<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrProgramController extends Controller
{
    public function index(){

        return view('tr.program.index');

    }
    public function create(){

        return view('tr.program.create');

    }
}
