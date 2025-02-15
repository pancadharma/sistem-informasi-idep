<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKegiatanRequest;
use App\Models\Dusun;
use App\Models\Jenis_Kegiatan;
use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kegiatan;
use App\Models\Kegiatan_Assessment;
use App\Models\Kegiatan_Kampanye;
use App\Models\Kegiatan_Konsultasi;
use App\Models\Kegiatan_Kunjungan;
use App\Models\Kegiatan_Lainnya;
use App\Models\Kegiatan_Lokasi;
use App\Models\Kegiatan_Monitoring;
use App\Models\Kegiatan_Pelatihan;
use App\Models\Kegiatan_Pembelanjaan;
use App\Models\Kegiatan_Pemetaan;
use App\Models\Kegiatan_Pengembangan;
use App\Models\Kegiatan_Sosialisasi;
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


    // store kegiatan related to jenis kegiatan
    public function storeKegiatanHasil(Request $request, Kegiatan $kegiatan)
    {
        $jenisKegiatan = $request->input('jeniskegiatan_id');
        $idKegiatan = $kegiatan->id;

        switch ($jenisKegiatan) {
            case 1: // Assessment
                Kegiatan_Assessment::create(array_merge($request->only([
                    'assessmentyangterlibat',
                    'assessmenttemuan',
                    'assessmenttambahan',
                    'assessmenttambahan_ket',
                    'assessmentkendala',
                    'assessmentisu',
                    'assessmentpembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 2: // Sosialisasi
                Kegiatan_Sosialisasi::create(array_merge($request->only([
                    'sosialisasiyangterlibat',
                    'sosialisasitemuan',
                    'sosialisasitambahan',
                    'sosialisasitambahan_ket',
                    'sosialisasikendala',
                    'sosialisasiisu',
                    'sosialisasipembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 3: // Pelatihan
                Kegiatan_Pelatihan::create(array_merge($request->only([
                    'pelatihanpelatih',
                    'pelatihanhasil',
                    'pelatihandistribusi',
                    'pelatihandistribusi_ket',
                    'pelatihanrencana',
                    'pelatihanunggahan',
                    'pelatihanisu',
                    'pelatihanpembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 4: // Pembelanjaan
                Kegiatan_Pembelanjaan::create(array_merge($request->only([
                    'pembelanjaandetailbarang',
                    'pembelanjaanmulai',
                    'pembelanjaanselesai',
                    'pembelanjaandistribusimulai',
                    'pembelanjaandistribusiselesai',
                    'pembelanjaanterdistribusi',
                    'pembelanjaanakandistribusi',
                    'pembelanjaanakandistribusi_ket',
                    'pembelanjaankendala',
                    'pembelanjaanisu',
                    'pembelanjaanpembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 5: // Pengembangan
                Kegiatan_Pengembangan::create(array_merge($request->only([
                    'pengembanganjeniskomponen',
                    'pengembanganberapakomponen',
                    'pengembanganlokasikomponen',
                    'pengembanganyangterlibat',
                    'pengembanganrencana',
                    'pengembangankendala',
                    'pengembanganisu',
                    'pengembanganpembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 6: // Kampanye
                Kegiatan_Kampanye::create(array_merge($request->only([
                    'kampanyeyangdikampanyekan',
                    'kampanyejenis',
                    'kampanyebentukkegiatan',
                    'kampanyeyangterlibat',
                    'kampanyeyangdisasar',
                    'kampanyejangkauan',
                    'kampanyerencana',
                    'kampanyekendala',
                    'kampanyeisu',
                    'kampanyepembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 7: // Pemetaan
                Kegiatan_Pemetaan::create(array_merge($request->only([
                    'pemetaanyangdihasilkan',
                    'pemetaanluasan',
                    'pemetaanunit',
                    'pemetaanyangterlibat',
                    'pemetaanrencana',
                    'pemetaanisu',
                    'pemetaanpembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 8: // Monitoring
                Kegiatan_Monitoring::create(array_merge($request->only([
                    'monitoringyangdipantau',
                    'monitoringdata',
                    'monitoringyangterlibat',
                    'monitoringmetode',
                    'monitoringhasil',
                    'monitoringkegiatanselanjutnya',
                    'monitoringkegiatanselanjutnya_ket',
                    'monitoringkendala',
                    'monitoringisu',
                    'monitoringpembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 9: // Kunjungan
                Kegiatan_Kunjungan::create(array_merge($request->only([
                    'kunjunganlembaga',
                    'kunjunganpeserta',
                    'kunjunganyangdilakukan',
                    'kunjunganhasil',
                    'kunjunganpotensipendapatan',
                    'kunjunganrencana',
                    'kunjungankendala',
                    'kunjunganisu',
                    'kunjunganpembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 10: // Konsultasi
                Kegiatan_Konsultasi::create(array_merge($request->only([
                    'konsultasilembaga',
                    'konsultasikomponen',
                    'konsultasiyangdilakukan',
                    'konsultasihasil',
                    'konsultasipotensipendapatan',
                    'konsultasirencana',
                    'konsultasikendala',
                    'konsultasiisu',
                    'konsultasipembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 11: // Lainnya
                Kegiatan_Lainnya::create(array_merge($request->only([
                    'lainnyamengapadilakukan',
                    'lainnyadampak',
                    'lainnyasumberpendanaan_ket',
                    'lainnyayangterlibat',
                    'lainnyarencana',
                    'lainnyakendala',
                    'lainnyaisu',
                    'lainnyapembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            default:
                // Handle invalid jenisKegiatan (e.g., throw an exception)
                throw new \Exception("Invalid jenisKegiatan: " . $jenisKegiatan);
        }
    }
    public function getActivityTypes()
    {
        return [
            1 => Kegiatan_Assessment::class,
            2 => Kegiatan_Sosialisasi::class,
            3 => Kegiatan_Pelatihan::class,
            4 => Kegiatan_Pembelanjaan::class,
            5 => Kegiatan_Pengembangan::class,
            6 => Kegiatan_Kampanye::class,
            7 => Kegiatan_Pemetaan::class,
            8 => Kegiatan_Monitoring::class,
            9 => Kegiatan_Kunjungan::class,
            10 => Kegiatan_Konsultasi::class,
            11 => Kegiatan_Lainnya::class,
        ];
    }

    public function storeKegiatanLokasi(StoreKegiatanRequest $request, Kegiatan $kegiatan)
    {
        $jenisKegiatan = $request->input('jeniskegiatan_id');
    }

}
