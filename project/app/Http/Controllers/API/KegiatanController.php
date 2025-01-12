<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;



class KegiatanController extends Controller
{
    public function getProvinsi()
    {
        $provinsi = Provinsi::select('id', 'nama')->get();
        return response()->json($provinsi);
    }

    public function getKabupaten()
    {
        $provinsiId = request('provinsi_id');
        $kabupaten = Kabupaten::select('id', 'nama')->where('provinsi_id', $provinsiId)->get();

        return response()->json($kabupaten);
    }

    public function getKecamatan()
    {
        $kabupatenId = request('kabupaten_id');
        $kecamatan = Kecamatan::select('id', 'nama')->where('kabupaten_id', $kabupatenId)->get();

        return response()->json($kecamatan);
    }

    public function getKelurahan()
    {
        $kecamatanId = request('kecamatan_id');
        $kelurahan = Kelurahan::select('id', 'nama')->where('kecamatan_id', $kecamatanId)->get();

        return response()->json($kelurahan);
    }
}
