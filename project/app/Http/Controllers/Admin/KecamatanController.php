<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Provinsi;
// use App\Http\Requests\MassDestroyKecamatanRequest;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreKecamatanRequest;
use App\Http\Requests\UpdateKecamatanRequest;
use Symfony\Component\HttpFoundation\Response;



class KecamatanController extends Controller
{
    public function index(){
        // abort_if(Gate::denies('kecamatan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $kabupaten = Kabupaten::all();
        $provinsi = Provinsi::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        
        return view("master.kecamatan.index", compact('provinsi'));
    }

    public function store(StoreKecamatanRequest $request){


    }
    public function show(Kecamatan $kecamatan){

    }

    public function provinsi(){
        $provinsi = Provinsi::withActive()->get(['id','kode','nama']);
        return response()->json($provinsi);

    }
    public function provinsi_details(Provinsi $provinsi){
        $provinsi = Provinsi::with('kabupaten_kota:provinsi_id,id,kode,nama,type')
        ->where('id', $provinsi->id)
        ->get(['id','kode','nama']);
        return response()->json($provinsi);

    }
    public function kab(Request $request){
        $kabupaten = Kabupaten::where('aktif', 1)->get(['id','kode','nama']);
        $kab = Kabupaten::where("provinsi_id",$request->id)->pluck('id','nama');

        return response()->json($kab);
    }

    public function kab_details(Kabupaten $kabupaten)
    {
        $kabupaten = Kabupaten::with('kecamatan:kabupaten_id,id,kode,nama')
            ->where('id', $kabupaten->id)
            ->get(['id','kode','nama']);
        return response()->json($kabupaten);
    }
    
    public function kec(Kabupaten $kabupaten)
    {
        $kabupaten = Kecamatan::with('kabupaten')->where('kabupaten_id', $kabupaten->id)
            ->where('aktif', 1)
            ->get(['id', 'nama']);
        return response()->json($kabupaten);
    }

    public function datakecamatan(){
        $kecamatan = Kecamatan::where('aktif', 1)
            ->with('kabupaten:id,nama,provinsi_id', 'kabupaten.provinsi:nama')
            ->get();

        $data = DataTables::of($kecamatan)
            ->addColumn('action', function ($kecamatan) {
                return '<button type="button" class="btn btn-sm btn-info edit-kab-btn" data-action="edit" data-kecamatan-id="'. $kecamatan->id .'" title="'.__('global.edit') .' '. __('cruds.kecamatan.title') .' '. $kecamatan->nama .'"><i class="fas fa-pencil-alt"></i> Edit</button>

                <button type="button" class="btn btn-sm btn-primary view-kecamatan-btn" data-action="view" data-kecamatan-id="'. $kecamatan->id .'" value="'. $kecamatan->id .'" title="'.__('global.view') .' '. __('cruds.kecamatan.title') .' '. $kecamatan->nama .'"><i class="fas fa-folder-open"></i> View</button>';
            })
            ->make(true);
        return $data;
    }
}
