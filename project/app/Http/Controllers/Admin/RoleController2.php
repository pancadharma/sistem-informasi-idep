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
        if ($request->ajax()) {
            $roles = Role::with('permissions');
            return DataTables::of($roles)
                ->addColumn('permissions', function ($role) {
                    $count = $role->permissions->count();
                    if ($count > 0) {
                        return $count . ' Permissions <button class="btn btn-xs btn-info view-permissions-btn" data-id="' . $role->id . '">View</button>';
                    }
                    return 'No Permissions';
                })
                ->addColumn('actions', function ($role) {
                    return '<button class="btn btn-sm btn-warning edit-btn" data-id="' . $role->id . '">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="' . $role->id . '">Delete</button>';
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
        $role->delete(); // This will soft delete the role

        return response()->json(['success' => true, 'message' => 'Role deleted successfully (soft deleted).']);
    }
}
