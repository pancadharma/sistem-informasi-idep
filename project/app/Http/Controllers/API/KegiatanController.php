<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dusun;
use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;



class KegiatanController extends Controller
{
    public function getProvinsi(Request $request)
    // {
    //     $request->validate([
    //         'search'    => 'nullable|string|max:255',
    //         'page'      => 'nullable|integer|min:1',
    //         'id'        => 'nullable|array|min:1',
    //         'id.*'      => 'integer',
    //     ]);

    //     $search = $request->input('search', '');
    //     $page = $request->input('page', 1);
    //     $ids = $request->input('id', []);

    //     if (!is_array($ids) && $ids !== null) {
    //         $ids = [$ids];
    //     }

    //     $data = Provinsi::when(!empty($ids), function ($query) use ($ids) {
    //         return $query->whereIn('id', $ids);
    //     }, function ($query) use ($search) {
    //         return $query->where('nama', 'like', "%{$search}%");
    //     });

    //     $perPage = 20; // or whatever pagination size you want
    //     $results = $data->paginate($perPage, ['id', 'nama'], 'page', $page);

    //     return response()->json([
    //         'results' => $results->map(function ($item) {
    //             return [
    //                 'id' => $item->id,
    //                 'text' => $item->nama,
    //             ];
    //         })->all(),
    //         'pagination' => [
    //             'more' => $results->hasMorePages(),
    //         ],
    //     ]);
    // }
    {
        // Validate request inputs
        $request->validate([
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'id' => 'nullable|array|min:1', // Changed to array validation
            'id.*' => 'integer', // Validate each ID in the array
        ]);

        // Retrieve search, page, and ids inputs
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);

        // Convert single ID to array if needed
        if (!is_array($ids) && $ids !== null) {
            $ids = [$ids];
        }

        // Build query to include both name search and ids check
        $data = Provinsi::when(!empty($ids), function ($query) use ($ids) {
            return $query->whereIn('id', $ids);
        }, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%");
        });

        $data = $data->paginate(20, ['*'], 'page', $page);
        return response()->json($data);
    }

    public function getKabupaten(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
            'provinsi_id' => 'required|exists:provinsi,id'
        ]);


        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);
        $provinsiId = $request->input('provinsi_id');

        if (!is_array($ids) && $ids !== null) {
            $ids = [$ids];
        }

        // Build query to include both name search and ids check
        $data = Provinsi::when(!empty($ids), function ($query) use ($ids) {
            return $query->whereIn('id', $ids);
        }, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%");
        });

        $data = Kabupaten::where('provinsi_id', $provinsiId)->when(!empty($ids), function ($query) use ($ids) {
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
    public function getKecamatan(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
            'kabupaten_id' => 'required|exists:kabupaten,id'
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);
        $kabupatenId = $request->input('kabupaten_id');


        if (!is_array($ids) && $ids !== null) {
            $ids = [$ids];
        }

        $data = Kecamatan::where('kabupaten_id', $kabupatenId)->when(!empty($ids), function ($query) use ($ids) {
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
    public function getKelurahan(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
            'kecamatan_id' => 'required|exists:kecamatan,id'
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);
        $kecamatanId = $request->input('kecamatan_id');


        if (!is_array($ids) && $ids !== null) {
            $ids = [$ids];
        }

        $data = Kelurahan::where('kecamatan_id', $kecamatanId)->when(!empty($ids), function ($query) use ($ids) {
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
    public function getDusun(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
            'desa_id'   => 'required|exists:kelurahan,id'
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);
        $kecamatanId = $request->input('desa_id');


        if (!is_array($ids) && $ids !== null) {
            $ids = [$ids];
        }

        $data = Dusun::where('desa_id', $kecamatanId)->when(!empty($ids), function ($query) use ($ids) {
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
}
