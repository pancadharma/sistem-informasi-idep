<?php

namespace App\Http\Controllers;

use App\Models\Meals_Penerima_Manfaat;
use App\Models\Program;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $programs = Program::all();
        $provinsis = Provinsi::all();

        $years = DB::table('trprogram')
            ->select(DB::raw('YEAR(tanggalmulai) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('home', compact('programs', 'provinsis', 'years'));
    }
    function getDashboardData(Request $request)
    {
        $data = Meals_Penerima_Manfaat::query()
            ->with(['program', 'kelompokMarjinal'])
            ->when($request->provinsi_id, function ($query, $provinsi_id) {
                $query->whereHas('dusun.desa.kecamatan.kabupaten.provinsi', function ($q) use ($provinsi_id) {
                    $q->where('id', $provinsi_id);
                });
            })
            ->when($request->program_id, function ($query, $program_id) {
                $query->where('program_id', $program_id);
            })
            ->when($request->tahun, function ($query, $tahun) {
                $query->whereHas('program', function ($q) use ($tahun) {
                    $q->whereYear('tanggalmulai', '<=', $tahun)
                        ->whereYear('tanggalselesai', '>=', $tahun);
                });
            })
            ->whereNull('deleted_at')
            ->get();

        // Hitung keluarga unik (berdasarkan nama kepala keluarga atau nama sendiri jika dia kepala keluarga)
        $uniqueFamilies = $data->map(function ($item) {
            return $item->is_head_family ? trim(strtolower($item->nama)) : trim(strtolower($item->head_family_name));
        })->filter() // hapus null kosong
            ->unique()  // hanya keluarga unik
            ->count();

        return response()->json([
            'semua'           => $data->count(),
            'laki'            => $data->where('jenis_kelamin', 'laki')->count(),
            'perempuan'       => $data->where('jenis_kelamin', 'perempuan')->count(),
            'anak_laki'       => $data->where('jenis_kelamin', 'laki')->where('umur', '<', 17)->count(),
            'anak_perempuan'  => $data->where('jenis_kelamin', 'perempuan')->where('umur', '<', 17)->count(),
            'disabilitas'     => $data->filter(fn($item) => $item->kelompokMarjinal->contains('id', 3))->count(),
            'keluarga'        => $uniqueFamilies,
        ]);
    }

    public function getDesaPerProvinsiChartData(Request $request)
    {
        $data = Meals_Penerima_Manfaat::with('dusun.desa.kecamatan.kabupaten.provinsi')
            ->whereNull('deleted_at')
            ->get()
            ->groupBy(function ($item) {
                return optional($item->dusun?->desa?->kecamatan?->kabupaten?->provinsi)->nama;
            })
            ->map(function ($group) {
                $uniqueDesa = $group->pluck('dusun.desa.id')->unique()->count();
                return [
                    'total_desa' => $uniqueDesa,
                ];
            })
            ->filter(fn($v, $k) => !is_null($k))
            ->sortByDesc('total_desa')
            ->map(function ($item, $provinsi) {
                return [
                    'provinsi'    => $provinsi,
                    'total_desa'  => $item['total_desa'],
                ];
            })
            ->values(); // to reset keys (make it an array of objects)

        return response()->json($data);
    }

    public function getFilteredProvinsi(Request $request)
    {
        $query = Provinsi::query();

        // Filter berdasarkan input pengguna
        if ($request->filled('provinsi_id')) {
            $query->where('id', $request->provinsi_id);
        }

        $provinsiList = $query->select('id', 'nama', 'latitude', 'longitude')->get();

        return response()->json($provinsiList);
    }




}
