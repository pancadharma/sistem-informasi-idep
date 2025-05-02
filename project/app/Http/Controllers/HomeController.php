<?php

namespace App\Http\Controllers;

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

    public function getDashboardData(Request $request)
    {
        $query = DB::table('trmeals_penerima_manfaat')
            ->leftJoin('trprogram', 'trprogram.id', '=', 'trmeals_penerima_manfaat.program_id')
            ->leftJoin('trmeals_penerima_manfaat_kelompok_marjinal', 'trmeals_penerima_manfaat_kelompok_marjinal.trmeals_penerima_manfaat_id', '=', 'trmeals_penerima_manfaat.id')
            ->whereNull('trmeals_penerima_manfaat.deleted_at');

        if ($request->filled('program_id')) {
            $query->where('trmeals_penerima_manfaat.program_id', $request->program_id);
        }

        if ($request->filled('provinsi_id')) {
            $query->where('trmeals_penerima_manfaat.provinsi_id', $request->provinsi_id); // pastikan kolom ini ada
        }

        if ($request->filled('tahun')) {
            $tahun = $request->tahun;
            $query->whereYear('trprogram.tanggalmulai', '<=', $tahun)
                ->whereYear('trprogram.tanggalselesai', '>=', $tahun);
        }

        $data = $query->selectRaw("
            COUNT(DISTINCT trmeals_penerima_manfaat.id) AS semua,
            COUNT(DISTINCT CASE WHEN trmeals_penerima_manfaat.jenis_kelamin = 'laki' THEN trmeals_penerima_manfaat.id END) AS laki,
            COUNT(DISTINCT CASE WHEN trmeals_penerima_manfaat.jenis_kelamin = 'perempuan' THEN trmeals_penerima_manfaat.id END) AS perempuan,
            COUNT(DISTINCT CASE WHEN trmeals_penerima_manfaat.umur < 17 AND trmeals_penerima_manfaat.jenis_kelamin = 'laki' THEN trmeals_penerima_manfaat.id END) AS anak_laki,
            COUNT(DISTINCT CASE WHEN trmeals_penerima_manfaat.umur < 17 AND trmeals_penerima_manfaat.jenis_kelamin = 'perempuan' THEN trmeals_penerima_manfaat.id END) AS anak_perempuan,
            COUNT(DISTINCT CASE WHEN trmeals_penerima_manfaat_kelompok_marjinal.kelompok_marjinal_id = 3 THEN trmeals_penerima_manfaat.id END) AS disabilitas
        ")->first();

        return response()->json($data);
    }
}
