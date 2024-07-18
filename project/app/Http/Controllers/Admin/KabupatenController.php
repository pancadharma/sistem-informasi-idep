<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateKabupatenRequest;
use App\Models\Kabupaten;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Provinsi;
use Gate;
use Psy\Command\WhereamiCommand;

class KabupatenController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('kabupaten_acceess'), Response::HTTP_FORBIDDEN, '403 Forbidden'); //Uncomment to apply permission provinsi_access index
        return view("master.kabupaten.index");
    }

    
    public function create()
    {
        
    }

    public function datakabupaten(){
        $kab = new Kabupaten();
        $data = $kab->dataKabupaten();
        return $data;
    }
    public function store(Request $request)
    {
        //
    }

    
    public function show(Kabupaten $kabupaten)
    {
        $kabupaten->load('provinsi');
        return response()->json($kabupaten); // Return province data as JSON
    }

    
    public function edit(Kabupaten $kabupaten)
    {
        $provinsi = Provinsi::withActive()->get(['id', 'nama']);
        $kabupaten->load('provinsi');   
        return [$kabupaten, "results" => $provinsi];
    }

    
    public function update(UpdateKabupatenRequest $request, Kabupaten $kabupaten)
    {
        return response()->json($request->all());

    }

    public function destroy(Kabupaten $kabupaten)
    {
        //
    }
}
