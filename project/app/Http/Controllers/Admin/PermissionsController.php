<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PermissionsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::query();
            return DataTables::of($permissions)
                ->addColumn('actions', function ($permission) {
                    return '<button class="btn btn-sm btn-warning edit-btn" data-id="' . $permission->id . '" data-nama="' . $permission->nama . '">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="' . $permission->id . '">Delete</button>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.permissions.index');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|unique:permissions,nama']);

        $permission = Permission::create(['nama' => $request->nama]);

        return response()->json($permission);
    }

    public function update(Request $request, Permission $permission)
    {
        // Validate the request, allowing the current permission's name
        // to be unchanged while ensuring uniqueness for others
        if ($permission->nama === $request->nama) {
            $request->validate(['nama' => 'required']);
        } else {
            $request->validate(['nama' => 'required|unique:permissions,nama']);
        }

        $request->validate(['nama' => 'required|unique:permissions,nama,' . $permission->id]);

        $permission->update(['nama' => $request->nama]);

        return response()->json($permission);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json(['success' => true]);
    }
}