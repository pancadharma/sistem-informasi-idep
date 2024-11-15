<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class KegiatanController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('kegiatan_access'), 403);
        return view('tr.kegiatan.index');
    }

    public function create()
    {
        return view('tr.kegiatan.create');
    }

    public function store(Request $request)
    {
        return view('tr.kegiatan.create');
    }

    public function show($id)
    {
        return view('tr.kegiatan.show');
    }

    public function edit($id)
    {
        return view('tr.kegiatan.edit');
    }

    public function update(Request $request, $id)
    {
        return view('tr.kegiatan.edit');
    }

    public function destroy($id)
    {
        return view('tr.kegiatan.destroy');
    }
}
