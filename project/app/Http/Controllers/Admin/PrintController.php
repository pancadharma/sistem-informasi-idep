<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meals_Penerima_Manfaat as MealsPM;
use App\Models\Program;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintController extends Controller
{
    public function printDashboard(Request $request)
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

        $uniqueFamilies = $data->map(function ($item) {
            return $item->is_head_family ? trim(strtolower($item->nama)) : trim(strtolower($item->head_family_name));
        })->filter()
            ->unique()
            ->count();

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
                'laki'             => $items->where('jenis_kelamin', 'laki')->count(),
                'perempuan'        => $items->where('jenis_kelamin', 'perempuan')->count(),
                'anak_laki'        => $items->where('jenis_kelamin', 'laki')->where('umur', '<', 17)->count(),
                'anak_perempuan'   => $items->where('jenis_kelamin', 'perempuan')->where('umur', '<', 17)->count(),
                'disabilitas'      => $items->filter(fn($item) => $item->kelompokMarjinal->contains('id', 3))->count(),
            ];
        })->values();

        $dashboardData = [
            'semua'           => $data->count(),
            'laki'            => $data->where('jenis_kelamin', 'laki')->count(),
            'perempuan'       => $data->where('jenis_kelamin', 'perempuan')->count(),
            'anak_laki'       => $data->where('jenis_kelamin', 'laki')->where('umur', '<', 17)->count(),
            'anak_perempuan'  => $data->where('jenis_kelamin', 'perempuan')->where('umur', '<', 17)->count(),
            'disabilitas'     => $data->filter(fn($item) => $item->kelompokMarjinal->contains('id', 3))->count(),
            'keluarga'        => $uniqueFamilies,
        ];

        return view('print.dashboard', compact('dashboardData', 'grouped', 'request'));
    }
}
