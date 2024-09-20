<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class RolesController extends Controller
{
    public function index(){
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::pluck('nama', 'id');
        return view('master.users.index', compact('roles'));
    }
}
