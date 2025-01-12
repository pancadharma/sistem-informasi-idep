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
    public function getProvinsi(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);

        if (!is_array($ids) && $ids !== null) {
            $ids = [$ids];
        }

        $data = Provinsi::when(!empty($ids), function ($query) use ($ids) {
            return $query->whereIn('id', $ids);
        }, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%");
        });

        $perPage = 20; // or whatever pagination size you want
        $results = $data->paginate($perPage, ['id', 'nama'], 'page', $page);

        return response()->json([
            'results' => $results->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->nama,
                ];
            })->all(),
            'pagination' => [
                'more' => $results->hasMorePages(),
            ],
        ]);
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
