<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meals_Penerima_Manfaat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;

class DashboardExportController extends Controller
{
    public function export(Request $request)
    {
        $format = strtolower($request->input('format', 'pdf'));

        // Accept JSON string inputs for filters/snapshots from client and decode
        $format = $request->input('format');
        $filters = $request->input('filters');
        $snapshots = $request->input('snapshots');
        $mapMeta = $request->input('mapMeta');
        if (is_string($filters)) { $filters = json_decode($filters, true) ?: []; }
        if (is_string($snapshots)) { $snapshots = json_decode($snapshots, true) ?: []; }
        if (is_string($mapMeta)) { $mapMeta = json_decode($mapMeta, true) ?: null; }
        // Basic validation
        $request->merge(['filters' => $filters, 'snapshots' => $snapshots, 'format' => $format]);
        $validated = $request->validate([
            'format' => 'required|in:pdf,docx',
            'filters.provinsi_id' => 'nullable|integer',
            'filters.program_id' => 'nullable|integer',
            'filters.tahun' => 'nullable|integer',
            'snapshots.barChart' => 'nullable|string',
            'snapshots.pieChart' => 'nullable|string',
            'snapshots.map' => 'nullable|string',
            'mapMeta.centerLat' => 'nullable|numeric',
            'mapMeta.centerLng' => 'nullable|numeric',
            'mapMeta.zoom' => 'nullable|integer',
        ]);

        // Recompute stats to match on-screen cards (server-trusted)
        $statsQuery = Meals_Penerima_Manfaat::query()
            ->with(['program', 'kelompokMarjinal'])
            ->when(($filters['provinsi_id'] ?? null), function ($query, $provinsi_id) {
                $query->whereHas('dusun.desa.kecamatan.kabupaten.provinsi', fn($q) => $q->where('id', $provinsi_id));
            })
            ->when(($filters['program_id'] ?? null), fn($q, $program_id) => $q->where('program_id', $program_id))
            ->when(($filters['tahun'] ?? null), function ($q, $tahun) {
                $q->whereHas('program', fn($qq) => $qq->whereYear('tanggalmulai', '<=', $tahun)->whereYear('tanggalselesai', '>=', $tahun));
            })
            ->whereNull('deleted_at')
            ->get();

        $uniqueFamilies = $statsQuery->map(function ($item) {
            return $item->is_head_family ? trim(strtolower($item->nama)) : trim(strtolower($item->head_family_name));
        })->filter()->unique()->count();

        $cards = [
            'semua' => $statsQuery->count(),
            'laki' => $statsQuery->where('jenis_kelamin', 'laki')->count(),
            'perempuan' => $statsQuery->where('jenis_kelamin', 'perempuan')->count(),
            'anak_laki' => $statsQuery->where('jenis_kelamin', 'laki')->where('umur', '<', 17)->count(),
            'anak_perempuan' => $statsQuery->where('jenis_kelamin', 'perempuan')->where('umur', '<', 17)->count(),
            'disabilitas' => $statsQuery->filter(fn($item) => $item->kelompokMarjinal->contains('id', 3))->count(),
            'keluarga' => $uniqueFamilies,
        ];

        // Prepare table data (no pagination): group by dusun
        $tableData = $statsQuery->groupBy('dusun_id')->map(function ($items) {
            $first = $items->first();
            return [
                'nama_dusun' => $first->dusun->nama ?? '-',
                'desa' => $first->dusun->desa->nama ?? '-',
                'kecamatan' => $first->dusun->desa->kecamatan->nama ?? '-',
                'kabupaten' => $first->dusun->desa->kecamatan->kabupaten->nama ?? '-',
                'provinsi' => $first->dusun->desa->kecamatan->kabupaten->provinsi->nama ?? '-',
                'program' => $first->program->nama ?? '-',
                'total_penerima' => $items->count(),
                'laki' => $items->where('jenis_kelamin', 'laki')->count(),
                'perempuan' => $items->where('jenis_kelamin', 'perempuan')->count(),
                'anak_laki' => $items->where('jenis_kelamin', 'laki')->where('umur', '<', 17)->count(),
                'anak_perempuan' => $items->where('jenis_kelamin', 'perempuan')->where('umur', '<', 17)->count(),
                'disabilitas' => $items->filter(fn($item) => $item->kelompokMarjinal->contains('id', 3))->count(),
            ];
        })->values();

        // Static Maps fallback if map snapshot is missing
        if (empty($snapshots['map']) && !empty($mapMeta['centerLat']) && !empty($mapMeta['centerLng'])) {
            $apiKey = env('GOOGLE_MAPS_API_KEY');
            if ($apiKey) {
                $center = $mapMeta['centerLat'] . ',' . $mapMeta['centerLng'];
                $zoom = (int)($mapMeta['zoom'] ?? 6);
                $size = '1024x512';
                $staticUrl = 'https://maps.googleapis.com/maps/api/staticmap?center=' . urlencode($center)
                    . '&zoom=' . $zoom . '&size=' . $size . '&maptype=roadmap&key=' . $apiKey;
                // Try to fetch and embed as data URI for PDF compatibility
                try {
                    $img = @file_get_contents($staticUrl);
                    if ($img !== false) {
                        $snapshots['map'] = 'data:image/png;base64,' . base64_encode($img);
                    } else {
                        $snapshots['map_static_url'] = $staticUrl;
                    }
                } catch (\Throwable $e) {
                    $snapshots['map_static_url'] = $staticUrl;
                }
            }
        }

        $data = [
            'cards' => $cards,
            'filters' => $filters,
            'snapshots' => $snapshots ?? [],
            'tableData' => $tableData,
        ];

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('dashboard.export', $data)
                ->setPaper('a4', 'landscape')
                ->setOption('isRemoteEnabled', true);
            return $pdf->download('dashboard-export.pdf');
        }

        if ($format === 'docx') {
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();

            // Title
            $section->addText('Dashboard Export', ['bold' => true, 'size' => 16]);

            // Cards summary
            $section->addText(sprintf('Total: %d | Laki: %d | Perempuan: %d | Anak L: %d | Anak P: %d | Disabilitas: %d | Keluarga: %d',
                $cards['semua'], $cards['laki'], $cards['perempuan'], $cards['anak_laki'], $cards['anak_perempuan'], $cards['disabilitas'], $cards['keluarga']
            ), ['size' => 10]);

            // Charts & Map images if provided
            foreach (['barChart' => 'Bar Chart', 'pieChart' => 'Pie Chart', 'kabupatenPie' => 'Kabupaten Pie Chart', 'map' => 'Map'] as $key => $label) {
                $img = $data['snapshots'][$key] ?? null;
                if ($img && str_starts_with($img, 'data:image')) {
                    $tmp = tempnam(sys_get_temp_dir(), 'dash_');
                    $raw = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img));
                    file_put_contents($tmp, $raw);
                    $section->addText($label, ['bold' => true]);
                    $section->addImage($tmp, ['width' => 500]);
                }
            }

            // Table
            if ($tableData->count()) {
                $section->addText('Table Desa Penerima Manfaat', ['bold' => true]);
                $table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
                $table->addRow();
                foreach (['Dusun','Desa','Kecamatan','Kabupaten','Provinsi',
                // 'Program',
                'Total','Laki','Perempuan','Anak L','Anak P','Disabilitas'] as $h) {
                    $table->addCell(1200)->addText($h, ['bold' => true]);
                }
                foreach ($tableData as $row) {
                    $table->addRow();
                    $table->addCell(1200)->addText($row['nama_dusun']);
                    $table->addCell(1200)->addText($row['desa']);
                    $table->addCell(1200)->addText($row['kecamatan']);
                    $table->addCell(1200)->addText($row['kabupaten']);
                    $table->addCell(1200)->addText($row['provinsi']);
                    // $table->addCell(1200)->addText($row['program']);
                    $table->addCell(1200)->addText((string)$row['total_penerima']);
                    $table->addCell(1200)->addText((string)$row['laki']);
                    $table->addCell(1200)->addText((string)$row['perempuan']);
                    $table->addCell(1200)->addText((string)$row['anak_laki']);
                    $table->addCell(1200)->addText((string)$row['anak_perempuan']);
                    $table->addCell(1200)->addText((string)$row['disabilitas']);
                }
            }

            $tmpDoc = tempnam(sys_get_temp_dir(), 'dash_') . '.docx';
            $phpWord->save($tmpDoc, 'Word2007');
            return response()->download($tmpDoc, 'dashboard-export.docx')->deleteFileAfterSend(true);
        }

        abort(400, 'Unsupported format');
    }
}
