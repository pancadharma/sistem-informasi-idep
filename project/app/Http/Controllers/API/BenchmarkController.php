<?php

namespace App\Http\Controllers\API;

use Log;
use Exception;
use App\Models\User;
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
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Meals_Quality_Benchmark;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreBenchmarkRequest;
use App\Http\Requests\UpdateBenchmarkRequest;
use Illuminate\Validation\ValidationException;
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
            'program',          // Relasi ke table trprogram
            'jenisKegiatan',     // Dropdown jenis kegiatan
            'kegiatan.programOutcomeOutputActivity',           // Kegiatan berdasarkan filter program dan jenis kegiatan
            'desa',
            'kecamatan',
            'kabupaten',
            'provinsi',
            'compiler',
        ])->select('benchmark.id', 'benchmark.program_id', 'benchmark.usercompiler_id', 'benchmark.tanggalimplementasi', 'benchmark.jeniskegiatan_id', 'benchmark.kegiatan_id', 'benchmark.score');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('tanggalimplementasi', function ($item) {
                return Carbon::parse($item->tanggalimplementasi)->format('d-m-Y');
            })
            ->addColumn('program', fn($item) => $item->program->nama ?? 'N/A')
            ->addColumn('jenisKegiatan', fn($item) => $item->jeniskegiatan->nama ?? 'N/A')
            ->addColumn('kegiatan', fn($item) => $item->kegiatan->programOutcomeOutputActivity->nama ?? 'N/A')
            ->addColumn('compiler', fn($item) => $item->user_compiler->name ?? 'N/A')
            ->addColumn('score', fn($item) => $item->score ?? 'N/A')
            ->addColumn('action', function ($benchmark) {
                $buttons = [];

                if (auth()->user()->id === 1 || auth()->user()->can('benchmark_edit')) {
                    $editUrl = route('benchmark.edit', $benchmark->id);
                    $buttons[] = "<a href='{$editUrl}' class='btn btn-info btn-sm' title='" . __('global.edit') . " " . __('cruds.benchmark.label') . " {$benchmark->nama}'>
                                    <i class='bi bi-pencil-square'></i>
                                  </a>";
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
    public function storeBenchmark(StoreBenchmarkRequest $request)
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
        $users = User::select('id', 'nama');

        if ($search = $request->input('search')) {
            $users->where('nama', 'like', "%{$search}%");
        }

        $results = $users->paginate(10);

        return response()->json([
            'results' => $results->getCollection()->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->nama,
                ];
            }),
            'pagination' => [
                'more' => $results->hasMorePages(),
            ],
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
        $programId = $request->program_id;
        $jenisKegiatanId = $request->jeniskegiatan_id;

        $data = DB::table('trkegiatan')
        ->select(
            'trprogramoutcomeoutputactivity.id',
            'trprogramoutcomeoutputactivity.nama',
            'trprogramoutcomeoutputactivity.kode',
            'trprogramoutcomeoutputactivity.deskripsi',
            'trprogramoutcomeoutputactivity.indikator',
            'trprogramoutcomeoutputactivity.target',
            'trkegiatan.jeniskegiatan_id AS idjeniskegiatan',
            'mjeniskegiatan.nama AS namajeniskegiatan'
        )
        ->leftJoin('mjeniskegiatan', 'mjeniskegiatan.id', '=', 'trkegiatan.jeniskegiatan_id')
        ->leftJoin('trprogramoutcomeoutputactivity', 'trkegiatan.programoutcomeoutputactivity_id', '=', 'trprogramoutcomeoutputactivity.id')
        ->leftJoin('trprogramoutcomeoutput', 'trprogramoutcomeoutput.id', '=', 'trprogramoutcomeoutputactivity.programoutcomeoutput_id')
        ->leftJoin('trprogramoutcome', 'trprogramoutcome.id', '=', 'trprogramoutcomeoutput.programoutcome_id')
        ->where('trprogramoutcome.program_id', $programId)
        ->where('trkegiatan.jeniskegiatan_id', $jenisKegiatanId)
        ->get();

        return response()->json($data);
    }

    public function getLokasi(Request $request)
    {
        $kegiatanId = $request->kegiatan_id;

        $data = DB::table('trkegiatan_lokasi')
                ->join('kelurahan', 'trkegiatan_lokasi.desa_id', '=', 'kelurahan.id')
                ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
                ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
                ->join('provinsi', 'kabupaten.provinsi_id', '=', 'provinsi.id')
                ->where('trkegiatan_lokasi.kegiatan_id', $kegiatanId)
                ->select(
                    'provinsi.id as provinsi_id',
                    'provinsi.nama as provinsi_nama',
                    'kabupaten.id as kabupaten_id',
                    'kabupaten.nama as kabupaten_nama',
                    'kecamatan.id as kecamatan_id',
                    'kecamatan.nama as kecamatan_nama',
                    'kelurahan.id as desa_id',
                    'kelurahan.nama as desa_nama' 
                )->get();

        return response()->json($data);
    }

    
    public function updateBenchmark(UpdateBenchmarkRequest $request, $id)
    {
        try {
            $benchmark = Meals_Quality_Benchmark::findOrFail($id);
            $data = $request->validated();
        
            unset($data['program_id']); // abaikan update program_id

            $exists = DB::table('trkegiatan')
    ->join('trprogramoutcomeoutputactivity', 'trkegiatan.programoutcomeoutputactivity_id', '=', 'trprogramoutcomeoutputactivity.id')
    ->join('trprogramoutcomeoutput', 'trprogramoutcomeoutput.id', '=', 'trprogramoutcomeoutputactivity.programoutcomeoutput_id')
    ->join('trprogramoutcome', 'trprogramoutcome.id', '=', 'trprogramoutcomeoutput.programoutcome_id')
    ->where('trkegiatan.jeniskegiatan_id', $request->jeniskegiatan_id)
    ->where('trprogramoutcome.program_id', $request->program_id)
    ->exists();

        if (!$exists) {
    throw ValidationException::withMessages([
        'jeniskegiatan_id' => ['Jenis kegiatan tidak sesuai dengan program yang dipilih.']
    ]);
}
            $benchmark->update($data);
        
            return response()->json([
                'success' => true,
                'message' => 'Benchmark berhasil diperbarui',
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

    
    // Metode tambahan untuk generate tombol aksi, misalnya edit & view
    protected function generateButton($type, $btnClass, $icon, $title, $id)
    {
        return "<button type='button' class='btn btn-{$btnClass} btn-{$type}' data-id='{$id}' title='{$title}'><i class='bi bi-{$icon}'></i></button>";
    }
}
