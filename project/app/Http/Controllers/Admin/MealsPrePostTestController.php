<?php

namespace App\Http\Controllers\Admin;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class MealsPrePostTestController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('prepost_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.prepost.index');
    }

    public function create()
    {
        // abort_if(Gate::denies('prepost_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $program = Program::all();
        return view('tr.prepost.create',compact('program'));
    }
}
