<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Dusun;
use App\Models\Satuan;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\BenchmarkRequest;
use App\Models\Meals_Quality_Benchmark;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BenchmarkController extends Controller
{
    public function getBenchmarkDatatable(Request $request)
    {
        $data = Meals_Quality_Benchmark::with([
            'program', 'jenis_kegiatan', 'program_output_outcome_activity', 'dusun',
            'users'
        ])->get()
        ->map(function ($item) {
            $item->formatted_tanggal = Carbon::parse($item->tanggal_implementasi)->format('d-m-Y');
            return $item;
        });

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('program_name', fn($item) => $item->program->nama ?? 'N/A')
            ->addColumn('handler', fn($item) => $item->user_handler->name ?? 'N/A')
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

    public function storeBenchmark(BenchmarkRequest $request)
    {
        try {
            $data = $request->validated();
            $benchmark = Meals_Quality_Benchmark::create($data);
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

    public function getProv(Request $request)
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
        $perPage = 20;

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
        $perPage = 20;

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
        $perPage = 20;

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

    public function getDusuns(Request $request)
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
        $desaId = $request->input('desa_id');
        $perPage = 20;

        $cacheKey = "dusuns_desa_{$desaId}_search_{$search}_page_{$page}_ids_" . implode(',', $ids);

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($search, $page, $ids, $desaId, $perPage) {
            $query = Dusun::where('desa_id', $desaId);

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
    
    public function getSatuan(Request $request)
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

        $data = Satuan::when(!empty($ids), function ($query) use ($ids) {
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
