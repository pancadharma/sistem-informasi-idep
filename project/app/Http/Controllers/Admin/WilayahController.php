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

    function getProvinsi()
    {
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
    function getKabupaten(Request $request)
    {
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
    function getKecamatan(Request $request)
    {
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
    function getDesa(Request $request)
    {
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
    function getDusun(Request $request)
    {
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


    public function getProgramLokasi(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $provinsi = Provinsi::where('nama', 'like', "%{$search}%")->get();
        return response()->json($provinsi);
    }

    public function getKegiatanDesa(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
        ]);

        // Retrieve search and page inputs
        $search = $request->input('search', '');
        $page = $request->input('page', 1);

        // Query Dusun model with pagination
        $dusun = Dusun::where('nama', 'like', "%{$search}%")
            ->paginate(10, ['*'], 'page', $page);

        // Return paginated response
        return response()->json($dusun);
    }
}