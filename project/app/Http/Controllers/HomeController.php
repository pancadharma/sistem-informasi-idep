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

    // public function getFilteredProvinsi(Request $request)
    // {
    //     $query = Provinsi::query();

    //     // Filter berdasarkan input pengguna
    //     if ($request->filled('provinsi_id')) {
    //         $query->where('id', $request->provinsi_id);
    //     }

    //     $provinsiList = $query->select('id', 'nama', 'latitude', 'longitude')->get();

    //     return response()->json($provinsiList);
    // }


    // public function getFilteredProvinsi($id = null)
    // {
    //     // Query dasar ke tabel provinsi
    //     $query = DB::table('provinsi as p')
    //         ->select(
    //             'p.id',
    //             'p.nama',
    //             'p.latitude',
    //             'p.longitude',
    //             // Subquery atau Join untuk menghitung desa unik
    //             // CONTOH MENGGUNAKAN trkegiatan_lokasi: Hitung desa unik yang ada di trkegiatan_lokasi
    //             // Sesuaikan join dan tabel ini sesuai struktur data 'penerima manfaat' Anda
    //             DB::raw('(SELECT COUNT(DISTINCT tl.dusun_id)
    //                     FROM kabupaten kab
    //                     JOIN kecamatan kec ON kab.id = kec.kabupaten_id
    //                     JOIN kelurahan kel ON kec.id = kel.kecamatan_id
    //                     JOIN dusun     dus ON kel.id = dus.desa_id

    //                     JOIN trmeals_penerima_manfaat tl ON dus.id = tl.dusun_id
    //                     WHERE kab.provinsi_id = p.id
    //                    ) as jumlah_desa') // Alias harus 'jumlah_desa' atau sesuai yg dipakai di JS
    //         );


    //     // Filter berdasarkan ID jika diberikan
    //     if ($id) {
    //         $query->where('p.id', $id);
    //     }

    //     // Hanya ambil provinsi yang punya data desa (opsional, tapi disarankan)
    //     // Ini memastikan subquery tidak menghasilkan NULL jika tidak ada desa terkait
    //      $query->whereExists(function ($subquery) {
    //          $subquery->select(DB::raw(1))
    //                   ->from('kabupaten as kab')
    //                   ->join('kecamatan as kec', 'kab.id', '=', 'kec.kabupaten_id')
    //                   ->join('kelurahan as kel', 'kec.id', '=', 'kel.kecamatan_id')
    //                   ->join('dusun     as dus', 'kel.id', '=', 'dus.desa_id')
    //                   ->join('trmeals_penerima_manfaat as tl', 'dus.id', '=', 'tl.dusun_id') // Sesuaikan tabel jika perlu
    //                   ->whereColumn('kab.provinsi_id', 'p.id');
    //      });


    //     $provinsiData = $query->get();

    //     // Pastikan latitude dan longitude adalah float
    //     $provinsiData = $provinsiData->map(function ($item) {
    //         $item->latitude = (float) $item->latitude;
    //         $item->latitude = (float) $item->latitude;
    //         $item->longitude = (float) $item->longitude;
    //         $item->jumlah_desa = (int) $item->jumlah_desa; // Pastikan integer
    //         return $item;
    //     });


    //     return response()->json($provinsiData);
    // }

    public function getFilteredProvinsi($id = null)
    {
        $query = Provinsi::query();

        if ($id) {
            $query->where('id', $id);
        }

        $provinsiList = $query->select('id', 'nama', 'latitude', 'longitude')->get();

        // Hitung jumlah desa per provinsi
        $desaCounts = Meals_Penerima_Manfaat::with('dusun.desa.kecamatan.kabupaten.provinsi')
            ->whereNull('deleted_at')
            ->get()
            ->groupBy(function ($item) {
                return optional($item->dusun?->desa?->kecamatan?->kabupaten?->provinsi)->id;
            })
            ->map(function ($group) {
                return $group->pluck('dusun.desa.id')->unique()->count();
            });

        $provinsiList->each(function ($provinsi) use ($desaCounts) {
            $provinsi->total_desa = $desaCounts[$provinsi->id] ?? 0;
        });

        return response()->json($provinsiList);
    }




}
