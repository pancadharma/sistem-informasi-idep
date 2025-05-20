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

    // public function getDataDesa(Request $request, $id = null)
    // {
    //     // $provinsi_id = $request->input('provinsi') ?? $id;
    //     // $provinsi = Provinsi::find($id);

    //     // if (!$provinsi) {
    //     //     return response()->json([
    //     //         'error' => 'Selected Provinsi Not Found',
    //     //         'message' => 'Provinsi with ID ' . $provinsi_id . ' Does Not Exist',
    //     //     ], 404);
    //     // }
    //     // Ambil data lokasi kegiatan berdasarkan provinsi_id
    //     $lokasiRecords = Kegiatan_Lokasi::with([
    //         'kegiatan.activity',
    //         'desa.kecamatan',
    //         'desa.kecamatan.kabupaten',
    //         'desa.kecamatan.kabupaten.provinsi',
    //         'kegiatan.activity.program_outcome_output.program_outcome.program',
    //         'penerimaManfaat',
    //     ])->whereHas('desa.kecamatan.kabupaten.provinsi', function ($q) use ($id) {
    //         $q->where('id', $id);
    //     })
    //         ->whereNotNull('lat')
    //         ->whereNotNull('long')
    //         ->get();

    //     // Group the records by a composite key (kegiatan_id, lat, long)
    //     $groupedMarkers = $lokasiRecords->groupBy(function ($item) {
    //         return $item->kegiatan_id . '_' . $item->lat . '_' . $item->long;
    //     });

    //     // Map the grouped data into the desired output format
    //     $markers = $groupedMarkers->map(function ($group) {
    //         // $group is a collection of Kegiatan_Lokasi records with the same kegiatan_id, lat, and long
    //         $first = $group->first(); // Take the first item to get common details

    //         return [
    //             'kegiatan_id'   => $first->kegiatan_id ?? null,
    //             'nama_kegiatan' => $first->kegiatan->activity->nama ?? null,
    //             'kode_kegiatan' => $first->kegiatan->activity->kode ?? null,
    //             'kode_program'  => $first->kegiatan->activity->program_outcome_output->program_outcome->program->kode ?? null,
    //             'program'       => $first->kegiatan->activity->program_outcome_output->program_outcome->program->nama ?? null,
    //             'lat'           => $first->lat,
    //             'long'          => $first->long,
    //             // Aggregate 'lokasi' - list all unique lokasi names in the group
    //             'lokasi'        => $group->pluck('lokasi')->unique()->filter()->implode(', '),
    //             'desa'          => $first->desa->nama, // Assuming desa is the same for all in the group
    //             'kecamatan'     => $first->desa->kecamatan->nama,
    //             'kabupaten'     => $first->desa->kecamatan->kabupaten->nama,
    //             'provinsi'      => $first->desa->kecamatan->kabupaten->provinsi->nama,
    //             // Sum 'total_penerima' for all records in the group
    //             'total_penerima'   => $group->sum(function ($item) {
    //                 return $item->penerimaManfaat->count();
    //             }),
    //         ];
    //     })->values(); // Reset keys after mapping
    //     return response()->json($markers);
    // }


    // public function getFilteredDataDesa(Request $request, $id = null)
    // {
    //     $query = Provinsi::query();
    //     if ($id) {
    //         $query->where('id', $id);
    //     }
    //     $provinsiList = $query->select('id', 'nama', 'latitude', 'longitude')->get();


    //     $query = MealsPM::query()
    //         ->with(['dusun.desa.kecamatan.kabupaten.provinsi', 'program', 'kelompokMarjinal'])
    //         ->when($request->program_id, function ($query, $program_id) {
    //             $query->where('program_id', $program_id);
    //         })
    //         ->when($request->tahun, function ($query, $tahun) {
    //             $query->whereHas('program', function ($q) use ($tahun) {
    //                 $q->where('tahun', $tahun);
    //             });
    //         })
    //         ->when($request->provinsi_id, function ($query, $provinsi_id) {
    //             $query->whereHas('dusun.desa.kecamatan.kabupaten.provinsi', function ($q) use ($provinsi_id) {
    //                 $q->where('id', $provinsi_id);
    //             });
    //         })
    //         ->when($request->kabupaten_id, function ($query, $kabupaten_id) {
    //             $query->whereHas('dusun.desa.kecamatan.kabupaten', function ($q) use ($kabupaten_id) {
    //                 $q->where('id', $kabupaten_id);
    //             });
    //         })
    //         ->when($request->kecamatan_id, function ($query, $kecamatan_id) {
    //             $query->whereHas('dusun.desa.kecamatan', function ($q) use ($kecamatan_id) {
    //                 $q->where('id', $kecamatan_id);
    //             });
    //         })
    //         ->when($request->desa_id, function ($query, $desa_id) {
    //             $query->whereHas('dusun.desa', function ($q) use ($desa_id) {
    //                 $q->where('id', $desa_id);
    //             });
    //         })
    //         ->when($request->dusun_id, function ($query, $dusun_id) {
    //             $query->where('dusun_id', $dusun_id);
    //         })
    //         ->when($request->program_id, function ($query, $program_id) {
    //             $query->where('program_id', $program_id);
    //         })
    //         ->when($request->tahun, function ($query, $tahun) {
    //             $query->whereHas('program', function ($q) use ($tahun) {
    //                 $q->whereYear('tanggalmulai', '<=', $tahun)
    //                     ->whereYear('tanggalselesai', '>=', $tahun);
    //             });
    //         })
    //         ->whereNull('deleted_at')
    //         ->get();

    //     return response()->json([
    //         'data' => $query->map(function ($item) {
    //             return [
    //                 'id'                => $item->id,
    //                 'nama'              => $item->nama,
    //                 'dusun_id'          => $item->dusun_id,
    //                 'dusun'             => $item->dusun->nama,
    //                 'desa'              => $item->dusun->desa->nama,
    //                 'kecamatan'         => $item->dusun->desa->kecamatan->nama,
    //                 'kabupaten'         => $item->dusun->desa->kecamatan->kabupaten->nama,
    //                 'provinsi'          => $item->dusun->desa->kecamatan->kabupaten->provinsi->nama,
    //                 'program'           => $item->program ? $item->program->nama : null,
    //                 'kelompokMarjinal'  => $item->kelompokMarjinal ? $item->kelompokMarjinal : null,
    //             ];
    //         }),
    //         'total' => $query->count(),
    //         'laki'  => $query->where('jenis_kelamin', 'laki')->count(),
    //         'perempuan' => $query->where('jenis_kelamin', 'perempuan')->count(),
    //         'anak_laki' => $query->where('jenis_kelamin', 'laki')->where('umur', '<', 17)->count(),
    //         'anak_perempuan' => $query->where('jenis_kelamin', 'perempuan')->where('umur', '<', 17)->count(),
    //         'disabilitas' => $query->filter(fn($item) => $item->kelompokMarjinal->contains('id', 3))->count(),
    //         'keluarga' => $query->pluck('nama_kepala_keluarga')->unique()->count(),
    //         'program' => $query->pluck('program.nama')->unique()->count(),
    //         'dusun' => $query->pluck('dusun.nama')->unique()->count(),
    //         'desa' => $query->pluck('dusun.desa.nama')->unique()->count(),
    //         'kecamatan' => $query->pluck('dusun.desa.kecamatan.nama')->unique()->count(),
    //         'kabupaten' => $query->pluck('dusun.desa.kecamatan.kabupaten.nama')->unique()->count(),
    //         'provinsi' => $query->pluck('dusun.desa.kecamatan.kabupaten.provinsi.nama')->unique()->count(),
    //     ]);
    //     // Hitung keluarga unik (berdasarkan nama kepala keluarga atau nama sendiri jika dia kepala keluarga)
    //     $uniqueKeluarga = $query->pluck('nama_kepala_keluarga')->unique()->count();
    //     return response()->json([
    //         'semua'           => $query->count(),
    //         'laki'            => $query->where('jenis_kelamin', 'laki')->count(),
    //         'perempuan'       => $query->where('jenis_kelamin', 'perempuan')->count(),
    //         'anak_laki'       => $query->where('jenis_kelamin', 'laki')->where('umur', '<', 17)->count(),
    //         'anak_perempuan'  => $query->where('jenis_kelamin', 'perempuan')->where('umur', '<', 17)->count(),
    //         'disabilitas'     => $query->filter(fn($item) => $item->kelompokMarjinal->contains('id', 3))->count(),
    //         'keluarga'        => $uniqueKeluarga,
    //     ]);
    // }


    // public function getFilteredDataDesa(Request $request, $id = null)
    // {
    //     // Gunakan $id dari URL sebagai filter provinsi jika tersedia
    //     if ($id) {
    //         $request->merge(['provinsi_id' => $id]);
    //     }

    //     // Ambil data Penerima Manfaat terfilter
    //     $data = MealsPM::query()
    //         ->with([
    //             'dusun.desa.kecamatan.kabupaten.provinsi',
    //             'program',
    //             'kelompokMarjinal'
    //         ])
    //         ->when($request->program_id, function ($query, $program_id) {
    //             $query->where('program_id', $program_id);
    //         })
    //         ->when($request->tahun, function ($query, $tahun) {
    //             $query->whereHas('program', function ($q) use ($tahun) {
    //                 $q->whereYear('tanggalmulai', '<=', $tahun)
    //                     ->whereYear('tanggalselesai', '>=', $tahun);
    //             });
    //         })
    //         ->when($request->provinsi_id, function ($query, $provinsi_id) {
    //             $query->whereHas('dusun.desa.kecamatan.kabupaten.provinsi', function ($q) use ($provinsi_id) {
    //             $q->where('id', $provinsi_id);
    //             });
    //         })
    //         ->when($request->kabupaten_id, function ($query, $kabupaten_id) {
    //             $query->whereHas('dusun.desa.kecamatan.kabupaten', function ($q) use ($kabupaten_id) {
    //                 $q->where('id', $kabupaten_id);
    //             });
    //         })
    //         ->when($request->kecamatan_id, function ($query, $kecamatan_id) {
    //             $query->whereHas('dusun.desa.kecamatan', function ($q) use ($kecamatan_id) {
    //                 $q->where('id', $kecamatan_id);
    //             });
    //         })
    //         ->when($request->desa_id, function ($query, $desa_id) {
    //             $query->whereHas('dusun.desa', function ($q) use ($desa_id) {
    //                 $q->where('id', $desa_id);
    //             });
    //         })
    //         ->when($request->dusun_id, function ($query, $dusun_id) {
    //             $query->where('dusun_id', $dusun_id);
    //     })
    //         ->whereNull('deleted_at')
    //         ->get();

    //     // Persiapkan response data utama
    //     $responseData = $data->map(function ($item) {
    //         return [
    //             'id'               => $item->id,
    //             'nama'             => $item->nama,
    //             'dusun_id'         => $item->dusun_id,
    //             'dusun'            => $item->dusun->nama ?? '-',
    //             'desa'             => $item->dusun->desa->nama ?? '-',
    //             'kecamatan'        => $item->dusun->desa->kecamatan->nama ?? '-',
    //             'kabupaten'        => $item->dusun->desa->kecamatan->kabupaten->nama ?? '-',
    //             'provinsi'         => $item->dusun->desa->kecamatan->kabupaten->provinsi->nama ?? '-',
    //             'program'          => $item->program->nama ?? '-',
    //             'kelompokMarjinal' => $item->kelompokMarjinal ?? [],
    //         ];
    //     });

    //     // Statistik
    //     $total = $data->count();
    //     $laki = $data->where('jenis_kelamin', 'laki')->count();
    //     $perempuan = $data->where('jenis_kelamin', 'perempuan')->count();
    //     $anak_laki = $data->where('jenis_kelamin', 'laki')->where('umur', '<', 17)->count();
    //     $anak_perempuan = $data->where('jenis_kelamin', 'perempuan')->where('umur', '<', 17)->count();
    //     $disabilitas = $data->filter(fn($item) => $item->kelompokMarjinal->contains('id', 3))->count();
    //     $keluarga = $data->pluck('nama_kepala_keluarga')->filter()->unique()->count();

    //     // Ringkasan wilayah dan program
    //     $summary = [
    //         'program'   => $data->pluck('program.nama')->filter()->unique()->count(),
    //         'dusun'     => $data->pluck('dusun.nama')->filter()->unique()->count(),
    //         'desa'      => $data->pluck('dusun.desa.nama')->filter()->unique()->count(),
    //         'kecamatan' => $data->pluck('dusun.desa.kecamatan.nama')->filter()->unique()->count(),
    //         'kabupaten' => $data->pluck('dusun.desa.kecamatan.kabupaten.nama')->filter()->unique()->count(),
    //         'provinsi'  => $data->pluck('dusun.desa.kecamatan.kabupaten.provinsi.nama')->filter()->unique()->count(),
    //     ];

    //     return response()->json([
    //         'data'             => $responseData,
    //         'total'            => $total,
    //         'laki'             => $laki,
    //         'perempuan'        => $perempuan,
    //         'anak_laki'        => $anak_laki,
    //         'anak_perempuan'   => $anak_perempuan,
    //         'disabilitas'      => $disabilitas,
    //         'keluarga'         => $keluarga,
    //         'summary'          => $summary,
    //         'filters'          => [
    //             'program_id'   => $request->program_id,
    //             'tahun'        => $request->tahun,
    //             'provinsi_id'  => $request->provinsi_id,
    //         ],
    //         'success'          => true,
    //         'message'          => 'Data berhasil diambil',
    //     ]);
    // }


    public function getFilteredDataDesa(Request $request, $id = null)
    {
        if ($id) {
            $request->merge(['provinsi_id' => $id]);
        }

        $data = MealsPM::query()
            ->with([
                'dusun.desa.kecamatan.kabupaten.provinsi',
                'kelompokMarjinal'
            ])
            ->when($request->program_id, function ($query, $program_id) {
                $query->where('program_id', $program_id);
            })
            ->when($request->tahun, function ($query, $tahun) {
                $query->whereHas('program', function ($q) use ($tahun) {
                    $q->whereYear('tanggalmulai', '<=', $tahun)
                        ->whereYear('tanggalselesai', '>=', $tahun);
                });
            })
            ->when($request->provinsi_id, function ($query, $provinsi_id) {
                $query->whereHas('dusun.desa.kecamatan.kabupaten.provinsi', function ($q) use ($provinsi_id) {
                $q->where('id', $provinsi_id);
            });
        })
            ->whereNull('deleted_at')
            ->get();

        // Kelompokkan berdasarkan dusun
        $grouped = $data->groupBy('dusun_id')->map(function ($items, $dusun_id) {
            $first = $items->first();

            return [
                'nama_dusun'       => $first->dusun->nama ?? '-',
                'desa'             => $first->dusun->desa->nama ?? '-',
                'kecamatan'        => $first->dusun->desa->kecamatan->nama ?? '-',
                'kabupaten'        => $first->dusun->desa->kecamatan->kabupaten->nama ?? '-',
                'provinsi'         => $first->dusun->desa->kecamatan->kabupaten->provinsi->nama ?? '-',
                'program'          => $first->program->nama ?? '-',
                'kegiatan'         => $first->program->activity->nama ?? '-',
                'total_penerima'   => $items->count(),

                // Statistik tambahan:
                'laki'             => $items->where('jenis_kelamin', 'laki')->count(),
                'perempuan'        => $items->where('jenis_kelamin', 'perempuan')->count(),
                'anak_laki'        => $items->where('jenis_kelamin', 'laki')->where('umur', '<', 17)->count(),
                'anak_perempuan'   => $items->where('jenis_kelamin', 'perempuan')->where('umur', '<', 17)->count(),
                'disabilitas'      => $items->filter(fn($item) => $item->kelompokMarjinal->contains('id', 3))->count(),
            ];
        })->values(); // untuk reset index agar berbentuk array biasa

        return response()->json([
            'data' => $grouped,
            'total_dusun' => $grouped->count(),
            'success' => true,
            'message' => 'Data penerima manfaat per dusun berhasil diambil'
        ]);

        // return response()->json([
        //     'data' => $grouped->values(), // <= pastikan ini array numerik
        // ]);
    }






    // EMD DEV




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

    // public function getDesaTableData(Request $req)
    // {
    //     $provId   = $req->provinsi_id;
    //     $progId   = $req->program_id;
    //     $tahun    = $req->tahun;

    //     $q = DB::table('trmeals_penerima_manfaat as pm')
    //         ->join('dusun',    'pm.dusun_id',        '=', 'dusun.id')
    //         ->join('kelurahan',     'dusun.desa_id',      '=', 'kelurahan.id')
    //         ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
    //         ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
    //         ->join('provinsi', 'kabupaten.provinsi_id', '=', 'provinsi.id')
    //         ->when($provId, function ($q) use ($provId) {
    //             $q->where('provinsi.id', $provId);
    //         })
    //         ->when($progId, function ($q) use ($progId) {
    //             $q->where('pm.program_id', $progId);
    //         })
    //         ->when($tahun, function ($q) use ($tahun) {
    //             $q->whereYear('pm.created_at', $tahun);
    //         })
    //         ->select('kelurahan.id', 'kelurahan.nama as kelurahan', 'kabupaten.nama as kabupaten')
    //         ->selectRaw('COUNT(DISTINCT pm.id) as penerima')
    //         ->groupBy('kelurahan.id', 'kelurahan.nama', 'kabupaten.nama')
    //         ->orderByDesc('penerima');

    //     return datatables()->of($q)->toJson();
    // }

    // /**
    //  * Data untuk Pie Chart: total desa per kabupaten
    //  */
    // public function getKabupatenPieData(Request $req)
    // {
    //     $provId   = $req->provinsi_id;
    //     $progId   = $req->program_id;
    //     $tahun    = $req->tahun;

    //     $data = DB::table('trmeals_penerima_manfaat as pm')
    //         ->join('dusun',    'pm.dusun_id',        '=', 'dusun.id')
    //         ->join('kelurahan',     'dusun.desa_id',      '=', 'kelurahan.id')
    //         ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
    //         ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
    //         ->join('provinsi', 'kabupaten.provinsi_id', '=', 'provinsi.id')
    //         ->when($provId, function ($q) use ($provId) {
    //             $q->where('provinsi.id', $provId);
    //         })
    //         ->when($progId, function ($q) use ($progId) {
    //             $q->where('pm.program_id', $progId);
    //         })
    //         ->when($tahun, function ($q) use ($tahun) {
    //             $q->whereYear('pm.created_at', $tahun);
    //         })
    //         ->select('kabupaten.nama as kabupaten')
    //         ->selectRaw('COUNT(DISTINCT kelurahan.id) as total_desa')
    //         ->groupBy('kabupaten.nama')
    //         ->orderByDesc('total_desa')
    //         ->get();

    //     return response()->json($data);
    // }


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

        // return DataTable::of($q->toJson())
        //     ->addIndexColumn()
        //     ->addColumn('kelurahan', function ($row) {
        //         return $row->penerima;
        //     })
        //     ->addColumn('kabupaten', function ($row) {
        //         return $row->kelurahan;
        //     })
        //     ->addColumn('penerima', function ($row) {
        //         return $row->kabupaten;
        //     })
        //     ->make(true);
        $datatable = datatables()->of($q)->toJson();
        return $datatable;
    }
}