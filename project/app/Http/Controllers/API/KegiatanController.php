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
use App\Models\User;
use Exception;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use PHPUnit\Event\Code\Throwable;

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
    public function storeApi(StoreKegiatanRequest $request, Kegiatan $kegiatan)
    {
        try {
            $user = User::findOrFail($request->user_id);
            $data = $request->validated();
            DB::beginTransaction();
            // $kegiatan = Kegiatan::create([
            //     'user_id' => $request->user_id,
            //     'programoutcomeoutputactivity_id' => $request->programoutcomeoutputactivity_id,
            //     'fasepelaporan' => $request->fasepelaporan,
            //     'jeniskegiatan_id' => $request->jeniskegiatan_id,
            // ]);
            $kegiatan = Kegiatan::create($data);
            $kegiatan->mitra()->sync($request->input('mitra_id', []));
            $kegiatan->sektor()->sync($request->input('sektor_id', []));

            $this->storeHasilKegiatan($request, $kegiatan);
            $this->storeLokasiKegiatan($request, $kegiatan);
            $this->storePenulisKegiatan($request, $kegiatan);
            $this->storeMediaDokumen($request, $kegiatan);



            DB::commit();
            return response()->json([
                'success' => true,
                'data'    => $data,
                'created by' => $user->nama,
                'message' => __('global.create_success'),
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create record: ' . $th->getMessage(),
                'error'   => $th->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to create record: ' . $e->getMessage()], 500);
        }
    }

    public function storeMediaDokumen(Request $request, Kegiatan $kegiatan)
    {
        $handleFileUploads = function ($files, $captions, $collectionName) use ($kegiatan) {
            $timestamp = now()->format('Ymd_His');
            $fileCount = 1;

            foreach ($files as $index => $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $kegiatanName = str_replace(' ', '_', $kegiatan->nama);
                $fileName = "{$kegiatanName}_{$timestamp}_{$fileCount}.{$extension}";
                $keterangan = $captions[$index] ?? $fileName;

                $media = $kegiatan
                    ->addMedia($file)
                    ->withCustomProperties([
                        'keterangan' => $keterangan,
                        'user_id' => auth()->user()->id,
                        'original_name' => $originalName,
                        'extension' => $extension
                    ])
                    ->usingName("{$kegiatanName}_{$originalName}_{$fileCount}")
                    ->usingFileName($fileName)
                    ->toMediaCollection($collectionName);

                $fileCount++;
            }
        };

        if ($request->hasFile('dokumen_pendukung')) {
            $handleFileUploads(
                $request->file('dokumen_pendukung'),
                $request->input('keterangan', []),
                'dokumen_pendukung'
            );
        }

        if ($request->hasFile('media_pendukung')) {
            $handleFileUploads(
                $request->file('media_pendukung'),
                $request->input('keterangan', []),
                'media_pendukung'
            );
        }
    }

    public function storeHasilKegiatan(StoreKegiatanRequest $request, Kegiatan $kegiatan)
    {
        $jenisKegiatan = (int)$request->input('jeniskegiatan_id');
        $idKegiatan = $kegiatan->id;

        $modelMapping = [
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

        if (!isset($modelMapping[$jenisKegiatan])) {
            throw new \InvalidArgumentException("Invalid jenisKegiatan: " . $jenisKegiatan);
        }

        $modelClass = $modelMapping[$jenisKegiatan];
        $validatedData = $request->validated();
        $validatedData['kegiatan_id'] = $idKegiatan;
        $modelClass::create($validatedData);

        return response()->json(['message' => 'Kegiatan created successfully'], 201);
    }
    protected function getTypeSpecificFields(int $jenisKegiatan): array
    {
        switch ($jenisKegiatan) {
            case 1:
                return ['assessmentyangterlibat', 'assessmenttemuan', 'assessmenttambahan', 'assessmenttambahan_ket', 'assessmentkendala', 'assessmentisu', 'assessmentpembelajaran'];
            case 2:
                return ['sosialisasiyangterlibat', 'sosialisasitemuan', 'sosialisasitambahan', 'sosialisasitambahan_ket', 'sosialisasikendala', 'sosialisasiisu', 'sosialisasipembelajaran'];
            case 3:
                return ['pelatihanpelatih', 'pelatihanhasil', 'pelatihandistribusi', 'pelatihandistribusi_ket', 'pelatihanrencana', 'pelatihanunggahan', 'pelatihanisu', 'pelatihanpembelajaran'];
            case 4:
                return ['pembelanjaandetailbarang', 'pembelanjaanmulai', 'pembelanjaanselesai', 'pembelanjaandistribusimulai', 'pembelanjaandistribusiselesai', 'pembelanjaanterdistribusi', 'pembelanjaanakandistribusi', 'pembelanjaanakandistribusi_ket', 'pembelanjaankendala', 'pembelanjaanisu', 'pembelanjaanpembelajaran'];
            case 5:
                return ['pengembanganjeniskomponen', 'pengembanganberapakomponen', 'pengembanganlokasikomponen', 'pengembanganyangterlibat', 'pengembanganrencana', 'pengembangankendala', 'pengembanganisu', 'pengembanganpembelajaran'];
            case 6:
                return ['kampanyeyangdikampanyekan', 'kampanyejenis', 'kampanyebentukkegiatan', 'kampanyeyangterlibat', 'kampanyeyangdisasar', 'kampanyejangkauan', 'kampanyerencana', 'kampanyekendala', 'kampanyeisu', 'kampanyepembelajaran'];
            case 7:
                return ['pemetaanyangdihasilkan', 'pemetaanluasan', 'pemetaanunit', 'pemetaanyangterlibat', 'pemetaanrencana', 'pemetaanisu', 'pemetaanpembelajaran'];
            case 8:
                return ['monitoringyangdipantau', 'monitoringdata', 'monitoringyangterlibat', 'monitoringmetode', 'monitoringhasil', 'monitoringkegiatanselanjutnya', 'monitoringkegiatanselanjutnya_ket', 'monitoringkendala', 'monitoringisu', 'monitoringpembelajaran'];
            case 9:
                return ['kunjunganlembaga', 'kunjunganpeserta', 'kunjunganyangdilakukan', 'kunjunganhasil', 'kunjunganpotensipendapatan', 'kunjunganrencana', 'kunjungankendala', 'kunjunganisu', 'kunjunganpembelajaran'];
            case 10:
                return ['konsultasilembaga', 'konsultasikomponen', 'konsultasiyangdilakukan', 'konsultasihasil', 'konsultasipotensipendapatan', 'konsultasirencana', 'konsultasikendala', 'konsultasiisu', 'konsultasipembelajaran'];
            case 11:
                return ['lainnyamengapadilakukan', 'lainnyadampak', 'lainnyasumberpendanaan_ket', 'lainnyayangterlibat', 'lainnyarencana', 'lainnyakendala', 'lainnyaisu', 'lainnyapembelajaran'];
            default:
                return [];
        }
    }


    public function storePenulisKegiatan(Request $request, Kegiatan $kegiatan)
    {
        $penulis = $request->input('penulis', []);
        $jabatan = $request->input('jabatan', []);

        if (count($penulis) !== count($jabatan))
        {
            throw new Exception('Penulis and Jabatan count mismatch.');
        }

        $dataPenulisJabatan = [];
        foreach ($penulis as $index => $penulisID) {
            $dataPenulisJabatan[$penulisID] = ['peran_id' => $jabatan[$index]];
        }

        $kegiatan->penulis()->sync($dataPenulisJabatan);
    }

    public function updatePenulisKegiatan(Request $request, Kegiatan $kegiatan)
    {
        try {
            $penulis = $request->input('penulis', []);
            $jabatan = $request->input('jabatan', []);

            if (count($penulis) !== count($jabatan)) {
                throw new Exception('Mismatched penulis and jabatan arrays length');
            }

            $penulisData = [];
            foreach ($penulis as $index => $penulisId) {
                if (!isset($jabatan[$index])) {
                    throw new Exception("Missing jabatan value for penulis ID $penulisId at index $index");
                }
                $penulisData[$penulisId] = ['peran_id' => $jabatan[$index]];
            }

            $kegiatan->penulis()->sync($penulisData); // sync handles adds, updates, and deletes
        } catch (Exception $e) {
            \Log::error('Error updating penulis: ' . $e->getMessage());
            throw $e;
        }
    }

    public function storeLokasiKegiatan(Request $request, Kegiatan $kegiatan)
    {
        $locationData = $this->prepareLocationData($request, $kegiatan);

        if ($locationData === false) {
            throw new \Exception("Invalid location data");
        }
        try {
            $kegiatan->lokasi()->createMany($locationData); // Use createMany for efficiency
        } catch (\Exception $e) {
            Log::error('Failed to store locations: ' . $e->getMessage());
            throw new Exception("Failed to store location data: " . $e->getMessage());
        }
    }


    protected function prepareLocationData(Request $request, Kegiatan $kegiatan)
    {
        $kecamatanIds = $request->input('kecamatan_id', []);
        $kelurahanIds = $request->input('kelurahan_id', []);
        $lokasiValues = $request->input('lokasi', []);
        $latValues = $request->input('lat', []);
        $longValues = $request->input('long', []);

        $arrayLengths = [
            count($kecamatanIds),
            count($kelurahanIds),
            count($lokasiValues),
            count($latValues),
            count($longValues),
        ];

        if (count(array_unique($arrayLengths)) !== 1) {
            return false; // Indicate inconsistent data
        }

        $locationData = [];
        $locationCount = count($kecamatanIds);
        for ($i = 0; $i < $locationCount; $i++) {

            $locationData[] = [
                'kegiatan_id' => $kegiatan->id,
                'desa_id' => $kelurahanIds[$i],
                'lokasi' => $lokasiValues[$i],
                'lat' => $latValues[$i],
                'long' => $longValues[$i],
            ];
        }
        return $locationData;
    }

    protected function updateLocations(Request $request, Kegiatan $kegiatan)
    {
        $newLocationData = $this->prepareLocationData($request, $kegiatan);

        if ($newLocationData === false) {
            throw new \Exception("Invalid location data");
        }
        $kegiatan->lokasi()->delete();
            foreach ($newLocationData as $location) {
            $kegiatan->lokasi()->create($location);
        }
    }

    public function updateApi(Request $request, Kegiatan $kegiatan)
    {
        try {
            DB::beginTransaction();

            $kegiatan->update([
                'programoutcomeoutputactivity_id' => $request->programoutcomeoutputactivity_id,
            ]);

            $this->syncRelationships($request, $kegiatan);
            $this->updateLocations($request, $kegiatan);

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $kegiatan,
                'message' => __('global.update_success'),
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update record: ' . $th->getMessage(),
                'error' => $th->getMessage(),
            ], 500);
        }
    }


    public function updateApi2(UpdateKegiatanRequest $request, Kegiatan $kegiatan)
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();
            $kegiatan->update($data);
            $kegiatan->mitra()->sync($request->input('mitra_id', []));
            $kegiatan->sektor()->sync($request->input('sektor_id', []));

            // Update hasil kegiatan jika diperlukan
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kegiatan berhasil diperbarui',
                'data' => $kegiatan,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kegiatan',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function getKegiatan(Request $request)
    {
        $query = Kegiatan::query();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('jeniskegiatan_id')) {
            $query->where('jeniskegiatan_id', $request->input('jeniskegiatan_id'));
        }

        $perPage = $request->input('per_page', 10);
        $results = $query->paginate($perPage);

        return response()->json($results);
    }


}
