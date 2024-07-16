<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kabupaten;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Provinsi;
use Gate;

class KabupatenController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('kabupaten_acceess'), Response::HTTP_FORBIDDEN, '403 Forbidden'); //Uncomment to apply permission provinsi_access index
        // return view("master.kabupaten.index");.
        $kab = new Kabupaten();
        $data = $kab->dataKabupaten();
        return $data;
        
    }

    
    public function create()
    {
        //
    }

    public function datakabupaten(){
        $data = Kabupaten::dataKabupaten();
        return $data;
    }
    public function store(Request $request)
    {
        //
    }

    
    public function show(Kabupaten $kabupaten)
    {
        //
    }

    
    public function edit(Kabupaten $kabupaten)
    {
        //
    }

    
    public function update(Request $request, Kabupaten $kabupaten)
    {
        //
    }

    public function destroy(Kabupaten $kabupaten)
    {
        //
    }
}
