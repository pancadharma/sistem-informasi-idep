<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class DusunController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('dusun_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // return view('master.dusun.index');
    }

    public function create()
    {
        abort_if(Gate::denies('dusun_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('master.dusun.create');
    }

    public function store(Request $request)
    {
        // Code to store a new Dusun
    }

    public function show($dusun)
    {
        abort_if(Gate::denies('dusun_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('master.dusun.show', compact('id'));
    }

    public function edit($dusun)
    {
        abort_if(Gate::denies('dusun_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('master.dusun.edit', compact('id'));
    }

    public function update(Request $request, $dusun)
    {
        
    }

    public function destroy($dusun)
    {
        
    }
}
