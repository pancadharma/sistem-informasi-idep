<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Exception;
use App\Models\Partner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartnerRequest;
use App\Http\Requests\UpdatePartnerRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;


class PartnersController extends Controller
{
    public function index(){
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('master.partner.index');
    }


    public function store(StorePartnerRequest $request, Partner $partner){
        try {
            // Validate the request data
            $data = $request->validated();
            $partner->create($data);
            return response()->json([
                'success' => true,
                "message" => __('cruds.data.data') .' '.__('cruds.partner.title') .' '. $request->nama .' '. __('cruds.data.added'),
                'data'    => $data,
            ], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (ModelNotFoundException $e) {
            // Handle model not found errors
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

    public function edit(Partner $partner) {
        return response()->json($partner);
    }

    public function show(Partner $partner) {
        return response()->json($partner);
    }

    public function update(UpdatePartnerRequest $request, Partner $partner){
        try {
            $data = $request->validated();
            $partner->update($data);
            return response()->json([
                'success' => true,
                "message" => __('cruds.data.data') .' '.__('cruds.partner.title') .' '. $request->nama .' '. __('cruds.data.updated'),
                'data'    => $data,
            ], Response::HTTP_OK);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (ModelNotFoundException $e) {
            // Handle model not found errors
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

    public function getPartners(Request $request) {
        if ($request->ajax()) {
            $query = Partner::all();
            $data = DataTables::of($query)
                ->addColumn('status', function($partner) {
                    return match ($partner->aktif) {
                        1 => '<div class="icheck-primary d-inline">
                                <input id="aktif_' . $partner->id . '" data-aktif-id="' . $partner->id . '" class="icheck-primary" alt="☑️ aktif" title="' . __("cruds.status.aktif") . '" type="checkbox" checked>
                                <label for="aktif_' . $partner->id . '"></label>
                              </div>',
                        0 => '<div class="icheck-primary d-inline">
                                <input id="aktif_' . $partner->id . '" data-aktif-id="' . $partner->id . '" class="icheck-primary" alt="aktif" title="' . __("cruds.status.aktif") . '" type="checkbox">
                                <label for="aktif_' . $partner->id . '"></label>
                              </div>',
                    };
                })
                ->addColumn('action', function($partner) {
                    $editButton = '';
                    $viewButton = '';
                    $deleteButton = '';
                    if (auth()->user()->can("partner_edit")) {
                        $editButton = "<button type=\"button\" class=\"btn btn-sm btn-info edit-partner-btn\"
                        data-action=\"edit\" data-partner-id=\"{$partner->id}\"
                        title=\"" . __('global.edit') . " " . __('cruds.partner.title') . " {$partner->nama}\">
                        <i class=\"fas fa-pencil-alt\"></i> " . __('global.edit') . "</button>";
                    }
                    $viewButton = "<button type=\"button\" class=\"btn btn-sm btn-primary view-partner-btn\"
                    data-action=\"view\" data-partner-id=\"{$partner->id}\"
                    title=\"" . __('global.view') . " " . __('cruds.partner.title') . " {$partner->nama}\">
                    <i class=\"fas fa-folder-open\"></i> " . __('global.view') . "</button>";
                    return "$editButton $viewButton";
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
            return $data;
        }
    }

}
