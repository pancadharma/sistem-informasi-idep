<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dusun;
use App\Models\Jenis_Kegiatan;
use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kegiatan_Lokasi;
use App\Models\Kelurahan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

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
        $data = Kabupaten::where('provinsi_id', $provinsiId)
            ->when(!empty($ids), function ($query) use ($ids) {
                return $query->whereIn('id', $ids);
            }, function ($query) use ($search) {
                return $query->where('nama', 'like', "%{$search}%");
            });

        $perPage = 20; // or whatever pagination size you want
        $results = $data->paginate($perPage, ['*'], 'page', $page);

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


    /**
     * Get kabupaten geojson data by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getKabupatenGeojson(int $id): JsonResponse
    {
        try {
            $kabupaten = Kabupaten::findOrFail($id);

            return response()->json([
                'success' => true,
                'path' => json_decode($kabupaten->path, true), // Decode JSON if necessary
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kabupaten not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function getProvinsiGeojson(int $id): JsonResponse
    {
        try {
            $provinsi = Provinsi::findOrFail($id);

            return response()->json([
                'success' => true,
                'path' => json_decode($provinsi->path, true), // Decode JSON if necessary
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Provinsi not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function getJenisKegiatan(Request $request)
    {
        $jenisKegiatan = Jenis_Kegiatan::select('id', 'nama')->get()->mapWithKeys(function ($item) {
            return [$item->id => $item->nama];
        })->toArray();

        if ($request->has('id')) {
            // If requesting specific ID(s)
            $id = $request->input('id');
            $data = collect($jenisKegiatan)
                ->filter(function ($value, $key) use ($id) {
                    return $key == $id;
                })
                ->map(function ($text, $id) {
                    return [
                        'id' => $id,
                        'nama' => $text
                    ];
                })
                ->values()
                ->all();

            return response()->json(['data' => $data]);
        }

        // For dropdown listing
        $first = __('cruds.kegiatan.basic.bentuk');
        // $second =  __('cruds.kegiatan.basic.sektor');

        $groupedData = [
            $first => array_slice($jenisKegiatan, 0, 11, true),
            // $second => array_slice($jenisKegiatan, 11, null, true)
        ];
        return response()->json($groupedData);
    }


    // update the API/KegiatanController with api to store , update, and get data
    public function storeApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'programoutcomeoutputactivity_id'   => 'required|exists:trprogramoutcomeoutputactivity,id',

            // validation of trkegiatan_lokasi
            'kelurahan_id'                      => ['array'],
            'kelurahan_id.*'                    => ['nullable', 'integer', 'exists:kelurahan,id'],
            'kecamatan_id'                      => ['array'],
            'kecamatan_id.*'                    => ['nullable', 'integer', 'exists:kecamatan,id'],
            'lokasi'                            => ['array'],
            'lokasi.*'                          => ['nullable', 'string',],
            'lat'                               => ['array'],
            'lat.*'                             => ['nullable', 'string',],
            'long'                              => ['array'],
            'long.*'                            => ['nullable', 'string',],


            // 'kecamatan_id.*'                    => 'required|exists:kecamatan,id',
            // 'kelurahan_id.*'                    => 'required|exists:kelurahan,id',
            // 'lokasi.*'                          => 'required|string',
            // 'lat.*'                             => 'required|numeric',
            // 'long.*'                            => 'required|numeric',


            // this input for model Kegiatan
            // 'jeniskegiatan_id'                  => ['required', 'exists:mjeniskegiatan,id'],

            // 'mitra_id'                          => ['array'],
            // 'mitra_id.*'                        => ['nullable', 'integer', 'exists:mpartner,id'],

            // 'user_id'                           => ['required', 'exist:users,id'],
            // 'fasepelaporan'                     => ['required', 'integer'],


            // 'tanggalmulai',
            // 'tanggalselesai',
            // 'status',
            // 'deskripsilatarbelakang',
            // 'deskripsitujuan',
            // 'deskripsikeluaran',
            // 'deskripsiyangdikaji',
            // 'penerimamanfaatdewasaperempuan',
            // 'penerimamanfaatdewasalakilaki',
            // 'penerimamanfaatdewasatotal',
            // 'penerimamanfaatlansiaperempuan',
            // 'penerimamanfaatlansialakilaki',
            // 'penerimamanfaatlansiatotal',
            // 'penerimamanfaatremajaperempuan',
            // 'penerimamanfaatremajalakilaki',
            // 'penerimamanfaatremajatotal',
            // 'penerimamanfaatanakperempuan',
            // 'penerimamanfaatanaklakilaki',
            // 'penerimamanfaatanaktotal',
            // 'penerimamanfaatdisabilitasperempuan',
            // 'penerimamanfaatdisabilitaslakilaki',
            // 'penerimamanfaatdisabilitastotal',
            // 'penerimamanfaatnondisabilitasperempuan',
            // 'penerimamanfaatnondisabilitaslakilaki',
            // 'penerimamanfaatnondisabilitastotal',
            // 'penerimamanfaatmarjinalperempuan',
            // 'penerimamanfaatmarjinallakilaki',
            // 'penerimamanfaatmarjinaltotal',
            // 'penerimamanfaatperempuantotal',
            // 'penerimamanfaatlakilakitotal',
            // 'penerimamanfaattotal',
            // 'created_at',
            // 'updated_at',
            // other validation fields related to other tables
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $kegiatan = Trkegiatan::create([
                'programoutcomeoutputactivity_id' => $request->programoutcomeoutputactivity_id,

            ]);

            // trkegiatan_lokasi
            // Get the arrays of data
            $kecamatanIds = $request->input('kecamatan_id', []);
            $kelurahanIds = $request->input('kelurahan_id', []);
            $lokasiValues = $request->input('lokasi', []);
            $latValues = $request->input('lat', []);
            $longValues = $request->input('long', []);

            // Check if all arrays have the same length
            $arrayLengths = [
                count($kecamatanIds),
                count($kelurahanIds),
                count($lokasiValues),
                count($latValues),
                count($longValues),
            ];

            if (count(array_unique($arrayLengths)) !== 1) {
                DB::rollback();
                return response()->json(['error' => 'Array lengths are inconsistent'], 400);
            }
            $locationCount = count($kecamatanIds); // You could use any of the arrays

            // Loop through the arrays and create location records
            for ($i = 0; $i < $locationCount; $i++) {
                Kegiatan_Lokasi::create([
                    'kegiatan_id' => $kegiatan->id,
                    'desa_id' => $kelurahanIds[$i],
                    'lokasi' => $lokasiValues[$i],
                    'lat' => $latValues[$i],
                    'long' => $longValues[$i],
                ]);
            }

            DB::commit();
            return response()->json(
                [
                    'success' => true,
                    'data' => $kegiatan
                ],
                201
            );
        } catch (\Throwable $th) {
            //throw $th;
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to create record: ' . $e->getMessage()], 500);
        }
    }
}
