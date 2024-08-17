<?php

namespace App\Http\Controllers\Admin;

use App\Models\Provinsi;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Gate;
// use Yajra\DataTables\DataTables;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

class DesaController extends Controller
{
    function index() {
        abort_if(Gate::denies('desa_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $provinsi = Provinsi::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('master.desa.index', compact('provinsi'));

        // $json = response()->json([
        //     'provinsi'  => $provinsi,
        //     'message'   => "Data Provinsi Loaded",
        //     'success'   => true,
        //     'status'    => Response::HTTP_CREATED
        // ]);
    }

    // function getDesa(Request $request) {
    //     // $desa = Kelurahan::with('kec:id,kode,nama,kabupaten_id', 'kecamatan.kabupaten:nama')->get();
    //     if($request->ajax()){
    //         // $provinsi = Provinsi::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');
    //         // $desa = Kelurahan::with('kec:id,kode,nama,kabupaten_id','kec.kabupaten:id,nama');
    //         $desa = Kelurahan::with(['kecamatan:id,kode,nama','kecamatan.kabupaten:id,nama']);
    //         $data = DataTables::of($desa)
    //         ->addColumn('action', function ($desa) {
    //             return '<button type="button" class="btn btn-sm btn-info edit-kec-btn" data-action="edit" 
    //             data-kecamatan-id="'. $desa->id .'" title="'.__('global.edit') .' '. __('cruds.kecamatan.title') .' '. $desa->nama .'">
    //             <i class="fas fa-pencil-alt"></i> Edit</button>
                
    //             <button type="button" class="btn btn-sm btn-primary view-kec-btn" data-action="view"
    //             data-kecamatan-id="'. $desa->id .'" value="'. $desa->id .'" title="'.__('global.view') .' '. __('cruds.kecamatan.title') .' '. $desa->nama .'">
    //             <i class="fas fa-folder-open"></i> View</button>';
    //         })
    //         ->make(true);
    //         // return view('master.desa.index', compact('provinsi'));
    //         return $data;
    //     }
    //     // return response()->json(['data' => $desa]);
        
    // }

    public function getDesa_old(Request $request) {
        if ($request->ajax()) {
            $desa = Kelurahan::with([
                'kecamatan:id,nama', 
                'kecamatan.kabupaten:id,nama'
            ])->select('id', 'nama','kode', 'aktif', 'kecamatan_id'); // Select only necessary columns from Kelurahan
            $data = DataTables::of($desa)
                ->addColumn('kecamatan_nama', function ($desa) {
                    return $desa->kecamatan ? $desa->kecamatan->nama : 'N/A'; // Add kecamatan name column
                })
                ->addColumn('action', function ($desa) {
                    return '<button type="button" class="btn btn-sm btn-info edit-kec-btn" data-action="edit" 
                    data-kecamatan-id="'. $desa->id .'" title="'.__('global.edit') .' '. __('cruds.kecamatan.title') .' '. $desa->nama .'">
                    <i class="fas fa-pencil-alt"></i> Edit</button>              
                    <button type="button" class="btn btn-sm btn-primary view-kec-btn" data-action="view"
                    data-kecamatan-id="'. $desa->id .'" value="'. $desa->id .'" title="'.__('global.view') .' '. __('cruds.kecamatan.title') .' '. $desa->nama .'">
                    <i class="fas fa-folder-open"></i> View</button>';
                })
                ->make(true);                
                return $data;
        }
    }
    function getDesa(Request $request) {
        if ($request->ajax()) {
            $query = Kelurahan::select('kelurahan.id', 'kelurahan.kode', 'kelurahan.nama', 'kelurahan.aktif', 'kelurahan.kecamatan_id')->with('kecamatan:id,nama');
            $data = DataTables::of($query)
            ->addColumn('action', function ($desa) {
                return '<button type="button" class="btn btn-sm btn-info edit-kec-btn" data-action="edit" 
                data-kecamatan-id="'. $desa->id .'" title="'.__('global.edit') .' '. __('cruds.kecamatan.title') .' '. $desa->nama .'">
                <i class="fas fa-pencil-alt"></i> Edit</button>              
                <button type="button" class="btn btn-sm btn-primary view-kec-btn" data-action="view"
                data-kecamatan-id="'. $desa->id .'" value="'. $desa->id .'" title="'.__('global.view') .' '. __('cruds.kecamatan.title') .' '. $desa->nama .'">
                <i class="fas fa-folder-open"></i> View</button>';
            })
            ->make(true);
            return $data;            
        }
    }
    
    
}
