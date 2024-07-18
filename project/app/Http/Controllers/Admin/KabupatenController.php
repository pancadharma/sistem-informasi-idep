<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Exception;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateKabupatenRequest;

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
        $data = $request->all();
        try {
            $kabupaten->update($request->validated());
            
            return response()->json([
                'status'    => 'success',
                'message'   => "Data ". $request->nama ." Updated Successfully",
                'data'      => $data,
            ],201);

        } catch (Exception $e) {
            $status = 'error';
            $message = $e->getMessage();
            return response()->json([
                'status'    => $status,
                'message'=> $message,
            ], 400);
        }
    }

    public function destroy(Kabupaten $kabupaten)
    {
        //
    }
}
