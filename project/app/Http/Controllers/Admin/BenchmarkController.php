<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
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
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Meals_Quality_Benchmark;

class BenchmarkController extends Controller
{
    public function index()
    {
     
        return view('tr.benchmark.index');
    }

    public function create()
    {
        return view('tr.benchmark.create');
    }

    public function edit($id)
    {
        $benchmark = Meals_Quality_Benchmark::with(['program', 'jenisKegiatan', 'kegiatan', 'provinsi', 'desa', 'kecamatan', 'kabupaten', 'compiler'])->findOrFail($id);

        // $selectedProgram = Program::select('id', 'kode', 'nama')->get();

        // $selectedJenisKegiatan = [
        //     'id' => $benchmark->jeniskegiatan_id,
        //     'nama' => optional($benchmark->jenisKegiatan)->nama
        // ];

        $selectedKegiatan = [
            'id' => $benchmark->kegiatan_id,
            'kode' => optional($benchmark->kegiatan->programOutcomeOutputActivity)->kode,
            'nama' => optional($benchmark->kegiatan->programOutcomeOutputActivity)->nama
        ];

        return view('tr.benchmark.edit', compact(
            'benchmark',
            'selectedKegiatan'
        ));
    }

    public function getBenchmark(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'id' => 'nullable|array|min:1',
            'id.*' => 'integer',
            'program_id' => 'nullable|integer|exists:trprogram,id',
        ]);
    
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);
        $programId = $request->input('program_id');
    
        $query = Meals_Quality_Benchmark::query();
    
        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        } elseif (!empty($search)) {
            $query->where('program_name', 'like', "%{$search}%");
        }
    
        if (!empty($programId)) {
            $query->where('program_id', $programId);
        }
    
        $data = $query->select('id', 'program_name')->paginate(20, ['*'], 'page', $page);
    
        return response()->json([
            'results' => $data->items(),
            'pagination' => [
                'more' => $data->hasMorePages(),
            ],
        ]);
    }


    public function getKegiatanByProgramJenis(Request $request) {
        $data = Kegiatan::where('program_id', $request->program_id)
                        ->where('jenis_kegiatan_id', $request->jenis_kegiatan_id)
                        ->get();
        return response()->json($data);
    }

//    public function getWilayahByKegiatan($id) {
//         $kegiatan = Kegiatan::with(['kelurahan.kecamatan.kabupaten.provinsi'])->findOrFail($id);

//         return response()->json([
//             'desa_id' => $kegiatan->desa->id ?? null,
//             'desa_nama' => $kegiatan->desa->nama ?? '',
//             'kecamatan_id' => $kegiatan->desa->kecamatan->id ?? null,
//             'kecamatan_nama' => $kegiatan->desa->kecamatan->nama ?? '',
//             'kabupaten_id' => $kegiatan->desa->kecamatan->kabupaten->id ?? null,
//             'kabupaten_nama' => $kegiatan->desa->kecamatan->kabupaten->nama ?? '',
//             'provinsi_id' => $kegiatan->desa->kecamatan->kabupaten->provinsi->id ?? null,
//             'provinsi_nama' => $kegiatan->desa->kecamatan->kabupaten->provinsi->nama ?? '',
//         ]);
//     }

    
}
