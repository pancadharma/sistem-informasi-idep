<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class BenchmarkController extends Controller
{
    public function index()
    {
        return view('tr.benchmark.index');
    }

}
