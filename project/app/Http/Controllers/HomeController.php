<?php

namespace App\Http\Controllers;

use App\Models\Meals_Penerima_Manfaat;
use App\Models\Program;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        // $programs = Program::all()->where('status', true)->sortBy('nama');
        $programs = Program::all()->sortByDesc('created_at');
        $provinsis = Provinsi::all()->where('aktif', true)->sortBy('nama');

        $years = DB::table('trprogram')
            ->select(DB::raw('YEAR(tanggalmulai) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');

        return view('home', compact('programs', 'provinsis', 'years', 'googleMapsApiKey'));
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

    /* updated method to Add filter parameters (provinsi_id, program_id, tahun) to the method
       Users can analyze data more deeply by combining filters, which is valuable for a dashboard’s purpose
    */
    // public function getDesaPerProvinsiChartData(Request $request)
    // {
    //     $query = Meals_Penerima_Manfaat::with('dusun.desa.kecamatan.kabupaten.provinsi')
    //         ->whereNull('deleted_at');

    //     // Apply province filter
    //     if ($request->provinsi_id) {
    //         $query->whereHas('dusun.desa.kecamatan.kabupaten.provinsi', function ($q) use ($request) {
    //             $q->where('id', $request->provinsi_id);
    //         });
    //     }

    //     // Apply program filter
    //     if ($request->program_id) {
    //         $query->where('program_id', $request->program_id);
    //     }

    //     // Apply year filter
    //     if ($request->tahun) {
    //         $query->whereHas('program', function ($q) use ($request) {
    //             $q->whereYear('tanggalmulai', '<=', $request->tahun)
    //                 ->whereYear('tanggalselesai', '>=', $request->tahun);
    //         });
    //     }

    //     $data = $query->get()
    //         ->groupBy(function ($item) {
    //             return optional($item->dusun?->desa?->kecamatan?->kabupaten?->provinsi)->nama;
    //         })
    //         ->map(function ($group) {
    //             $uniqueDesa = $group->pluck('dusun.desa.id')->unique()->count();
    //             return [
    //                 'total_desa' => $uniqueDesa,
    //             ];
    //         })
    //         ->filter(fn($v, $k) => !is_null($k))
    //         ->sortByDesc('total_desa')
    //         ->map(function ($item, $provinsi) {
    //             return [
    //                 'provinsi'    => $provinsi,
    //                 'total_desa'  => $item['total_desa'],
    //             ];
    //         })
    //         ->values();

    //     return response()->json($data);
    // }
    // //
    // //
    // //

    // public function getFilteredProvinsi(Request $request, $id = null)
    // {
    //     // Fetch province data
    //     $query = Provinsi::query();
    //     if ($id) {
    //         $query->where('id', $id);
    //     }
    //     $provinsiList = $query->select('id', 'nama', 'latitude', 'longitude')->get();

    //     // Build the stats query
    //     $statsQuery = Meals_Penerima_Manfaat::query()
    //         ->whereNull('trmeals_penerima_manfaat.deleted_at')
    //         ->join('dusun', 'trmeals_penerima_manfaat.dusun_id', '=', 'dusun.id')
    //         ->join('kelurahan', 'dusun.desa_id', '=', 'kelurahan.id')
    //         ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
    //         ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
    //         ->join('provinsi', 'kabupaten.provinsi_id', '=', 'provinsi.id');

    //     // Apply program filter
    //     if ($request->program_id) {
    //         $statsQuery->where('trmeals_penerima_manfaat.program_id', $request->program_id);
    //     }

    //     // Apply year filter
    //     if ($request->tahun) {
    //         $statsQuery->whereHas('program', function ($q) use ($request) {
    //             $q->whereYear('tanggalmulai', '<=', $request->tahun)
    //                 ->whereYear('tanggalselesai', '>=', $request->tahun);
    //         });
    //     }

    //     $stats = $statsQuery
    //         ->select(
    //             'provinsi.id as provinsi_id',
    //             DB::raw('COUNT(DISTINCT kelurahan.id) as total_desa'),
    //             DB::raw('COUNT(trmeals_penerima_manfaat.id) as total_penerima')
    //         )
    //         ->groupBy('provinsi.id')
    //         ->get()
    //         ->keyBy('provinsi_id');

    //     // Attach stats to province list
    //     $provinsiList->each(function ($provinsi) use ($stats) {
    //         $stat = $stats->get($provinsi->id);
    //         $provinsi->total_desa = $stat ? (int) $stat->total_desa : 0;
    //         $provinsi->total_penerima = $stat ? (int) $stat->total_penerima : 0;
    //     });

    //     return response()->json($provinsiList);
    // }

    public function getDesaPerProvinsiChartData(Request $request)
    {
        $query = Meals_Penerima_Manfaat::with('dusun.desa.kecamatan.kabupaten.provinsi')
            ->whereNull('deleted_at');

        // Apply province filter
        if ($request->provinsi_id) {
            $query->whereHas('dusun.desa.kecamatan.kabupaten.provinsi', function ($q) use ($request) {
                $q->where('id', $request->provinsi_id);
            });
        }

        // Apply program filter
        if ($request->program_id) {
            $query->where('program_id', $request->program_id);
        }

        // Apply year filter
        if ($request->tahun) {
            $query->whereHas('program', function ($q) use ($request) {
                $q->whereYear('tanggalmulai', '<=', $request->tahun)
                    ->whereYear('tanggalselesai', '>=', $request->tahun);
            });
        }

        $data = $query->get()
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
            ->values();

        return response()->json($data);
    }

    public function getFilteredProvinsi(Request $request, $id = null)
    {
        // Fetch province data
        $query = Provinsi::query();
        if ($id) {
            // If an ID is provided, fetch only that province (for specific filtering)
            $query->where('id', $id);
        }
        // Select necessary columns (id, nama, latitude, longitude)
        $provinsiList = $query->select('id', 'nama', 'latitude', 'longitude')->get();

        // Build the stats query for beneficiaries and desa, joining through geographic tables
        $statsQuery = Meals_Penerima_Manfaat::query()
            ->whereNull('trmeals_penerima_manfaat.deleted_at')
            ->join('dusun', 'trmeals_penerima_manfaat.dusun_id', '=', 'dusun.id')
            ->join('kelurahan', 'dusun.desa_id', '=', 'kelurahan.id')
            ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
            ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
            ->join('provinsi', 'kabupaten.provinsi_id', '=', 'provinsi.id');

        // Apply program filter
        if ($request->program_id) {
            $statsQuery->where('trmeals_penerima_manfaat.program_id', $request->program_id);
        }

        // Apply year filter
        if ($request->tahun) {
            $statsQuery->whereHas('program', function ($q) use ($request) {
                $q->whereYear('tanggalmulai', '<=', $request->tahun)
                    ->whereYear('tanggalselesai', '>=', $request->tahun);
            });
        }

        $stats = $statsQuery
            ->select(
                'provinsi.id as provinsi_id',
                // Count distinct desa (kelurahan) IDs where beneficiaries exist
                DB::raw('COUNT(DISTINCT kelurahan.id) as total_desa'),
                // Count total beneficiaries
                DB::raw('COUNT(trmeals_penerima_manfaat.id) as total_penerima')
            )
            ->groupBy('provinsi.id')
            ->get()
            ->keyBy('provinsi_id'); // Key by provinsi_id for easy lookup

        // Attach stats to the province list
        $provinsiList->each(function ($provinsi) use ($stats) {
            $stat = $stats->get($provinsi->id);
            // Assign 0 if no stats found for a province, otherwise cast to int
            $provinsi->total_desa = $stat ? (int) $stat->total_desa : 0;
            $provinsi->total_penerima = $stat ? (int) $stat->total_penerima : 0;
        });

        return response()->json($provinsiList);
    }

    // this query is too slow
    // public function getFilteredProvinsi(Request $request, $id = null)
    // {
    //     $programId = $request->program_id;
    //     $tahun = $request->tahun;

    //     // Build cache key based on filters
    //     $cacheKey = "provinsi_stats_{$id}_{$programId}_{$tahun}";

    //     $provinsiList = Provinsi::withCount([
    //         'desa as total_desa' => function ($q) use ($request) {
    //             if ($request->program_id) {
    //                 $q->whereHas('penerimaManfaat', function ($qq) use ($request) {
    //                     $qq->where('program_id', $request->program_id);
    //                 });
    //             }
    //         },
    //         'penerimaManfaat as total_penerima' => function ($q) use ($request) {
    //             if ($request->program_id) {
    //                 $q->where('program_id', $request->program_id);
    //             }

    //             if ($request->tahun) {
    //                 $q->whereHas('program', function ($qq) use ($request) {
    //                     $qq->whereYear('tanggalmulai', '<=', $request->tahun)
    //                         ->whereYear('tanggalselesai', '>=', $request->tahun);
    //                 });
    //             }
    //         }
    //     ])->get(); // tanpa ->get([...])

    //     return response()->json(
    //         $provinsiList->map(function ($prov) {
    //             return [
    //                 'id' => $prov->id,
    //                 'nama' => $prov->nama,
    //                 'latitude' => $prov->latitude,
    //                 'longitude' => $prov->longitude,
    //                 'total_desa' => (int) $prov->total_desa,
    //                 'total_penerima' => (int) $prov->total_penerima,
    //             ];
    //         })->values()
    //     );

    // }

}
