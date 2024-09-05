<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Dusun;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use App\Http\Requests\StoreDusunRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DusunController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('dusun_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $provinsi = Provinsi::pluck('nama', 'id')->prepend(trans('global.selectProv'), '');
        return view('master.dusun.index', compact('provinsi'));
    }

    public function create()
    {
        abort_if(Gate::denies('dusun_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('master.dusun.create');
    }

    public function store(StoreDusunRequest $request)
    {
        try {
            $data = $request->validated();
            Dusun::create($data);
            return response()->json([
                'success'   => true,
                'message'   =>  __('cruds.data.data') .' '.__('cruds.dusun.title') .' '. $request->nama .' '. __('cruds.data.added'),
                'status'    => Response::HTTP_CREATED,
                'data'      => $data,
            ]);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
                'data'  => $request,
            ], 422);

        } catch (ModelNotFoundException $e) {
            // Handle model not found errors
            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
                'data'  => $request,
            ], 404);

        } catch (HttpException $e) {
            // Handle HTTP-specific exceptions
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage(),
                'data'      => $request,
            ], $e->getStatusCode());

        } catch (Exception $e) {
            // Handle all other exceptions
            return response()->json([
                'success'   => false,
                'data'      => $request,
                'message'   => 'An error occurred.',
                'error'     => $e->getMessage(),
            ], 500);
        }catch (QueryException $e){
            return response()->json([
                'success'   => false,
                'data'      => $request,
                'message'   => 'An error occurred.',
                'error'     => $e->getMessage(),
            ], 500);
        }
    }

    public function show($dusun)
    {
        abort_if(Gate::denies('dusun_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('master.dusun.show', compact('id'));
    }

    public function edit($dusun)
    {
        abort_if(Gate::denies('dusun_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('master.dusun.edit', compact('id'));
    }

    public function update(Request $request, $dusun)
    {
        
    }

    public function destroy($dusun)
    {
        
    }


    function getDusun(Request $request) {
        if ($request->ajax()) {
            $query = Dusun::select('dusun.id', 'dusun.kode', 'dusun.nama', 'dusun.aktif', 'dusun.kode_pos' , 'dusun.desa_id')->with('desa:id,nama');
            $data = DataTables::of($query)
            ->addColumn('action', function ($dusun) {
                return '<button type="button" class="btn btn-sm btn-info edit-dusun-btn" data-action="edit"
                data-desa-id="'. $dusun->id .'" title="'.__('global.edit') .' '. __('cruds.dusun.title') .' '. $dusun->nama .'">
                <i class="fas fa-pencil-alt"></i> Edit</button>
                <button type="button" class="btn btn-sm btn-primary view-dusun-btn" data-action="view"
                data-desa-id="'. $dusun->id .'" value="'. $dusun->id .'" title="'.__('global.view') .' '. __('cruds.dusun.title') .' '. $dusun->nama .'">
                <i class="fas fa-folder-open"></i> View</button>';
            })
            ->make(true);
            return $data;
        }
    }
}
