<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Satuan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSatuanRequest;
use App\Http\Requests\UpdateSatuanRequest;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SatuanController extends Controller
{
    public function index(){
        abort_if(Gate::denies('satuan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('master.satuan.index');
    }
    public function show(Satuan $satuan){
        return response()->json($satuan);
    }
    public function edit(Satuan $satuan){
        return response()->json($satuan);
    }
    public function create(){

    }
    public function store(StoreSatuanRequest $request, Satuan $satuan){
        try {
            $data = $request->validated();
            $satuan->create($data);
            return response()->json([
                'success' => true,
                "message" => __('cruds.data.data') .' '.__('cruds.satuan.title') .' '. $request->nama .' '. __('cruds.data.added'),
                'data'    => $data,
            ], Response::HTTP_OK);
            } catch (ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors(),
                ], Response::HTTP_BAD_REQUEST);
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found.',
                    'errors' => $e->getMessage(),
                ], Response::HTTP_NOT_FOUND);
            } catch (QueryException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found.',
                    'errors' =>  $e->getMessage()
                ], 404);
            } catch (HttpException $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], $e->getStatusCode());
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred.',
                    'error' => $e->getMessage(),
                ], 500);
        }
    }
    public function update(UpdateSatuanRequest $request, Satuan $satuan){
        try {
            $data = $request->validated();
            $satuan->update($data);
            return response()->json([
                'success' => true,
                "message" => __('cruds.data.data') .' '.__('cruds.satuan.title') .' '. $request->nama .' '. __('cruds.data.updated'),
                'data'    => $data,
            ], Response::HTTP_OK);
            } catch (ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors(),
                ], Response::HTTP_BAD_REQUEST);
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found.',
                    'errors' => $e->getMessage(),
                ], Response::HTTP_NOT_FOUND);
            } catch (QueryException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found.',
                    'errors' =>  $e->getMessage()
                ], 404);
            } catch (HttpException $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], $e->getStatusCode());
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred.',
                    'error' => $e->getMessage(),
                ], 500);
        }
    }
    public function getSatuan(Request $request){
        if ($request->ajax()) {
            $query = Satuan::all();
            $data = DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status', function($satuan) {
                    return match ($satuan->aktif) {
                        1 => '<div class="icheck-primary align-middle">
                                <input id="aktif_' . $satuan->id . '" data-aktif-id="' . $satuan->id . '" class="icheck-primary" alt="☑️ aktif" title="' . __("cruds.status.aktif") . '" type="checkbox" checked>
                                <label for="aktif_' . $satuan->id . '"><span class="btn-sm bg-success" hidden>' . __("cruds.status.aktif") . '</span></label>
                              </div>',
                        0 => '<div class="icheck-primary align-middle">
                                <input id="aktif_' . $satuan->id . '" data-aktif-id="' . $satuan->id . '" class="icheck-primary" alt="aktif" title="' . __("cruds.status.tidak_aktif") . '" type="checkbox">
                                <label for="aktif_' . $satuan->id . '"><span class="btn-sm bg-danger" hidden>' . __("cruds.status.tidak_aktif") . '</span></label>
                              </div>',
                    };
                })
                ->addColumn('action', function($satuan) {
                    $editButton = '';
                    $viewButton = '';
                    $deleteButton = '';
                    if (auth()->user()->can("satuan_edit")) {
                        $editButton = "<button type=\"button\" class=\"btn btn-sm btn-info edit-satuan-btn\"
                        data-action=\"edit\" data-satuan-id=\"{$satuan->id}\"
                        title=\"" . __('global.edit') . " " . __('cruds.satuan.title') . " {$satuan->nama}\">
                        <i class=\"fas fa-pencil-alt\"></i> " . __('global.edit') . "</button>";
                    }
                    $viewButton = "<button type=\"button\" class=\"btn btn-sm btn-primary view-satuan-btn\"
                    data-action=\"view\" data-satuan-id=\"{$satuan->id}\"
                    title=\"" . __('global.view') . " " . __('cruds.satuan.title') . " {$satuan->nama}\">
                    <i class=\"fas fa-folder-open\"></i> " . __('global.view') . "</button>";
                    return "$editButton $viewButton";
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
            return $data;
        }
    }
}
