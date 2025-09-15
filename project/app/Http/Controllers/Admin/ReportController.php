<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jenis_Kegiatan;
use App\Models\Program;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('report.report-idep');
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'type_laporan'      => 'required|in:kegiatan,program,meals',
            'program_id'        => 'nullable|integer|exists:trprogram,id',
            'jeniskegiatan_id'  => 'nullable|integer|exists:mjeniskegiatan,id',
            'kegiatan_id'       => 'nullable', // 'all' or integer id
            'status'            => 'nullable|in:draft,ongoing,completed,cancelled',
            'output_format'     => 'nullable|in:html,csv,xlsx,pdf',
            'group_by'          => 'nullable|in:,program,jenis,provinsi,bulan',
        ]);

        $type = $validated['type_laporan'];
        $format = $validated['output_format'] ?? 'html';

        [$headings, $rows, $title] = $this->buildReport($type, $validated);

        if ($request->ajax() && $format === 'html') {
            $html = view('report.preview-generic', [
                'headings' => $headings,
                'rows' => $rows,
            ])->render();
            return response()->json(['html' => $html]);
        }

        if ($format === 'csv' || $format === 'xlsx') {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\SimpleArrayExport($headings, $rows),
                'report_' . $type . '_' . now()->format('Ymd_His') . '.' . ($format === 'csv' ? 'csv' : 'xlsx')
            );
        }

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.report-pdf', [
                'title' => $title,
                'headings' => $headings,
                'rows' => $rows,
            ]);
            return $pdf->download('report_' . $type . '_' . now()->format('Ymd_His') . '.pdf');
        }

        // HTML preview
        return view('report.report-idep', [
            'preview' => true,
            'reportType' => $type,
            'rows' => $rows,
        ]);
    }

    private function buildReport(string $type, array $filters): array
    {
        if ($type === 'kegiatan') {
            return $this->buildKegiatanReport($filters);
        }
        if ($type === 'program') {
            return $this->buildProgramReport($filters);
        }
        return $this->buildMealsReport($filters);
    }

    private function buildKegiatanReport(array $filters): array
    {
        $query = \App\Models\Kegiatan::with([
            'jenisKegiatan:id,nama',
            'activity.program_outcome_output.program_outcome.program:id,nama',
            'lokasi.desa.kecamatan.kabupaten.provinsi'
        ])->select('trkegiatan.*');

        if (!empty($filters['program_id'])) {
            $programId = (int) $filters['program_id'];
            $query->whereHas('activity.program_outcome_output.program_outcome.program', function ($q) use ($programId) {
                $q->where('id', $programId);
            });
        }
        if (!empty($filters['jeniskegiatan_id'])) {
            $query->where('jeniskegiatan_id', (int) $filters['jeniskegiatan_id']);
        }
        if (!empty($filters['kegiatan_id']) && $filters['kegiatan_id'] !== 'all') {
            $query->where('id', (int) $filters['kegiatan_id']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        // Date filters removed as requested; always show tanggal mulai/selesai in output only

        $items = $query->get();

        $headings = ['Program', 'Jenis Kegiatan', 'Tanggal Mulai', 'Tanggal Selesai', 'Lokasi Kegiatan', 'Status Kegiatan', 'Fase Kegiatan', 'Total Penerima', 'Perempuan', 'Laki-laki', 'Dewasa', 'Remaja', 'Anak', 'Disabilitas', 'Kelompok Marjinal'];
        $rows = $items->map(function ($k) {
            $program = optional(optional(optional($k->activity)->program_outcome_output)->program_outcome)->program->nama ?? '-';
            $jenis = $k->jenisKegiatan->nama ?? '-';
            $lokasiNames = $k->lokasi->map(function ($lok) {
                $desa = optional($lok->desa)->nama;
                $kec = optional($lok->desa->kecamatan ?? null)->nama ?? optional($lok->kecamatan)->nama;
                $kab = optional($lok->desa->kecamatan->kabupaten ?? null)->nama ?? optional($lok->kabupaten)->nama;
                $prov = optional($lok->desa->kecamatan->kabupaten->provinsi ?? null)->nama ?? optional($lok->provinsi)->nama;
                return trim(collect([$desa, $kec, $kab, $prov])->filter()->implode(', '));
            })->filter()->unique()->implode('; ');
            return [
                'program' => $program,
                'jenis' => $jenis,
                'tanggal_mulai' => optional($k->tanggalmulai)->format('Y-m-d'),
                'tanggal_selesai' => optional($k->tanggalselesai)->format('Y-m-d'),
                'lokasi' => $lokasiNames ?: '-',
                'status' => $k->status,
                'fase' => (int) $k->fasepelaporan,
                'total' => (int) $k->penerimamanfaattotal,
                'perempuan' => (int) $k->penerimamanfaatperempuantotal,
                'laki_laki' => (int) $k->penerimamanfaatlakilakitotal,
                'dewasa' => (int) $k->penerimamanfaatdewasatotal,
                'remaja' => (int) $k->penerimamanfaatremajatotal,
                'anak' => (int) $k->penerimamanfaatanaktotal,
                'disabilitas' => (int) $k->penerimamanfaatdisabilitastotal,
                'marjinal' => (int) $k->penerimamanfaatmarjinaltotal,
            ];
        })->toArray();

        return [$headings, $rows, 'Laporan Kegiatan'];
    }

    private function buildProgramReport(array $filters): array
    {
        $programId = $filters['program_id'] ?? null;
        $jenisId = $filters['jeniskegiatan_id'] ?? null;
        $kegiatanId = $filters['kegiatan_id'] ?? null;
        $status = $filters['status'] ?? null;
        // Date filters removed

        $q = \DB::table('trprogram as p')
            ->leftJoin('trprogramoutcome as o', 'o.program_id', '=', 'p.id')
            ->leftJoin('trprogramoutcomeoutput as oo', 'oo.programoutcome_id', '=', 'o.id')
            ->leftJoin('trprogramoutcomeoutputactivity as a', 'a.programoutcomeoutput_id', '=', 'oo.id')
            ->leftJoin('trkegiatan as k', 'k.programoutcomeoutputactivity_id', '=', 'a.id')
            ->selectRaw('p.id, p.kode, p.nama, p.tanggalmulai, p.tanggalselesai, p.totalnilai, COUNT(DISTINCT k.id) as total_kegiatan, COALESCE(SUM(k.penerimamanfaattotal),0) as total_beneficiaries')
            ->when($programId, fn($qq) => $qq->where('p.id', $programId))
            ->when($jenisId, fn($qq) => $qq->where('k.jeniskegiatan_id', $jenisId))
            ->when($kegiatanId && $kegiatanId !== 'all', fn($qq) => $qq->where('k.id', $kegiatanId))
            ->when($status, fn($qq) => $qq->where('k.status', $status))
            // no date filtering
            ->groupBy('p.id', 'p.kode', 'p.nama', 'p.tanggalmulai', 'p.tanggalselesai', 'p.totalnilai')
            ->orderBy('p.nama');

        $items = $q->get();

        $headings = ['Kode', 'Nama', 'Tanggal Mulai', 'Tanggal Selesai', 'Total Kegiatan', 'Total Beneficiaries', 'Budget'];
        $rows = $items->map(function ($p) {
            return [
                'kode' => $p->kode,
                'nama' => $p->nama,
                'tanggal_mulai' => optional(\Carbon\Carbon::parse($p->tanggalmulai))->format('Y-m-d'),
                'tanggal_selesai' => optional(\Carbon\Carbon::parse($p->tanggalselesai))->format('Y-m-d'),
                'total_kegiatan' => (int) $p->total_kegiatan,
                'total_beneficiaries' => (int) $p->total_beneficiaries,
                'budget' => (float) $p->totalnilai,
            ];
        })->toArray();

        return [$headings, $rows, 'Laporan Program'];
    }

    private function buildMealsReport(array $filters): array
    {
        $programId = $filters['program_id'] ?? null;
        $kegiatanId = $filters['kegiatan_id'] ?? null;

        // If kegiatan selected, derive program from kegiatan
        if (!empty($kegiatanId) && $kegiatanId !== 'all') {
            $k = \App\Models\Kegiatan::with('activity.program_outcome_output.program_outcome.program')
                ->find((int) $kegiatanId);
            if ($k) {
                $programId = optional(optional(optional($k->activity)->program_outcome_output)->program_outcome)->program->id ?? $programId;
            }
        }

        $query = \App\Models\Meals_Komponen_Model::with([
            'program:id,nama',
            'komponenmodel:id,nama',
            'lokasi.satuan:id,nama',
            'lokasi.desa.kecamatan.kabupaten.provinsi'
        ])->when($programId, fn($q) => $q->where('program_id', (int) $programId));

        $items = $query->get();

        $rows = [];
        foreach ($items as $item) {
            foreach ($item->lokasi as $lok) {
                $rows[] = [
                    'program' => $item->program->nama ?? '-',
                    'komponen' => $item->komponenmodel->nama ?? '-',
                    'jumlah' => (int) ($lok->jumlah ?? $item->totaljumlah ?? 0),
                    'satuan' => $lok->satuan->nama ?? '-',
                    'provinsi' => optional($lok->provinsi)->nama ?? optional(optional($lok->desa)->kecamatan->kabupaten->provinsi ?? null)->nama ?? '-',
                    'kabupaten' => optional($lok->kabupaten)->nama ?? optional(optional($lok->desa)->kecamatan->kabupaten ?? null)->nama ?? '-',
                    'kecamatan' => optional($lok->kecamatan)->nama ?? optional(optional($lok->desa)->kecamatan ?? null)->nama ?? '-',
                    'desa' => optional($lok->desa)->nama ?? '-',
                    'long' => $lok->long,
                    'lat' => $lok->lat,
                ];
            }
        }

        $headings = ['Program', 'Komponen Model', 'Jumlah', 'Satuan', 'Provinsi', 'Kabupaten', 'Kecamatan', 'Desa', 'Long', 'Lat'];
        return [$headings, $rows, 'Laporan MEALS - Komponen Model'];
    }

    // Simple Select2-friendly program list
    public function getPrograms(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'page'   => 'nullable|integer|min:1',
            'id'     => 'nullable|array',
        ]);

        $search = $request->input('search', '');
        $page   = (int) $request->input('page', 1);
        $ids    = $request->input('id', []);
        if (!is_array($ids) && $ids !== null) {
            $ids = [$ids];
        }

        $query = Program::query()->select('id', 'nama');

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        } elseif ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $results = $query->orderBy('nama')->paginate(20, ['*'], 'page', $page);

        return response()->json([
            'results' => $results->getCollection()->map(function ($item) {
                return [
                    'id'   => $item->id,
                    'text' => $item->nama,
                ];
            }),
            'pagination' => [
                'more' => $results->hasMorePages(),
            ],
        ]);
    }

    // Simple Select2-friendly jenis kegiatan list
    public function getJenisKegiatan(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'page'   => 'nullable|integer|min:1',
            'id'     => 'nullable|array',
        ]);

        $search = $request->input('search', '');
        $page   = (int) $request->input('page', 1);
        $ids    = $request->input('id', []);
        if (!is_array($ids) && $ids !== null) {
            $ids = [$ids];
        }

        $query = Jenis_Kegiatan::query()->select('id', 'nama');

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        } elseif ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $results = $query->orderBy('nama')->paginate(20, ['*'], 'page', $page);

        return response()->json([
            'results' => $results->getCollection()->map(function ($item) {
                return [
                    'id'   => $item->id,
                    'text' => $item->nama,
                ];
            }),
            'pagination' => [
                'more' => $results->hasMorePages(),
            ],
        ]);
    }
}
