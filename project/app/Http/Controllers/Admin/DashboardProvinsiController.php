<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan_Lokasi;
use App\Models\Meals_Penerima_Manfaat as MealsPM;
use App\Models\Program;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Contracts\DataTable;

class DashboardProvinsiController extends Controller
{
    public function getDashboardDataProvinsi()
    {
        $programs = Program::all();
        $provinsis = Provinsi::all();
        $selectedProvinsi = Provinsi::find(51);

        $years = DB::table('trprogram')
            ->select(DB::raw('YEAR(tanggalmulai) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('dashboard.provinsi-data', compact('programs', 'provinsis', 'years', 'selectedProvinsi'));
    }

    function getDashboardData(Request $request)
    {
        $data = MealsPM::query()
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


    // DEV
    public function indexProvinsiData(Request $request)
    {
        $programId = $request->input('program');
        $tahun = $request->input('tahun');
        $provinsiId = $request->input('provinsi');

        // Ambil data desa dan total penerima manfaat
        $dataDesa = DB::table('trkegiatan_lokasi as lokasi')
            ->join('trkegiatan as keg', 'lokasi.kegiatan_id', '=', 'keg.id')
            ->join('trprogramoutcomeoutputactivity as poa', 'keg.programoutcomeoutputactivity_id', '=', 'poa.id')
            ->join('trprogram as prog', 'poa.program_id', '=', 'prog.id')
            ->join('kelurahan as desa', 'lokasi.desa_id', '=', 'desa.id')
            ->leftJoin('dusun', 'desa.id', '=', 'dusun.desa_id')
            ->leftJoin('trmeals_penerima_manfaat as penerima', 'dusun.id', '=', 'penerima.dusun_id')
            ->where('prog.id', $programId)
            ->whereYear('prog.tanggalmulai', '<=', $tahun)
            ->whereYear('prog.tanggalselesai', '>=', $tahun)
            ->where('desa.provinsi_id', $provinsiId)
            ->selectRaw('desa.nama as nama_desa, lokasi.lokasi as lokasi_kegiatan, COUNT(penerima.id) as total_penerima')
            ->groupBy('desa.id', 'lokasi.lokasi')
            ->get();

        return response()->json($dataDesa);
    }

    public function getFilteredDataDesa(Request $request, $id = null)
    {
        if ($id) {
            $request->merge(['provinsi_id' => $id]);
        }

        $query = MealsPM::with([
            'dusun.desa.kecamatan.kabupaten.provinsi',
            'program',
            'kelompokMarjinal'
        ])->whereNull('deleted_at');

        // Provinsi filter
        if ($request->provinsi_id) {
            $query->whereHas('dusun.desa.kecamatan.kabupaten.provinsi', function ($q) use ($request) {
                $q->where('id', $request->provinsi_id);
            });
        }

        // Program ID filter
        if ($request->program_id) {
            $query->where('program_id', $request->program_id);
        }

        // Tahun filter
        if ($request->tahun) {
            $query->whereHas('program', function ($q) use ($request) {
                $q->whereYear('tanggalmulai', '<=', $request->tahun)
                    ->whereYear('tanggalselesai', '>=', $request->tahun);
            });
        }

        $data = $query->get();

        // Jika data kosong, langsung balikan agar datatable kosong
        if ($data->isEmpty()) {
            return response()->json([
                'data' => [],
                'total_dusun' => 0,
                'success' => true,
                'message' => 'No data matching filter'
            ]);
        }

        // Group by dusun
        $grouped = $data->groupBy('dusun_id')->map(function ($items) {
            $first = $items->first();

            return [
                'nama_dusun'       => $first->dusun->nama ?? '-',
                'desa'             => $first->dusun->desa->nama ?? '-',
                'kecamatan'        => $first->dusun->desa->kecamatan->nama ?? '-',
                'kabupaten'        => $first->dusun->desa->kecamatan->kabupaten->nama ?? '-',
                'provinsi'         => $first->dusun->desa->kecamatan->kabupaten->provinsi->nama ?? '-',
                'program'          => $first->program->nama ?? '-',
                'total_penerima'   => $items->count(),

                // Statistik tambahan:
                'laki'             => $items->where('jenis_kelamin', 'laki')->count(),
                'perempuan'        => $items->where('jenis_kelamin', 'perempuan')->count(),
                'anak_laki'        => $items->where('jenis_kelamin', 'laki')->where('umur', '<', 17)->count(),
                'anak_perempuan'   => $items->where('jenis_kelamin', 'perempuan')->where('umur', '<', 17)->count(),
                'disabilitas'      => $items->filter(fn($item) => $item->kelompokMarjinal->contains('id', 3))->count(),
            ];
        })->values();

        return response()->json([
            'data' => $grouped,
            'total_dusun' => $grouped->count(),
            'success' => true,
            'message' => 'Data berhasil difilter dan dikembalikan'
        ]);
    }

    //method untuk endpoint baru chart by selected provinsi /data/chart/kabupaten/{id?}
    public function getChartByKabupaten(Request $request, $id = null)
    {
        if ($id) $request->merge(['provinsi_id' => $id]);

        $data = MealsPM::with('dusun.desa.kecamatan.kabupaten')
            ->when(
                $request->provinsi_id,
                fn($q, $v) =>
                $q->whereHas('dusun.desa.kecamatan.kabupaten.provinsi', fn($q2) => $q2->where('id', $v))
            )
            ->whereNull('deleted_at')
            ->get();

        $grouped = $data->groupBy(fn($pm) => $pm->dusun->desa->kecamatan->kabupaten->nama ?? 'Lainnya')
            ->map(fn($items) => $items->count());

        return response()->json([
            'labels' => $grouped->keys(),
            'values' => $grouped->values(),
        ]);
    }

    // END DEV

    public function getProgramStatsPerProvinsi(Request $request)
    {
        $data = MealsPM::query()
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

        $programs = Program::withCount(['penerimaManfaat as total' => function ($query) use ($request) {
            $query->when($request->provinsi_id, function ($q) use ($request) {
                $q->whereHas('dusun.desa.kecamatan.kabupaten.provinsi', function ($sub) use ($request) {
                    $sub->where('id', $request->provinsi_id);
                });
            })
                ->when($request->tahun, function ($q) use ($request) {
                    $q->whereHas('program', function ($sub) use ($request) {
                        $sub->whereYear('tanggalmulai', '<=', $request->tahun)
                            ->whereYear('tanggalselesai', '>=', $request->tahun);
                    });
                });
        }])->get();

        return response()->json($data);
    }


    public function provinsiDetail($id)
    {
        $provinsi = Provinsi::with('kabupaten.kecamatan.desa.dusun')->findOrFail($id);
        return view('dashboard.provinsi-data', compact('provinsi'));
    }

    public function getKegiatanMarkers($provinsi_id)
    {
        $provinsi = Provinsi::find($provinsi_id);

        if (!$provinsi) {
            return response()->json([
                'error' => 'Selected Provinsi Not Found',
                'message' => 'Provinsi with ID ' . $provinsi_id . ' Does Not Exist',
            ], 404);
        }
        // Ambil data lokasi kegiatan berdasarkan provinsi_id
        $markers = Kegiatan_Lokasi::with([
            'kegiatan.activity',
            'desa.kecamatan',
            'desa.kecamatan.kabupaten',
            'desa.kecamatan.kabupaten.provinsi',
            'kegiatan.activity.program_outcome_output.program_outcome.program',
        ])->whereHas('desa.kecamatan.kabupaten.provinsi', function ($q) use ($provinsi_id) {
            $q->where('id', $provinsi_id);
        })
            ->whereNotNull('lat')
            ->whereNotNull('long')
            ->get()
            ->map(function ($lokasi) {
                return [
                    'kegiatan_id'   => $lokasi->kegiatan_id ?? null,
                    'nama_kegiatan' => $lokasi->kegiatan->activity->nama ?? null,
                    'kode_kegiatan' => $lokasi->kegiatan->activity->kode ?? null,
                    'kode_program'  => $lokasi->kegiatan->activity->program_outcome_output->program_outcome->program->kode ?? null,
                    'program'       => $lokasi->kegiatan->activity->program_outcome_output->program_outcome->program->nama ?? null,
                    'lat'           => $lokasi->lat,
                    'long'          => $lokasi->long,
                    'lokasi'        => $lokasi->lokasi,
                    'desa'          => $lokasi->desa->nama,
                    'kecamatan'     => $lokasi->desa->kecamatan->nama,
                    'kabupaten'     => $lokasi->desa->kecamatan->kabupaten->nama,
                    'provinsi'      => $lokasi->desa->kecamatan->kabupaten->provinsi->nama,
                ];
            });
        return response()->json($markers);
    }



    /**
     * Data untuk Pie Chart: total desa per kabupaten
     */
    public function getKabupatenPieData(Request $req)
    {
        $provId = $req->provinsi_id;
        $progId = $req->program_id;
        $tahun  = $req->tahun;

        // Optional: Validate input here if needed

        $data = DB::table('trmeals_penerima_manfaat as pm')
            ->join('dusun',    'pm.dusun_id',        '=', 'dusun.id')
            ->join('kelurahan',     'dusun.desa_id',      '=', 'kelurahan.id')
            ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
            ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
            ->join('provinsi', 'kabupaten.provinsi_id', '=', 'provinsi.id')
            ->when($provId, function ($q) use ($provId) {
                $q->where('provinsi.id', $provId);
            })
            ->when($progId, function ($q) use ($progId) {
                $q->where('pm.program_id', $progId);
            })
            ->when($tahun, function ($q) use ($tahun) {
                $q->whereYear('pm.created_at', $tahun);
            })
            ->select('kabupaten.nama as kabupaten')
            ->selectRaw('COUNT(DISTINCT kelurahan.id) as total_desa')
            ->groupBy('kabupaten.nama')
            ->orderByDesc('total_desa')
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No data found for the given filters.',
                'data'    => [],
            ]);
        }

        // Prepare data for charting (labels and values)
        $labels = $data->pluck('kabupaten');
        $values = $data->pluck('total_desa');

        return response()->json([
            'success' => true,
            'total'   => $data->sum('total_desa'),
            'labels'  => $labels,
            'data'    => $values,
            'raw'     => $data,
        ]);
    }

    /**
     * Data untuk Tabel Desa
     */
    public function getDesaTableData(Request $req)
    {
        $provId = $req->provinsi_id;
        $progId = $req->program_id;
        $tahun  = $req->tahun;

        $q = DB::table('trmeals_penerima_manfaat as pm')
            ->join('dusun',    'pm.dusun_id',        '=', 'dusun.id')
            ->join('kelurahan',     'dusun.desa_id',      '=', 'kelurahan.id')
            ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
            ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
            ->join('provinsi', 'kabupaten.provinsi_id', '=', 'provinsi.id')
            ->when($provId, function ($q) use ($provId) {
                $q->where('provinsi.id', $provId);
            })
            ->when($progId, function ($q) use ($progId) {
                $q->where('pm.program_id', $progId);
            })
            ->when($tahun, function ($q) use ($tahun) {
                $q->whereYear('pm.created_at', $tahun);
            })
            ->select('kelurahan.id', 'kelurahan.nama as kelurahan', 'kecamatan.nama as kecamatan', 'kabupaten.nama as kabupaten')
            ->selectRaw('COUNT(DISTINCT pm.id) as penerima')
            ->groupBy('kelurahan.id', 'kelurahan.nama', 'kabupaten.nama')
            ->orderByDesc('penerima');
        $datatable = datatables()->of($q)->toJson();
        return $datatable;
    }
}
