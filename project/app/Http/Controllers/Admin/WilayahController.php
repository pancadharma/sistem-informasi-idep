<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dusun;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WilayahController extends Controller
{

    function getProvinsi(){
        // $provinsi = Provinsi::pluck('nama', 'id')->prepend(trans('global.selectProv'), '');
        $provinsi = Provinsi::get(['id', 'kode', 'nama'])
        ->map(function ($item) {
            return [
                'id'   => $item->id,
                'kode' => $item->kode,
                'text' => "{$item->kode} - {$item->nama}",
            ];
        });
        return response()->json($provinsi);
    }
    function getKabupaten(Request $request){
        $kabupaten = Kabupaten::where('provinsi_id', $request->id)
        ->get(['id', 'kode', 'nama'])
        ->map(function ($item) {
            return [
                'id'   => $item->id,
                'kode' => $item->kode,
                'text' => "{$item->kode} - {$item->nama}",
            ];
        });
        return response()->json($kabupaten);
    }
    function getKecamatan(Request $request){
        $kecamatan = Kecamatan::where('kabupaten_id', $request->id)
        ->get(['id', 'kode', 'nama'])
        ->map(function ($item) {
            return [
                'id'   => $item->id,
                'kode' => $item->kode,
                'text' => "{$item->kode} - {$item->nama}",
            ];
        });
        return response()->json($kecamatan);
    }
    function getDesa(Request $request){
        $desa = Kelurahan::where('kecamatan_id', $request->id)
        ->get(['id', 'kode', 'nama'])
        ->map(function ($item) {
            return [
                'id'   => $item->id,
                'kode' => $item->kode,
                'text' => "{$item->kode} - {$item->nama}",
            ];
        });
        return response()->json($desa);
    }
    function getDusun(Request $request){
        $dusun = Dusun::where('desa_id', $request->id)
        ->get(['id', 'kode', 'nama'])
        ->map(function ($item) {
            return [
                'id'   => $item->id,
                'kode' => $item->kode,
                'text' => "{$item->kode} - {$item->nama}",
            ];
        });
        return response()->json($dusun);
    }
}
