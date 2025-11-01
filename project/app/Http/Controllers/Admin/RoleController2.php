<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RoleController2 extends Controller
{
    public function index(Request $request)
    {
        // abort if user has no permission to delete roles
        if ($request->ajax()) {
            $roles = Role::with('permissions');
            return DataTables::of($roles)
                ->addColumn('permissions', function ($role) {
                    $count = $role->permissions->count();
                    if ($count > 0) {
                        return $count . ' Permissions <button class="btn btn-xs btn-info view-permissions-btn" data-id="' . $role->id . '"><i class="fas fa-eye"></i></button>';
                    }
                    return 'No Permissions';
                })
                // ->addColumn('actions', function ($role) {
                //     return '<button title=\"" . __('global.edit') . " " . __('cruds.role.title') . " {$role->nama}\" class="btn btn-sm btn-warning edit-btn" data-id="' . $role->id . '"><i class="fas fa-edit"></i></button>

                //     <button title=\"" . __('global.delete') . " " . __('cruds.role.title') . " {$role->nama}\" class="btn btn-sm btn-danger delete-btn" data-id="' . $role->id . '"><i class="fas fa-trash"></i></button>';
                // })


                ->addColumn('actions', function ($role) {
                    $editButton = '';
                    $delButton = '';
                    if (auth()->user()->can("role_edit")) {
                        $editButton = "<button type=\"button\" class=\"btn btn-sm btn-warning edit-btn\"
                        data-action=\"edit\" data-id=\"{$role->id}\"
                        title=\"" . __('global.edit') . " " . __('cruds.role.title') . " {$role->nama}\">
                        <i class=\"fas fa-pencil-alt\"></i> " . __('global.edit') . "</button>";
                    }
                    if (auth()->user()->can("role_delete")) {
                        $delButton = "<button type=\"button\" class=\"btn btn-sm btn-danger delete-btn\"
                        data-action=\"delete\" data-id=\"{$role->id}\"
                        title=\"" . __('global.delete') . " " . __('cruds.role.title') . " {$role->nama}\">
                        <i class=\"fas fa-trash\"></i> " . __('global.delete') . "</button>";
                    }
                    return "$editButton $delButton";
                })
                ->rawColumns(['permissions', 'actions'])
                ->make(true);
        }

        return view('admin.roles2.index');
    }

    public function create()
    {
        $permissions = Permission::all()->pluck('nama', 'id');
        return response()->json($permissions);
    }

    public function store(Request $request)
    {
        // abort_if(!auth()->user()->can('role_create'), 403, 'Unauthorized action.');
        $request->validate([
            'nama' => 'required|unique:roles,nama',
            'permissions' => 'array',
        ]);

        $role = Role::create(['nama' => $request->nama]);
        $role->permissions()->sync($request->input('permissions', []));

        return response()->json($role);
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        // Ensure permissions are returned as an array of objects with 'id' and 'nama'
        $assignedPermissions = $role->permissions->map(function ($permission) {
            return [
                'id' => $permission->id,
                'nama' => $permission->nama,
            ];
        })->toArray();

        return response()->json([
            'id' => $role->id,
            'nama' => $role->nama,
            'permissions' => $assignedPermissions,
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'nama' => 'required|unique:roles,nama,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['nama' => $request->nama]);
        $role->permissions()->sync($request->input('permissions', []));

        return response()->json($role);
    }

    public function destroy(Role $role)
    {
        // abort_if(!auth()->user()->can('role_delete'), 403, 'Unauthorized action.');
        // Check if the role is assigned to any users

        if(in_array($role->nama, ['admin', 'user'])) {
            return response()->json(['success' => false, 'message' => 'Cannot delete system role.'], 400);
        }
        if(auth()->user()->id === $role->id) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own role.'], 400);
        }
        if($role->id === 1) { // Assuming ID 1 is the admin role
            return response()->json(['success' => false, 'message' => 'Cannot delete admin role.'], 400);
        }
        // if has auth user is has no permission to delete roles
        if (!auth()->user()->can('role_delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }
        if ($role->users()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete role assigned to users.'], 400);
        }
        $role->delete(); // This will soft delete the role

        return response()->json(['success' => true, 'message' => 'Role deleted successfully (soft deleted).']);
    }
}
