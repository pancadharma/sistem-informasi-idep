<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Illuminate\Http\Request;
use App\Models\TargetReinstra;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreTargetReinstraRequest;
use App\Http\Requests\UpdateTargetReinstraRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TargetReinstraController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('target_reinstra_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('master.target_reinstra.index');
    }

    public function create()
    {
        abort_if(Gate::denies('target_reinstra_create'), Response::HTTP_ACCEPTED, '403 Forbidden');
        return view('master.target_reinstra.create');
    }

    public function store(StoreTargetReinstraRequest $request, TargetReinstra $target_reinstra)
    {
        try {
            // Validate the request data
            $data = $request->validated();
            $target_reinstra->create($data);
            return response()->json([
                'success' => true,
                "message" => __('cruds.data.data') .' '.__('cruds.reinstra.title') .' '. $request->nama .' '. __('cruds.data.added'),
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

    public function show(TargetReinstra $target_reinstra)
    {
        return response()->json($target_reinstra);
    }

    public function edit(TargetReinstra $target_reinstra) {
        return response()->json($target_reinstra);
    }

    public function update(UpdateTargetReinstraRequest $request, TargetReinstra $target_reinstra )
    {
        try {
            $data = $request->validated();
            $target_reinstra->update($data);
            return response()->json([
                'success' => true,
                "message" => __('cruds.data.data') .' '.__('cruds.reinstra.title') .' '. $request->nama .' '. __('cruds.data.updated'),
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

    public function destroy(string $id)
    {

    }

    public function getTargetReinstra(Request $request) {
        if ($request->ajax()) {
            $query = TargetReinstra::all();
            $data = DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status', function($reinstra) {
                    return match ($reinstra->aktif) {
                        1 => '<div class="icheck-primary d-inline">
                                <input id="aktif_' . $reinstra->id . '" data-aktif-id="' . $reinstra->id . '" class="icheck-primary" alt="☑️ aktif" title="' . __("cruds.status.aktif") . '" type="checkbox" checked>
                                <label for="aktif_' . $reinstra->id . '"></label>
                              </div>',
                        0 => '<div class="icheck-primary d-inline">
                                <input id="aktif_' . $reinstra->id . '" data-aktif-id="' . $reinstra->id . '" class="icheck-primary" alt="aktif" title="' . __("cruds.status.aktif") . '" type="checkbox">
                                <label for="aktif_' . $reinstra->id . '"></label>
                              </div>',
                    };
                })
                ->addColumn('action', function($reinstra) {
                    $editButton = '';
                    $viewButton = '';
                    $deleteButton = '';
                    if (auth()->user()->can("target_reinstra_edit")) {
                        $editButton = "<button type=\"button\" class=\"btn btn-sm btn-info edit-reinstra-btn\"
                        data-action=\"edit\" data-reinstra-id=\"{$reinstra->id}\"
                        title=\"" . __('global.edit') . " " . __('cruds.reinstra.title') . " {$reinstra->nama}\">
                        <i class=\"fas fa-pencil-alt\"></i> " . __('global.edit') . "</button>";
                    }
                    $viewButton = "<button type=\"button\" class=\"btn btn-sm btn-primary view-reinstra-btn\"
                    data-action=\"view\" data-reinstra-id=\"{$reinstra->id}\"
                    title=\"" . __('global.view') . " " . __('cruds.reinstra.title') . " {$reinstra->nama}\">
                    <i class=\"fas fa-folder-open\"></i> " . __('global.view') . "</button>";
                    return "$editButton $viewButton";
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
            return $data;
        }
    }








}
