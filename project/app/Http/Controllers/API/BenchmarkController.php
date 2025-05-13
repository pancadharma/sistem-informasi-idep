<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Program;
use App\Models\Kegiatan;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Jenis_Kegiatan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\BenchmarkRequest;
use App\Models\Meals_Quality_Benchmark; // Model untuk benchmark (table trmealsqb)
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BenchmarkController extends Controller
{
    /**
     * Tampilkan list benchmark dengan filter program.
     * Data benchmark diambil dari table trmealsqb.
     */
    public function getBenchmarkDatatable(Request $request)
    {
        $query = Meals_Quality_Benchmark::with([
            'program:id,nama',          // Relasi ke table trprogram
            'jenis_kegiatan:id,nama',     // Dropdown jenis kegiatan
            'kegiatan:id,nama',           // Kegiatan berdasarkan filter program dan jenis kegiatan
            'kelurahan:id,nama',
            'kecamatan:id,nama',
            'kabupaten:id,nama',
            'provinsi:id,nama',
            'user_compiler:id,name',
        ])->select('id', 'program_id', 'user_compiler_id', 'tanggal_implementasi');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('tanggal_implementasi', function ($item) {
                return Carbon::parse($item->tanggal_implementasi)->format('d-m-Y');
            })
            ->addColumn('program_name', fn($item) => $item->program->nama ?? 'N/A')
            ->addColumn('compiler', fn($item) => $item->user_compiler->name ?? 'N/A')
            ->addColumn('action', function ($item) {
                $buttons = [];

                if (auth()->user()->can('meals_quality_benchmark_edit')) {
                    $buttons[] = $this->generateButton('edit', 'info', 'pencil-square', 'Edit Benchmark ' . $item->id, $item->id);
                }

                if (auth()->user()->can('meals_quality_benchmark_view')) {
                    $buttons[] = $this->generateButton('view', 'primary', 'folder2-open', 'Lihat Benchmark ' . $item->id, $item->id);
                }

                return "<div class='button-container'>" . implode(' ', $buttons) . "</div>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    /**
     * Simpan benchmark baru.
     * Semua input divalidasi melalui BenchmarkRequest.
     */
    public function storeBenchmark(BenchmarkRequest $request)
    {
        try {
            $data = $request->validated();
            $benchmark = Meals_Quality_Benchmark::create($data);

            // Jika perlu, clear cache list benchmark
            Cache::forget('meals_quality_benchmark_list');

            return response()->json([
                'success' => true,
                'message' => 'Benchmark berhasil disimpan',
                'data' => $benchmark
            ], Response::HTTP_CREATED);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Benchmark tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan database',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan tidak terduga',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Lookup compiler (data user) untuk dropdown compiler.
     */
    public function getCompilers(Request $request)
    {
        $search = $request->input('search', '');
    
        $query = \App\Models\User::query();
    
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
    
        $results = $query->paginate(10);
    
        return response()->json([
            'results' => $results->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name
                ];
            }),
            'pagination' => [
                'more' => $results->hasMorePages()
            ]
        ]);
    }
    
    /**
     * Lookup program dari table trprogram.
     * Dilengkapi relasi output dan activities untuk filter (seperti di transaksi kegiatan).
     */
    public function getPrograms(Request $request)
    {
        if ($request->ajax()) {
            $query = Program::with(['outcome.output.activities' => function ($query) {
                $query->select('id', 'kode', 'nama', 'deskripsi', 'indikator', 'target', 'programoutcomeoutput_id', 'created_at');
            }])->get();

            return DataTables::of($query)
                ->addColumn('activities', function ($row) {
                    $activities = [];
                    foreach ($row->outcome as $out) {
                        foreach ($out->output as $come_output) {
                            foreach ($come_output->activities as $activity) {
                                $activities[] = $activity->kode;
                            }
                        }
                    }
                    return implode(', ', $activities);
                })
                ->addColumn('action', function ($row) {
                    $hasActivities = false;
                    foreach ($row->outcome as $out) {
                        foreach ($out->output as $come_output) {
                            if ($come_output->activities->isNotEmpty()) {
                                $hasActivities = true;
                                break 2;
                            }
                        }
                    }

                    $button = '<button type="button" class="btn btn-sm btn-danger select-program" data-id="' . $row->id . '" data-kode="' . $row->kode . '" data-nama="' . $row->nama . '"';
                    $button .= $hasActivities ? '' : ' disabled';
                    $button .= '><i class="bi bi-plus"></i></button>';

                    return $button;
                })
                ->rawColumns(['action', 'activities'])
                ->make(true);
        }
    }
    
    /**
     * Lookup jenis kegiatan untuk dropdown.
     */
    public function getJenisKegiatan(Request $request)
    {
        $search = $request->search;
        
        $query = Jenis_Kegiatan::query();
        
        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $results = $query->paginate(10);

        return response()->json([
            'results' => $results->items(),
            'pagination' => ['more' => $results->hasMorePages()]
        ]);
    }
    
    /**
     * Lookup kegiatan berdasarkan program_id dan jenis_kegiatan_id.
     * Dipakai untuk form benchmark ketika memilih kegiatan.
     */
    public function getKegiatan(Request $request)
    {
        $search = $request->search;
        $programId = $request->program_id;
        $jenisKegiatanId = $request->jenis_kegiatan_id;

        $query = Kegiatan::query();

        if ($programId) {
            $query->where('program_id', $programId);
        }

        if ($jenisKegiatanId) {
            $query->where('jenis_kegiatan_id', $jenisKegiatanId);
        }

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $results = $query->paginate(10);

        return response()->json([
            'results' => $results->items(),
            'pagination' => ['more' => $results->hasMorePages()]
        ]);
    }
    
    /**
     * Lookup provinsi dari table trkegiatan berdasarkan kegiatan yang dipilih.
     */
    public function getProv(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,id'
        ]);

        $provinsis = DB::table('trkegiatan')
            ->join('provinsi', 'trkegiatan.provinsi_id', '=', 'provinsi.id')
            ->where('trkegiatan.kegiatan_id', $request->kegiatan_id)
            ->select('provinsi.id', 'provinsi.nama')
            ->distinct()
            ->get();

        return response()->json([
            'results' => $provinsis->map(fn($item) => [
                'id' => $item->id,
                'text' => $item->nama
            ])
        ]);
    }
    
    /**
     * Lookup kabupaten berdasarkan provinsi yang didapat dari trkegiatan.
     */
    public function getKabupatens(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
            'provinsi_id'   => 'required|exists:provinsi,id'
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);
        $provId = $request->input('provinsi_id');
        $perPage = 10;

        $cacheKey = "kabupatens_provinsi_{$provId}_search_{$search}_page_{$page}_ids_" . implode(',', $ids);

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($search, $page, $ids, $provId, $perPage) {
            $query = Kabupaten::where('provinsi_id', $provId);

            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            } elseif ($search !== '') {
                $query->where('nama', 'like', "%{$search}%");
            }

            $results = $query->paginate($perPage, ['id', 'nama'], 'page', $page);

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
        });
    }
    
    /**
     * Lookup kecamatan berdasarkan kabupaten dari trkegiatan.
     */
    public function getKecamatans(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
            'kabupaten_id'   => 'required|exists:kabupaten,id'
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);
        $kabId = $request->input('kabupaten_id');
        $perPage = 10;

        $cacheKey = "kecamatans_kabupaten_{$kabId}_search_{$search}_page_{$page}_ids_" . implode(',', $ids);

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($search, $page, $ids, $kabId, $perPage) {
            $query = Kecamatan::where('kabupaten_id', $kabId);

            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            } elseif ($search !== '') {
                $query->where('nama', 'like', "%{$search}%");
            }

            $results = $query->paginate($perPage, ['id', 'nama'], 'page', $page);

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
        });
    }
    
    /**
     * Lookup desa berdasarkan kecamatan dari trkegiatan.
     */
    public function getDesas(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
            'kecamatan_id'   => 'required|exists:kecamatan,id'
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);
        $kecamatanId = $request->input('kecamatan_id');
        $perPage = 10;

        $cacheKey = "desas_desa_{$kecamatanId}_search_{$search}_page_{$page}_ids_" . implode(',', $ids);

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($search, $page, $ids, $kecamatanId, $perPage) {
            $query = Kelurahan::where('kecamatan_id', $kecamatanId);

            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            } elseif ($search !== '') {
                $query->where('nama', 'like', "%{$search}%");
            }

            $results = $query->paginate($perPage, ['id', 'nama'], 'page', $page);

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
        });
    }
    
    // Metode tambahan untuk generate tombol aksi, misalnya edit & view
    protected function generateButton($type, $btnClass, $icon, $title, $id)
    {
        return "<button type='button' class='btn btn-{$btnClass} btn-{$type}' data-id='{$id}' title='{$title}'><i class='bi bi-{$icon}'></i></button>";
    }
}
