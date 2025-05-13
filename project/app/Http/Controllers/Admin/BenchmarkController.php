<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Meals_Quality_Benchmark;
use Illuminate\Support\Facades\Gate;
use App\Models\Program;
use App\Models\Jenis_Kegiatan;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kegiatan;
use App\Models\Kelurahan;
use App\Models\Provinsi;
use App\Models\User;

class BenchmarkController extends Controller
{
    public function index()
    {
     
        return view('tr.benchmark.index');
    }

    public function create()
    {
        return view('tr.benchmark.create', [
            'programs' => Program::all(),
            'jenis_kegiatan' => Jenis_Kegiatan::all(),
            'kegiatan' => Kegiatan::all(),
            'desa' => Kelurahan::all(),
            'kecamatan' => Kecamatan::all(),
            'provinsi' => Provinsi::all(),
            'kabupaten' => Kabupaten::all(),
            'users' => User::all(),
        ]);
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
