<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrProgramController extends Controller
{
    public function index(){

        return view('tr.program.index');

    }
    public function create(){

        if (auth()->user()->id == 1 || auth()->user()->can('program_create')) {
            return view('tr.program.create');
        }
        abort(Response::HTTP_FORBIDDEN, 'Unauthorized Permission. Please ask your administrator to assign permissions to access and create a program');
    }
}
