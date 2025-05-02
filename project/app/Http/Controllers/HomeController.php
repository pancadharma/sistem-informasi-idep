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

    // public function getDashboardData(Request $request)
    // {
    //     $query = DB::table('trmeals_penerima_manfaat')
    //         ->leftJoin('trprogram', 'trprogram.id', '=', 'trmeals_penerima_manfaat.program_id')
    //         ->leftJoin('trmeals_penerima_manfaat_kelompok_marjinal', 'trmeals_penerima_manfaat_kelompok_marjinal.trmeals_penerima_manfaat_id', '=', 'trmeals_penerima_manfaat.id')
    //         ->whereNull('trmeals_penerima_manfaat.deleted_at');

    //     if ($request->filled('program_id')) {
    //         $query->where('trmeals_penerima_manfaat.program_id', $request->program_id);
    //     }

    //     if ($request->filled('provinsi_id')) {
    //         $query->where('trmeals_penerima_manfaat.provinsi_id', $request->provinsi_id); // pastikan kolom ini ada
    //     }

    //     if ($request->filled('tahun')) {
    //         $tahun = $request->tahun;
    //         $query->whereYear('trprogram.tanggalmulai', '<=', $tahun)
    //             ->whereYear('trprogram.tanggalselesai', '>=', $tahun);
    //     }

    //     $data = $query->selectRaw("
    //         COUNT(DISTINCT trmeals_penerima_manfaat.id) AS semua,
    //         COUNT(DISTINCT CASE WHEN trmeals_penerima_manfaat.jenis_kelamin = 'laki' THEN trmeals_penerima_manfaat.id END) AS laki,
    //         COUNT(DISTINCT CASE WHEN trmeals_penerima_manfaat.jenis_kelamin = 'perempuan' THEN trmeals_penerima_manfaat.id END) AS perempuan,
    //         COUNT(DISTINCT CASE WHEN trmeals_penerima_manfaat.umur < 17 AND trmeals_penerima_manfaat.jenis_kelamin = 'laki' THEN trmeals_penerima_manfaat.id END) AS anak_laki,
    //         COUNT(DISTINCT CASE WHEN trmeals_penerima_manfaat.umur < 17 AND trmeals_penerima_manfaat.jenis_kelamin = 'perempuan' THEN trmeals_penerima_manfaat.id END) AS anak_perempuan,
    //         COUNT(DISTINCT CASE WHEN trmeals_penerima_manfaat_kelompok_marjinal.kelompok_marjinal_id = 3 THEN trmeals_penerima_manfaat.id END) AS disabilitas
    //     ")->first();

    //     return response()->json($data);
    // }

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

    // public function getDesaPerProvinsiChartData(Request $request)
    // {
    //     $data = DB::table('trmeals_penerima_manfaat as pm')
    //         ->join('dusun', 'pm.dusun_id', '=', 'dusun.id')
    //         ->join('kelurahan', 'dusun.desa_id', '=', 'kelurahan.id')
    //         ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
    //         ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
    //         ->join('provinsi', 'kabupaten.provinsi_id', '=', 'provinsi.id')
    //         ->selectRaw('provinsi.nama as provinsi, count(distinct kelurahan.id) as total_desa')
    //         ->groupBy('provinsi.nama')
    //         ->orderByDesc('total_desa')
    //         ->get();

    //     return response()->json([
    //         'bar' => $data,
    //         'pie' => $data // Jika pie chart masih ingin digunakan dengan data yang sama
    //     ]);
    // }

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
}
