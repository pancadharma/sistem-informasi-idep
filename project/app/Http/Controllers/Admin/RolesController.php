<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

class RolesController extends Controller
{
    public function index(){
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::pluck('nama', 'id');
        $permissions = Permission::pluck('nama','id');
        
        return view('master.role.index', compact('permissions'));
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
    public function getPermission(Request $request){
        
        $permissions = Permission::get(['id', 'nama'])
        ->map(function ($item) {
            return [
                'id'   => $item->id,
                'text' => $item->nama,
            ];
        });
        return response()->json($permissions);
    }
    function getRole(Request $request) {
        if ($request->ajax()) {
            $query = Role::with('permissions');
            $data = DataTables::of($query)
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
        return response()->json($role);
    }
    
    

}
