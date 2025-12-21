<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Export\BTOR;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BTORExport;
use Novay\Word\Facades\Word;

class BTORController extends Controller
{
    /**
     * Display a listing of BTOR reports
     */
    public function index(Request $request)
    {
        $filters = [
            'jeniskegiatan_id' => $request->input('jeniskegiatan_id'),
            'status' => $request->input('status'),
            'program_id' => $request->input('program_id'),
            'activity_id' => $request->input('activity_id'),
        ];

        $kegiatanList = BTOR::getFilteredList($filters);

        return view('tr.btor.index', compact('kegiatanList', 'filters'));
    }

    /**
     * API: Get all programs for Select2
     */
    public function getPrograms(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $perPage = 20;

        $query = \App\Models\Program::query();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('kode', 'like', "%{$search}%");
        }

        $total = $query->count();
        $programs = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $results = $programs->map(function ($program) {
            return [
                'id' => $program->id,
                'text' => $program->kode . ' - ' . $program->nama
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => ($page * $perPage) < $total
            ]
        ]);
    }

    /**
     * API: Get activities (programoutcomeoutputactivity) that have kegiatan in trkegiatan
     * Returns unique activities based on program filter
     */
    public function getKegiatanByProgram(Request $request)
    {
        $programId = $request->input('program_id');
        $search = $request->input('search');

        // Get distinct activity IDs that have kegiatan records
        $query = Kegiatan::select('programoutcomeoutputactivity_id')
            ->distinct()
            ->with('programOutcomeOutputActivity');

        // Filter by program if selected
        if ($programId) {
            $query->whereHas('programOutcomeOutputActivity.program_outcome_output.program_outcome.program', function ($q) use ($programId) {
                $q->where('id', $programId);
            });
        }

        // Search by activity kode or nama
        if ($search) {
            $query->whereHas('programOutcomeOutputActivity', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        $activities = $query->get()
            ->map(function ($kegiatan) {
                $activity = $kegiatan->programOutcomeOutputActivity;
                if (!$activity)
                    return null;
                return [
                    'id' => $activity->id,
                    'text' => ($activity->kode ?? '') . ' - ' . ($activity->nama ?? 'N/A')
                ];
            })
            ->filter()
            ->unique('id')
            ->values();

        return response()->json([
            'results' => $activities,
            'pagination' => ['more' => false]
        ]);
    }

    /**
     * API: Get jenis kegiatan based on selected activity (programoutcomeoutputactivity_id)
     * Returns distinct jenis kegiatan types that exist in trkegiatan for the selected activity
     */
    public function getJenisKegiatanByKegiatan(Request $request)
    {
        $activityId = $request->input('activity_id');
        $search = $request->input('search');

        // If activity selected, get distinct jenis kegiatan from trkegiatan for this activity
        if ($activityId) {
            $jenisIds = Kegiatan::where('programoutcomeoutputactivity_id', $activityId)
                ->distinct()
                ->pluck('jeniskegiatan_id')
                ->filter();

            $query = \App\Models\Jenis_Kegiatan::whereIn('id', $jenisIds);
        } else {
            // Return all jenis kegiatan if no activity selected
            $query = \App\Models\Jenis_Kegiatan::query();
        }

        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $jenisList = $query->get();

        $results = $jenisList->map(function ($jenis) {
            return [
                'id' => $jenis->id,
                'text' => $jenis->nama
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => ['more' => false]
        ]);
    }

    /**
     * Display the specified BTOR report
     */
    public function show($id)
    {
        $kegiatan = BTOR::getData($id);
        $viewPath = BTOR::getViewPath($kegiatan->jeniskegiatan_id);

        return view('tr.btor.show', compact('kegiatan', 'viewPath'));
    }

    public function print($id)
    {
        $kegiatan = BTOR::getData($id);
        $viewPath = BTOR::getViewPath($kegiatan->jeniskegiatan_id);
        $showButtons = true; // Show buttons for print preview

        return view('tr.btor.print', compact('kegiatan', 'viewPath', 'showButtons'));
    }

    /**
     * Print preview for multiple kegiatan in a single page
     */
    public function printBulk(Request $request)
    {
        $ids = $request->input('ids', []);

        // Handle comma-separated string from URL
        if (is_string($ids)) {
            $ids = array_filter(explode(',', $ids));
        }

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal 1 laporan untuk dicetak');
        }

        $kegiatanList = collect($ids)->map(function ($id) {
            $kegiatan = BTOR::getData($id);
            return [
                'kegiatan' => $kegiatan,
                'viewPath' => BTOR::getViewPath($kegiatan->jeniskegiatan_id)
            ];
        });

        $showButtons = true;

        return view('tr.btor.print-bulk', compact('kegiatanList', 'showButtons'));
    }

    public function exportPdf($id, Request $request)
    {
        $kegiatan = BTOR::getData($id);
        $viewPath = BTOR::getViewPath($kegiatan->jeniskegiatan_id);
        $showButtons = false; // Hide buttons for PDF

        // Customizable header/footer config (can be extended via request params)
        $headerConfig = [
            'organization' => $request->get('organization', 'IDEP Foundation'),
            'department' => $request->get('department', 'Program'),
            'showReportInfo' => true,
            'logo' => public_path('images/logo-idep.png'),
        ];

        $footerConfig = [
            'organization' => $request->get('organization', 'IDEP Foundation'),
            'showPageNumber' => true,
            'customText' => 'Back to Office Report (BTOR)',
        ];

        $orientation = $request->get('orientation', 'portrait');

        $pdf = Pdf::loadView('tr.btor.pdf-export', compact(
            'kegiatan', 
            'viewPath', 
            'showButtons',
            'headerConfig',
            'footerConfig'
        ))
            ->setPaper('a4', $orientation)
            ->setOption([
                'margin-top' => 20,
                'margin-bottom' => 25,
                'margin-left' => 15,
                'margin-right' => 15,
                'encoding' => 'UTF-8',
                'enable-local-file-access' => true,
            ]);

        $filename = 'BTOR_' . $kegiatan->id . '_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }


    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $filters = [
            'jeniskegiatan_id' => $request->input('jeniskegiatan_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => $request->input('status'),
            'program_id' => $request->input('program_id'),
        ];

        $filename = 'BTOR_Report_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new BTORExport($filters), $filename);
    }

    /**
     * Export multiple reports to single PDF (all reports looped together)
     */
    public function exportBulkPdf(Request $request)
    {
        $ids = $request->input('ids', []);

        // Handle comma-separated string from form
        if (is_string($ids)) {
            $ids = array_filter(explode(',', $ids));
        }

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal 1 laporan untuk diekspor');
        }

        // Build kegiatan list with view paths (same structure as printBulk)
        $kegiatanList = collect($ids)->map(function ($id) {
            $kegiatan = BTOR::getData($id);
            return [
                'kegiatan' => $kegiatan,
                'viewPath' => BTOR::getViewPath($kegiatan->jeniskegiatan_id)
            ];
        });

        // Customizable header/footer config
        $headerConfig = [
            'organization' => 'IDEP Foundation',
            'department' => 'Program',
            'showReportInfo' => true,
            'logo' => public_path('images/logo-idep.png'),
        ];

        $footerConfig = [
            'organization' => 'IDEP Foundation',
            'showPageNumber' => true,
            'customText' => 'Back to Office Report (BTOR)',
        ];

        $showButtons = false;

        $pdf = Pdf::loadView('tr.btor.pdf-export-bulk', compact(
            'kegiatanList',
            'showButtons',
            'headerConfig',
            'footerConfig'
        ))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top' => 20,
                'margin-bottom' => 25,
                'margin-left' => 15,
                'margin-right' => 15,
                'encoding' => 'UTF-8',
                'enable-local-file-access' => true,
            ]);

        $filename = 'BTOR_Bulk_' . count($ids) . '_Reports_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }


    /**
     * Show export configuration page
     */
    public function exportConfig()
    {
        $jenisKegiatanList = \App\Models\Jenis_Kegiatan::all();
        $programList = \App\Models\Program::all();

        return view('tr.btor.export', compact('jenisKegiatanList', 'programList'));
    }

    /**
     * Export single report to DOCX
     */
    public function exportDocx($id)
    {
        $kegiatan = BTOR::getData($id);
        
        try {
            $builder = Word::builder();
            
            // Helper to sanitize text
            $sanitize = function($text) {
                if (empty($text)) return '-';
                return trim(strip_tags(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'))) ?: '-';
            };

            // Build the DOCX content
            $this->buildDocxContent($builder, $kegiatan, $sanitize);

            // Clear output buffer to prevent file corruption
            if (ob_get_length()) {
                ob_end_clean();
            }

            $filename = 'BTOR_' . $kegiatan->id . '_' . date('Ymd_His') . '.docx';
            return $builder->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('BTOR DOCX Export Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Gagal export DOCX: ' . $e->getMessage());
        }
    }

    /**
     * Export multiple reports to single DOCX
     */
    public function exportBulkDocx(Request $request)
    {
        $ids = $request->input('ids', []);

        // Handle comma-separated string from form
        if (is_string($ids)) {
            $ids = array_filter(explode(',', $ids));
        }

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal 1 laporan untuk diekspor');
        }

        try {
            $builder = Word::builder();
            
            // Helper to sanitize text
            $sanitize = function($text) {
                if (empty($text)) return '-';
                return trim(strip_tags(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'))) ?: '-';
            };

            foreach ($ids as $index => $id) {
                $kegiatan = BTOR::getData($id);
                
                // Add page break before each report except the first
                if ($index > 0) {
                    $builder->addPageBreak();
                }
                
                // Build the DOCX content
                $this->buildDocxContent($builder, $kegiatan, $sanitize);
            }

            // Clear output buffer to prevent file corruption
            if (ob_get_length()) {
                ob_end_clean();
            }

            $filename = 'BTOR_Bulk_' . count($ids) . '_Reports_' . date('Ymd_His') . '.docx';
            return $builder->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('BTOR Bulk DOCX Export Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal export DOCX: ' . $e->getMessage());
        }
    }

    /**
     * Build DOCX content for a single kegiatan
     */
    private function buildDocxContent($builder, $kegiatan, $sanitize)
    {
        // Add Header Image
        $headerImagePath = public_path('images/uploads/header.png');
        if (file_exists($headerImagePath)) {
            $builder->addImage($headerImagePath, ['width' => 450, 'height' => 40, 'alignment' => 'center']);
        }
        $builder->addText('');

        // Title
        $builder->addText('BACK TO OFFICE REPORT', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $builder->addText('');

        // Prepare data
        $programNama = $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-';
        $kegiatanNama = $kegiatan->programOutcomeOutputActivity?->nama ?? '-';
        $kodeBudget = $kegiatan->programOutcomeOutputActivity?->kode ?? '-';
        $penulisNames = $kegiatan->kegiatan_penulis?->pluck('user.nama')->filter()->implode(', ') ?: ($kegiatan->user->name ?? '-');
        $jabatanNames = $kegiatan->kegiatan_penulis?->pluck('peran.nama')->filter()->implode(', ') ?: '-';

        // Metadata Table
        $builder->addTable([
            ['Department', ':', 'Program'],
            ['Program', ':', $sanitize($programNama)],
            ['Nama Kegiatan', ':', $sanitize($kegiatanNama)],
            ['Kode Budget', ':', $sanitize($kodeBudget)],
            ['Penulis Laporan', ':', $sanitize($penulisNames)],
            ['Jabatan', ':', $sanitize($jabatanNames)],
        ]);
        $builder->addText('');

        // A. Latar Belakang Kegiatan
        $builder->addText('A. Latar Belakang Kegiatan', ['bold' => true, 'size' => 11]);
        $builder->addText($sanitize($kegiatan->deskripsilatarbelakang));
        $builder->addText('');

        // B. Tujuan Kegiatan
        $builder->addText('B. Tujuan Kegiatan', ['bold' => true, 'size' => 11]);
        $builder->addText($sanitize($kegiatan->deskripsitujuan));
        $builder->addText('');

        // C. Detail Kegiatan
        $builder->addText('C. Detail Kegiatan', ['bold' => true, 'size' => 11]);
        $tglFormatted = '-';
        if ($kegiatan->tanggalmulai) {
            $tglFormatted = $kegiatan->tanggalmulai == $kegiatan->tanggalselesai 
                ? \Carbon\Carbon::parse($kegiatan->tanggalmulai)->translatedFormat('d F Y')
                : \Carbon\Carbon::parse($kegiatan->tanggalmulai)->translatedFormat('d') . ' - ' . \Carbon\Carbon::parse($kegiatan->tanggalselesai)->translatedFormat('d F Y');
        }
        $lokasi = $kegiatan->lokasi?->first()?->desa?->nama ?? $kegiatan->lokasi_kegiatan ?? '-';
        $pihak = $kegiatan->mitra?->pluck('nama')->implode(', ') ?: '-';
        
        $builder->addTable([
            ['a. Hari, tanggal', ':', $tglFormatted],
            ['b. Tempat', ':', $sanitize($lokasi)],
            ['c. Pihak yang terlibat', ':', $sanitize($pihak)],
        ]);
        $builder->addText('');

        // D. Hasil Kegiatan
        $builder->addText('D. Hasil Kegiatan', ['bold' => true, 'size' => 11]);
        $builder->addText('a. Jumlah Partisipan yang Terlibat dan Disagregat', ['bold' => true]);
        $builder->addText('');
        
        // Beneficiary Table - Age Groups
        $builder->addTable([
            ['Penerima Manfaat', 'Perempuan', 'Laki-laki', 'Lainnya', 'Sub Total'],
            ['Dewasa (25-59 tahun)', (string)($kegiatan->penerimamanfaatdewasaperempuan ?? 0), (string)($kegiatan->penerimamanfaatdewasalakilaki ?? 0), '0', (string)($kegiatan->penerimamanfaatdewasatotal ?? 0)],
            ['Lansia (60+ tahun)', (string)($kegiatan->penerimamanfaatlansiaperempuan ?? 0), (string)($kegiatan->penerimamanfaatlansialakilaki ?? 0), '0', (string)($kegiatan->penerimamanfaatlansiatotal ?? 0)],
            ['Remaja (18-24 tahun)', (string)($kegiatan->penerimamanfaatremajaperempuan ?? 0), (string)($kegiatan->penerimamanfaatremajalakilaki ?? 0), '0', (string)($kegiatan->penerimamanfaatremajatotal ?? 0)],
            ['Anak (<18 tahun)', (string)($kegiatan->penerimamanfaatanakperempuan ?? 0), (string)($kegiatan->penerimamanfaatanaklakilaki ?? 0), '0', (string)($kegiatan->penerimamanfaatanaktotal ?? 0)],
            ['Grand Total', (string)($kegiatan->penerimamanfaatperempuantotal ?? 0), (string)($kegiatan->penerimamanfaatlakilakitotal ?? 0), '0', (string)($kegiatan->penerimamanfaattotal ?? 0)],
        ]);
        $builder->addText('');

        // Disability Table
        $builder->addTable([
            ['Kelompok Khusus', 'Perempuan', 'Laki-laki', 'Lainnya', 'Sub Total'],
            ['Penyandang Disabilitas', (string)($kegiatan->penerimamanfaatdisabilitasperempuan ?? 0), (string)($kegiatan->penerimamanfaatdisabilitaslakilaki ?? 0), '0', (string)($kegiatan->penerimamanfaatdisabilitastotal ?? 0)],
            ['Non-disabilitas', (string)($kegiatan->penerimamanfaatnondisabilitasperempuan ?? 0), (string)($kegiatan->penerimamanfaatnondisabilitaslakilaki ?? 0), '0', (string)($kegiatan->penerimamanfaatnondisabilitastotal ?? 0)],
            ['Kelompok Marjinal Lainnya', (string)($kegiatan->penerimamanfaatmarjinalperempuan ?? 0), (string)($kegiatan->penerimamanfaatmarjinallakilaki ?? 0), '0', (string)($kegiatan->penerimamanfaatmarjinaltotal ?? 0)],
        ]);
        $builder->addText('');

        $builder->addText('b. Hasil Pertemuan', ['bold' => true]);
        $builder->addText($sanitize($kegiatan->deskripsikeluaran));
        $builder->addText('');

        // E. Tantangan dan Solusi
        $builder->addText('E. Tantangan dan Solusi', ['bold' => true, 'size' => 11]);
        $kendala = $kegiatan->assessment?->assessmentkendala 
            ?? $kegiatan->pelatihan?->pelatihanisu 
            ?? $kegiatan->monitoring?->monitoringkendala 
            ?? '-';
        $solusi = $kegiatan->assessment?->assessmentsolusi 
            ?? $kegiatan->pelatihan?->pelatihansolusi 
            ?? $kegiatan->monitoring?->monitoringsolusi 
            ?? '-';
        
        $builder->addTable([
            ['Tantangan', 'Solusi yang Diambil Tim'],
            [$sanitize($kendala), $sanitize($solusi)],
        ]);
        $builder->addText('');

        // F. Isu yang Perlu Diperhatikan & Rekomendasi
        $builder->addText('F. Isu yang Perlu Diperhatikan & Rekomendasi', ['bold' => true, 'size' => 11]);
        $isu = $kegiatan->assessment?->assessmentisu 
            ?? $kegiatan->pelatihan?->pelatihanisu 
            ?? $kegiatan->monitoring?->monitoringisu 
            ?? '-';
        $rekomendasi = $kegiatan->assessment?->assessmentrekomendasi
            ?? $kegiatan->pelatihan?->pelatihanrekomendasi
            ?? $kegiatan->monitoring?->monitoringrekomendasi
            ?? '-';
        
        $builder->addTable([
            ['Isu yang Perlu Diperhatikan', 'Rekomendasi'],
            [$sanitize($isu), $sanitize($rekomendasi)],
        ]);
        $builder->addText('');

        // G. Pembelajaran
        $builder->addText('G. Pembelajaran', ['bold' => true, 'size' => 11]);
        $pembelajaran = $kegiatan->assessment?->assessmentpembelajaran
            ?? $kegiatan->pelatihan?->pelatihanpembelajaran
            ?? $kegiatan->monitoring?->monitoringpembelajaran
            ?? $kegiatan->sosialisasi?->sosialisasipembelajaran
            ?? $kegiatan->kampanye?->kampanyepembelajaran
            ?? $kegiatan->konsultasi?->konsultasipembelajaran
            ?? $kegiatan->kunjungan?->kunjunganpembelajaran
            ?? $kegiatan->pembelanjaan?->pembelanjaanpembelajaran
            ?? $kegiatan->pengembangan?->pengembanganpembelajaran
            ?? $kegiatan->pemetaan?->pemetaanpembelajaran
            ?? $kegiatan->lainnya?->lainnyapembelajaran
            ?? '-';
        $builder->addText($sanitize($pembelajaran));
        $builder->addText('');

        // H. Dokumen Pendukung
        $builder->addText('H. Dokumen Pendukung', ['bold' => true, 'size' => 11]);
        $dokumen = $kegiatan->getMedia('dokumen_pendukung');
        if ($dokumen && $dokumen->count() > 0) {
            $builder->addList($dokumen->pluck('name')->toArray());
        } else {
            $builder->addText('-');
        }
        $builder->addText('');

        // I. Catatan Penulis Laporan
        $builder->addText('I. Catatan Penulis Laporan', ['bold' => true, 'size' => 11]);
        $builder->addText('-');
        $builder->addText('');

        // Footer
        $builder->addText('');
        $builder->addText('Yayasan IDEP Selaras Alam', ['bold' => true, 'size' => 9], ['alignment' => 'center']);
        $builder->addText('Office & Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali – Indonesia', ['size' => 8], ['alignment' => 'center']);
        $builder->addText('Telp/Fax +62-361-908-2983 / +62-812 4658 5137', ['size' => 8], ['alignment' => 'center']);
    }
}

