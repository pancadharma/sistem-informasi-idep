<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\UpdateRoleRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RolesController extends Controller
{
    public function index(){
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::pluck('nama', 'id');
        $permissions = Permission::pluck('nama','id');
        $permissions_data = Permission::get(['id', 'nama'])
        ->map(function ($item) {
            return [
                'id'   => $item->id,
                'text' => $item->nama,
            ];
        });
        // response()->json($permissions);
        return view('master.role.index', compact('permissions', 'permissions_data'));
    }
    public function create(){

        $permissions = Role::get(['id', 'nama'])
        ->map(function ($item) {
            return [
                'id'   => $item->id,
                'text' => $item->nama,
            ];
        });
        return response()->json($permissions);
    }
    // public function getPermission(Request $request){

    //     $permissions = Permission::get(['id', 'nama'])
    //     ->map(function ($item) {
    //         return [
    //             'id'   => $item->id,
    //             'text' => $item->nama,
    //         ];
    //     });
    //     // return response()->json($permissions);
    //     return response()->json([ "data" => $permissions]);
    // }
    public function getPermission(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);

        // Fetch permissions based on search and pagination
        $permissions = Permission::where('nama', 'like', "%{$search}%")
                                 ->paginate(10, ['*'], 'page', $page);

        // Format the response
        $response = [
            'data' => $permissions->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'nama' => $permission->nama
                ];
            }),
            'pagination' => [
                'more' => $permissions->hasMorePages()
            ]
        ];

        return response()->json($response);
    }



    function getRole(Request $request) {
        if ($request->ajax()) {
            $query = Role::with('permissions');
            $data = DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('permissions', function ($role) {
                    return $role->permissions->map(function ($permission) {
                        return "<span class=\"btn btn-warning btn-xs\">{$permission->nama}</span>";
                    })->implode(' ');
                })
                ->addColumn('status', function($role){
                    return match ($role->aktif) {
                        1 => '<div class="icheck-primary d-inline">
                                <input id="aktif_' . $role->id . '" data-aktif-id="' . $role->id . '" class="icheck-primary" alt="☑️ aktif" title="' . __("cruds.status.aktif") . '" type="checkbox" checked>
                                <label for="aktif_' . $role->id . '"></label>
                              </div>',
                        0 => '<div class="icheck-primary d-inline">
                                <input id="aktif_' . $role->id . '" data-aktif-id="' . $role->id . '" class="icheck-primary" alt="aktif" title="' . __("cruds.status.aktif") . '" type="checkbox">
                                <label for="aktif_' . $role->id . '"></label>
                              </div>',
                    };
                })
                ->addColumn('action', function ($role) {
                    $editButton = '';
                    $viewButton = '';
                    if (auth()->user()->can("role_edit")) {
                        $editButton = "<button type=\"button\" class=\"btn btn-sm btn-info edit-role-btn\"
                        data-action=\"edit\" data-role-id=\"{$role->id}\"
                        title=\"" . __('global.edit') . " " . __('cruds.role.title') . " {$role->nama}\">
                        <i class=\"fas fa-pencil-alt\"></i> Edit</button>";
                    }
                    // if (auth()->user()->can("role_view")) {
                        $viewButton = "<button type=\"button\" class=\"btn btn-sm btn-primary view-role-btn\"
                        data-action=\"view\" data-role-id=\"{$role->id}\"
                        title=\"" . __('global.view') . " " . __('cruds.role.title') . " {$role->nama}\">
                        <i class=\"fas fa-folder-open\"></i> View</button>";
                    // }
                    return "$editButton $viewButton";
                })
                ->rawColumns(['permissions', 'status', 'action'])
                ->make(true);
            return $data;
        }
    }

    public function show(Role $role){
        abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load('permissions');
        $role->load('users');
        // return response()->json([$role, $user_role]);
        return response()->json($role);
    }
    public function edit(Role $role) {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all()->map(function ($item) {
            return [
                'id'   => $item->id,
                'text' => $item->nama,
            ];
        });

        $role->load('permissions', 'users');

        return response()->json([
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role){
        try {
            $data = $request->validated();
            $role->update($data);
            $role->permissions()->sync($request->input('permissions', []));
            return response()->json([
                'success'   => true,
                'message'   =>  __('cruds.data.data') .' '.__('cruds.role.title') .' '. $request->nama .' '. __('cruds.data.updated'),
                'status'    => Response::HTTP_CREATED,
                'data'      => $data
            ], 200);

        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (ModelNotFoundException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
            ], 404);

        } catch (HttpException $e) {
            // Handle HTTP-specific exceptions
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());

        } catch (Exception $e) {
            // Handle all other exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function store(StoreRoleRequest $request)
    {
        try {
            // Validate the request data
            $data = $request->validated();
            $role = Role::create($data);
            $role->permissions()->sync($request->input('permissions', []));

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully!',
                'data' => $data,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);

        } catch (ModelNotFoundException $e) {
            // Handle model not found errors
            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
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

}
