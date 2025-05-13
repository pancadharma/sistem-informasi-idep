<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan_Lokasi;
use App\Models\Meals_Penerima_Manfaat;
use App\Models\Program;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardProvinsiController extends Controller
{
    public function getDashboardDataProvinsi()
    {
        $programs = Program::all();
        $provinsis = Provinsi::all();

        $years = DB::table('trprogram')
            ->select(DB::raw('YEAR(tanggalmulai) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
            
        return view('dashboard.provinsi-data', compact('programs', 'provinsis', 'years'));
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

    public function provinsiDetail($id)
    {
        $provinsi = Provinsi::with('kabupaten.kecamatan.desa.dusun')->findOrFail($id);
        return view('dashboard.provinsi-data', compact('provinsi'));
    }

    public function getKegiatanMarkers(Request $request, $provinsi_id)
    {
        // if (!$request->ajax() && !$request->isJson()) {
        //     return "Not an Ajax Request & JSON REQUEST";
        // }

        // $provinsi_id = $request->provinsi_id;
        // if (!$provinsi_id) {
        //     return response()->json(['error' => 'Provinsi ID is required'], 400);
        // }

        // Validasi provinsi_id
        $provinsi = Provinsi::find($provinsi_id);
        if (!$provinsi) {
            return response()->json(['error' => 'Provinsi not found'], 404);
        }
        // Ambil data lokasi kegiatan berdasarkan provinsi_id
        $markers = Kegiatan_Lokasi::with([
            'kegiatan.activity', 
            'desa.kecamatan',
            'desa.kecamatan.kabupaten',
            'desa.kecamatan.kabupaten.provinsi',
            'kegiatan.activity.program_outcome_output.program_outcome.program',
            ])
            ->whereHas('desa.kecamatan.kabupaten.provinsi', function ($q) use ($provinsi_id) {
                $q->where('id', $provinsi_id);
            })
            ->whereNotNull('lat')
            ->whereNotNull('long')
            ->get()
            ->map(function ($lokasi) {
                // dd($lokasi);
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
        

        // $markers = Kegiatan_Lokasi::with([
        //     'kegiatan.activity', 
        //     'desa',
        //     'desa.kecamatan',
        //     ])
        //     ->select('trkegiatan_lokasi.id', 'trkegiatan_lokasi.lat', 'trkegiatan_lokasi.long', 'trkegiatan_lokasi.lokasi', 'trkegiatan_lokasi.desa_id', 'trkegiatan_lokasi.kegiatan_id')
        //     ->whereHas('desa.kecamatan.kabupaten.provinsi', function ($q) use ($provinsi_id) {
        //         $q->where('id', $provinsi_id);
        //     })

        //     ->whereNotNull('lat')
        //     ->whereNotNull('long')
        //     ->get()

        //     ->map(function ($lokasi) {
        //         return [
        //             'kegiatan_id'       => $lokasi->kegiatan_id ?? null,
        //             'nama_kegiatan'     => $lokasi->activity->nama ?? 'Kegiatan',
        //             'desa'              => $lokasi->desa->nama ?? 'Kegiatan',
        //             'lokasi'            => $lokasi->lokasi ?? 'Kegiatan',
        //             'lat'               => (float) $lokasi->lat,
        //             'long'              => (float) $lokasi->long,
        //         ];
        //     });

        return response()->json($markers);
    }

}
