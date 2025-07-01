<?php

namespace App\Http\Controllers;

use App\Models\Kelurahan;
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

        if ($id) {
            // If an ID is provided, fetch only that province (for specific filtering)
            $provinsiList = Provinsi::where('id', $id)->select('id', 'nama', 'latitude', 'longitude')->get();
            $statsQuery->where('provinsi.id', $id);
        } else {
            // Initial load: only get provinces that have beneficiaries matching the filters
            $provinceIdsWithStats = (clone $statsQuery)->distinct('provinsi.id')->pluck('provinsi.id');
            $provinsiList = Provinsi::whereIn('id', $provinceIdsWithStats)->select('id', 'nama', 'latitude', 'longitude')->get();
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

    public function getCombinedDesaMapData(Request $request, $provinsi_id = null)
    {
        $programId = $request->input('program_id');
        $tahun = $request->input('tahun');

        // Subquery for trkegiatan_lokasi coordinates
        $lokasiSubquery = DB::table('trkegiatan_lokasi')
            ->select(
                'desa_id',
                DB::raw('
                CASE 
                    WHEN COUNT(id) = 1 THEN MAX(`lat`)
                    WHEN COUNT(id) > 1 THEN AVG(`lat`)
                    ELSE NULL
                END as lokasi_lat
            '),
                DB::raw('
                CASE 
                    WHEN COUNT(id) = 1 THEN MAX(`long`)
                    WHEN COUNT(id) > 1 THEN AVG(`long`)
                    ELSE NULL
                END as lokasi_long
            '),
                DB::raw('
                CASE 
                    WHEN COUNT(id) = 1 THEN "exact"
                    WHEN COUNT(id) > 1 THEN "averaged"
                    ELSE NULL
                END as lokasi_source
            ')
            )
            ->groupBy('desa_id');

        // Subquery for dusun coordinates
        $dusunSubquery = DB::table('dusun')
            ->select(
                'desa_id',
                DB::raw('AVG(latitude) as dusun_lat'),
                DB::raw('AVG(longitude) as dusun_long')
            )
            ->groupBy('desa_id');

        $query = Kelurahan::select(
            'kelurahan.id',
            'kelurahan.nama as desa_name',
            'kecamatan.nama as kecamatan_name',
            'kabupaten.nama as kabupaten_name',
            DB::raw('
            COALESCE(
                lokasi.lokasi_lat,
                dusun.latitude,
                kabupaten.latitude
            ) as latitude
        '),
            DB::raw('
            COALESCE(
                lokasi.lokasi_long,
                dusun.longitude,
                kabupaten.longitude
            ) as longitude
        '),
            DB::raw('
            CASE 
                WHEN lokasi.lokasi_lat IS NOT NULL THEN lokasi.lokasi_source
                WHEN dusun.latitude IS NOT NULL THEN "dusun"
                ELSE "kabupaten"
            END as coordinate_source
        '),
            DB::raw('COUNT(DISTINCT tpm.id) as total_beneficiaries_in_desa'),
            DB::raw('kabupaten.latitude as kabupaten_latitude'),
            DB::raw('kabupaten.longitude as kabupaten_longitude')
        )
            ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
            ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
            ->leftJoin('dusun', 'kelurahan.id', '=', 'dusun.desa_id')
            ->leftJoin('trmeals_penerima_manfaat as tpm', 'dusun.id', '=', 'tpm.dusun_id')
            ->leftJoin('trprogram as tp', 'tpm.program_id', '=', 'tp.id')
            ->leftJoinSub($lokasiSubquery, 'lokasi', function ($join) {
                $join->on('kelurahan.id', '=', 'lokasi.desa_id');
            })
            ->leftJoinSub($dusunSubquery, 'dusun_coords', function ($join) {
                $join->on('kelurahan.id', '=', 'dusun_coords.desa_id');
            })
            ->whereNotNull('dusun.nama')
            ->whereNotNull('tpm.nama');

        if ($provinsi_id) {
            $query->where('kabupaten.provinsi_id', $provinsi_id);
        }

        if ($programId) {
            $query->where('tpm.program_id', $programId);
        }

        if ($tahun) {
            $query->whereYear('tp.tanggalmulai', '<=', $tahun)
                ->whereYear('tp.tanggalselesai', '>=', $tahun);
        }

        $query->groupBy(
            'kelurahan.id',
            'kelurahan.nama',
            'kecamatan.nama',
            'kabupaten.nama',
            'kabupaten.latitude',
            'kabupaten.longitude',
            'lokasi.lokasi_lat',
            'lokasi.lokasi_long',
            'lokasi.lokasi_source',
            'dusun.latitude',
            'dusun.longitude'
        )
            ->having('total_beneficiaries_in_desa', '>', 0);

        $desas = $query->get();

        $desas = $desas->map(function ($desa) {
            $desa->type = 'desa';
            $desa->total_beneficiaries_in_desa = (int) $desa->total_beneficiaries_in_desa;
            $desa->latitude = (float) $desa->latitude;
            $desa->longitude = (float) $desa->longitude;
            $desa->kabupaten_latitude = (float) $desa->kabupaten_latitude;
            $desa->kabupaten_longitude = (float) $desa->kabupaten_longitude;
            return $desa;
        });

        return response()->json($desas);
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
