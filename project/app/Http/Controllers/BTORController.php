<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Export\BTOR;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\SimpleType\Jc;


class BTORController extends Controller
{
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
    // public function getPrograms(Request $request)
    // {
    //     $search = $request->input('search');
    //     $page = $request->input('page', 1);
    //     $perPage = 20;

    //     $query = \App\Models\Program::query();

    //     if ($search) {
    //         $query->where('nama', 'like', "%{$search}%")
    //             ->orWhere('kode', 'like', "%{$search}%");
    //     }

    //     $total = $query->count();
    //     $programs = $query->skip(($page - 1) * $perPage)
    //         ->take($perPage)
    //         ->get();

    //     $results = $programs->map(function ($program) {
    //         return [
    //             'id' => $program->id,
    //             'text' => $program->kode . ' - ' . $program->nama
    //         ];
    //     });

    //     return response()->json([
    //         'results' => $results,
    //         'pagination' => [
    //             'more' => ($page * $perPage) < $total
    //         ]
    //     ]);
    // }

    /**
     * API: Get activities (programoutcomeoutputactivity) that have kegiatan in trkegiatan
     * Returns unique activities based on program filter
     */
    // public function getKegiatanByProgram(Request $request)
    // {
    //     $programId = $request->input('program_id');
    //     $search = $request->input('search');

    //     // Get distinct activity IDs that have kegiatan records
    //     $query = Kegiatan::select('programoutcomeoutputactivity_id')
    //         ->distinct()
    //         ->with('programOutcomeOutputActivity');

    //     // Filter by program if selected
    //     if ($programId) {
    //         $query->whereHas('programOutcomeOutputActivity.program_outcome_output.program_outcome.program', function ($q) use ($programId) {
    //             $q->where('id', $programId);
    //         });
    //     }

    //     // Search by activity kode or nama
    //     if ($search) {
    //         $query->whereHas('programOutcomeOutputActivity', function ($q) use ($search) {
    //             $q->where('nama', 'like', "%{$search}%")
    //                 ->orWhere('kode', 'like', "%{$search}%");
    //         });
    //     }

    //     $activities = $query->get()
    //         ->map(function ($kegiatan) {
    //             $activity = $kegiatan->programOutcomeOutputActivity;
    //             if (!$activity)
    //                 return null;
    //             return [
    //                 'id' => $activity->id,
    //                 'text' => ($activity->kode ?? '') . ' - ' . ($activity->nama ?? 'N/A')
    //             ];
    //         })
    //         ->filter()
    //         ->unique('id')
    //         ->values();

    //     return response()->json([
    //         'results' => $activities,
    //         'pagination' => ['more' => false]
    //     ]);
    // }

    /**
     * API: Get jenis kegiatan based on selected activity (programoutcomeoutputactivity_id)
     * Returns distinct jenis kegiatan types that exist in trkegiatan for the selected activity
     */
    // public function getJenisKegiatanByKegiatan(Request $request)
    // {
    //     $activityId = $request->input('activity_id');
    //     $search = $request->input('search');

    //     // If activity selected, get distinct jenis kegiatan from trkegiatan for this activity
    //     if ($activityId) {
    //         $jenisIds = Kegiatan::where('programoutcomeoutputactivity_id', $activityId)
    //             ->distinct()
    //             ->pluck('jeniskegiatan_id')
    //             ->filter();

    //         $query = \App\Models\Jenis_Kegiatan::whereIn('id', $jenisIds);
    //     } else {
    //         // Return all jenis kegiatan if no activity selected
    //         $query = \App\Models\Jenis_Kegiatan::query();
    //     }

    //     if ($search) {
    //         $query->where('nama', 'like', "%{$search}%");
    //     }

    //     $jenisList = $query->get();

    //     $results = $jenisList->map(function ($jenis) {
    //         return [
    //             'id' => $jenis->id,
    //             'text' => $jenis->nama
    //         ];
    //     });

    //     return response()->json([
    //         'results' => $results,
    //         'pagination' => ['more' => false]
    //     ]);
    // }

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
        $showButtons = true;
        return view('tr.btor.print', compact('kegiatan', 'viewPath', 'showButtons'));
    }

    public function printBulk(Request $request)
    {
        $ids = is_string($request->input('ids')) 
            ? array_filter(explode(',', $request->input('ids'))) 
            : $request->input('ids', []);

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

        return view('tr.btor.print-bulk', compact('kegiatanList'))->with('showButtons', true);
    }

    public function exportPdf($id, Request $request)
    {
        $kegiatan = BTOR::getData($id);
        $viewPath = BTOR::getViewPath($kegiatan->jeniskegiatan_id);

        $pdf = Pdf::loadView('tr.btor.templates.pdf-styled', compact('kegiatan', 'viewPath'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top' => 15,
                'margin-bottom' => 15,
                'margin-left' => 20,
                'margin-right' => 20,
                'encoding' => 'UTF-8',
                'enable-local-file-access' => true,
            ]);

        $filename = 'BTOR_' . $kegiatan->id . '_' . date('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportDocx($id)
    {
        $kegiatan = BTOR::getData($id);
        
        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'marginTop' => 1134,
            'marginBottom' => 1134,
            'marginLeft' => 1134,
            'marginRight' => 1134,
        ]);

        // Header
        $this->addDocxHeader($section, $kegiatan);
        
        // Content sections
        $this->addDocxContent($section, $kegiatan);

        $filename = 'BTOR_' . $kegiatan->id . '_' . date('Ymd_His') . '.docx';
        $temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');
        
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }

    public function exportBulkPdf(Request $request)
    {
        $ids = is_string($request->input('ids')) 
            ? array_filter(explode(',', $request->input('ids'))) 
            : $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal 1 laporan untuk diekspor');
        }

        $kegiatanList = collect($ids)->map(function ($id) {
            $kegiatan = BTOR::getData($id);
            return [
                'kegiatan' => $kegiatan,
                'viewPath' => BTOR::getViewPath($kegiatan->jeniskegiatan_id)
            ];
        });

        $pdf = Pdf::loadView('tr.btor.templates.pdf-styled-bulk', compact('kegiatanList'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top' => 15,
                'margin-bottom' => 15,
                'margin-left' => 20,
                'margin-right' => 20,
                'encoding' => 'UTF-8',
                'enable-local-file-access' => true,
            ]);

        $filename = 'BTOR_Bulk_' . count($ids) . '_Reports_' . date('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportBulkDocx(Request $request)
    {
        $ids = is_string($request->input('ids')) 
            ? array_filter(explode(',', $request->input('ids'))) 
            : $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal 1 laporan untuk diekspor');
        }

        $phpWord = new PhpWord();

        foreach ($ids as $index => $id) {
            $kegiatan = BTOR::getData($id);
            
            $section = $phpWord->addSection([
                'marginTop' => 1134,
                'marginBottom' => 1134,
                'marginLeft' => 1134,
                'marginRight' => 1134,
            ]);

            $this->addDocxHeader($section, $kegiatan);
            $this->addDocxContent($section, $kegiatan);

            if ($index < count($ids) - 1) {
                $section->addPageBreak();
            }
        }

        $filename = 'BTOR_Bulk_' . count($ids) . '_Reports_' . date('Ymd_His') . '.docx';
        $temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');
        
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }

    private function addDocxHeader($section, $kegiatan)
    {
        // Title
        $section->addText('BACK TO OFFICE REPORT', ['bold' => true, 'size' => 16], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(1);

        // Header info
        $program = $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program;
        
        $section->addText('Department : Program', ['size' => 11]);
        $section->addText('Program : ' . ($program->nama ?? 'N/A'), ['size' => 11]);
        $section->addText('Nama Kegiatan : ' . ($kegiatan->programOutcomeOutputActivity?->nama ?? 'N/A'), ['size' => 11]);
        $section->addText('Kode budget : ' . ($kegiatan->programOutcomeOutputActivity?->kode ?? 'N/A'), ['size' => 11]);
        
        $penulis = $kegiatan->kegiatan_penulis->map(fn($p) => $p->user->nama)->join(', ');
        $section->addText('Penulis laporan : ' . $penulis, ['size' => 11]);
        
        $jabatan = $kegiatan->kegiatan_penulis->map(fn($p) => $p->peran->nama)->join(', ');
        $section->addText('Jabatan : ' . $jabatan, ['size' => 11]);
        $section->addTextBreak(1);
    }

    /**
     * Add DOCX content sections
     */

    private function addDocxContent($section, $kegiatan)
    {
        // A. Latar Belakang
        $section->addText('A. Latar Belakang Kegiatan', ['bold' => true, 'size' => 12]);
        $section->addText($kegiatan->deskripsilatarbelakang ?? 'Tidak ada deskripsi latar belakang.', ['size' => 11]);
        $section->addTextBreak(1);

        // B. Tujuan
        $section->addText('B. Tujuan Kegiatan', ['bold' => true, 'size' => 12]);
        $section->addText($kegiatan->deskripsitujuan ?? 'Tidak ada deskripsi tujuan.', ['size' => 11]);
        $section->addTextBreak(1);

        // C. Detail Kegiatan
        $section->addText('C. Detail Kegiatan', ['bold' => true, 'size' => 12]);
        $section->addText('a. Hari, tanggal : ' . 
            ($kegiatan->tanggalmulai ? date('d F Y', strtotime($kegiatan->tanggalmulai)) : 'N/A') . 
            ' - ' . 
            ($kegiatan->tanggalselesai ? date('d F Y', strtotime($kegiatan->tanggalselesai)) : 'N/A'), 
            ['size' => 11]);
        
        $lokasi = $kegiatan->lokasi_kegiatan->map(fn($l) => $l->desa->nama ?? 'N/A')->join(', ');
        $section->addText('b. Tempat : ' . $lokasi, ['size' => 11]);
        
        // Partners
        if ($kegiatan->mitra && $kegiatan->mitra->count() > 0) {
            $section->addText('c. Pihak yang terlibat :', ['size' => 11]);
            foreach ($kegiatan->mitra as $index => $mitra) {
                $section->addText('   ' . ($index + 1) . '. ' . $mitra->nama, ['size' => 11]);
            }
        }
        $section->addTextBreak(1);

        // D. Hasil Kegiatan - Beneficiaries Table
        $section->addText('D. Hasil Kegiatan', ['bold' => true, 'size' => 12]);
        $section->addText('a. Jumlah partisipan yang terlibat dan disagregat', ['size' => 11]);
        $this->addBeneficiariesTable($section, $kegiatan);
        
        $section->addText('b. Hasil pertemuan', ['size' => 11]);
        $section->addText($kegiatan->deskripsikeluaran ?? 'Tidak ada deskripsi hasil pertemuan.', ['size' => 11]);
        $section->addTextBreak(1);

        // Get specific data based on jenis kegiatan
        $specificData = $this->getSpecificKegiatanData($kegiatan);

        // E. Tantangan dan Solusi
        $section->addText('E. Tantangan dan Solusi', ['bold' => true, 'size' => 12]);
        $section->addText($specificData['kendala'] ?? 'Tidak ada data tantangan.', ['size' => 11]);
        $section->addTextBreak(1);

        // F. Isu yang Perlu Diperhatikan & Rekomendasi
        $section->addText('F. Isu yang Perlu Diperhatikan & Rekomendasi', ['bold' => true, 'size' => 12]);
        $section->addText($specificData['isu'] ?? 'Tidak ada data isu.', ['size' => 11]);
        $section->addTextBreak(1);

        // G. Pembelajaran
        $section->addText('G. Pembelajaran', ['bold' => true, 'size' => 12]);
        $section->addText($specificData['pembelajaran'] ?? 'Tidak ada data pembelajaran.', ['size' => 11]);
        $section->addTextBreak(1);

        // H. Dokumen Pendukung
        $section->addText('H. Dokumen Pendukung', ['bold' => true, 'size' => 12]);
        if ($kegiatan->getDokumenPendukung() && $kegiatan->getDokumenPendukung()->count() > 0) {
            foreach ($kegiatan->getDokumenPendukung() as $doc) {
                $section->addText('- ' . $doc->file_name, ['size' => 11]);
            }
        } else {
            $section->addText('Tidak ada dokumen pendukung.', ['size' => 11]);
        }
        $section->addTextBreak(1);

        // I. Catatan Penulis Laporan
        $section->addText('I. Catatan Penulis Laporan', ['bold' => true, 'size' => 12]);
        $section->addText('-', ['size' => 11]);
        $section->addTextBreak(2);

        // Footer
        $section->addText('Yayasan IDEP Selaras Alam', ['bold' => true, 'size' => 9], ['alignment' => Jc::CENTER]);
        $section->addText('Office & Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali – Indonesia', 
            ['size' => 8], ['alignment' => Jc::CENTER]);
        $section->addText('Telp/Fax +62-361-908-2983 / +62-812 4658 5137', 
            ['size' => 8], ['alignment' => Jc::CENTER]);
    }

    /**
     * Get specific kegiatan data based on jeniskegiatan_id
     */
    private function getSpecificKegiatanData($kegiatan)
    {
        $specificData = null;
        switch($kegiatan->jeniskegiatan_id) {
            case 1: $specificData = $kegiatan->assessment; break;
            case 2: $specificData = $kegiatan->sosialisasi; break;
            case 3: $specificData = $kegiatan->pelatihan; break;
            case 4: $specificData = $kegiatan->pembelanjaan; break;
            case 5: $specificData = $kegiatan->pengembangan; break;
            case 6: $specificData = $kegiatan->kampanye; break;
            case 7: $specificData = $kegiatan->pemetaan; break;
            case 8: $specificData = $kegiatan->monitoring; break;
            case 9: $specificData = $kegiatan->kunjungan; break;
            case 10: $specificData = $kegiatan->konsultasi; break;
            case 11: $specificData = $kegiatan->lainnya; break;
        }

        return [
            'kendala' => $specificData?->assessmentkendala 
                ?? $specificData?->sosialisasikendala 
                ?? $specificData?->pelatihanunggahan 
                ?? $specificData?->pembelanjaankendala 
                ?? $specificData?->pengembangankendala 
                ?? $specificData?->kampanyekendala 
                ?? $specificData?->pemetaanisu
                ?? $specificData?->monitoringkendala 
                ?? $specificData?->kunjungankendala 
                ?? $specificData?->konsultasikendala 
                ?? $specificData?->lainnyakendala 
                ?? 'Tidak ada data tantangan.',
            
            'isu' => $specificData?->assessmentisu 
                ?? $specificData?->sosialisasiisu 
                ?? $specificData?->pelatihanisu 
                ?? $specificData?->pembelanjaanisu 
                ?? $specificData?->pengembanganisu 
                ?? $specificData?->kampanyeisu 
                ?? $specificData?->pemetaanisu
                ?? $specificData?->monitoringisu 
                ?? $specificData?->kunjunganisu 
                ?? $specificData?->konsultasiisu 
                ?? $specificData?->lainnyaisu 
                ?? 'Tidak ada data isu.',
            
            'pembelajaran' => $specificData?->assessmentpembelajaran 
                ?? $specificData?->sosialisasipembelajaran 
                ?? $specificData?->pelatihanpembelajaran 
                ?? $specificData?->pembelanjaanpembelajaran 
                ?? $specificData?->pengembanganpembelajaran 
                ?? $specificData?->kampanyepembelajaran 
                ?? $specificData?->pemetaanpembelajaran
                ?? $specificData?->monitoringpembelajaran 
                ?? $specificData?->kunjunganpembelajaran 
                ?? $specificData?->konsultasipembelajaran 
                ?? $specificData?->lainnyapembelajaran 
                ?? 'Tidak ada data pembelajaran.',
        ];
    }

    /**
     * Add beneficiaries table to DOCX
     */

    private function addBeneficiariesTable($section, $kegiatan)
    {
        // Age Group Table
        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'width' => 9000, 'unit' => 'pct'];
        $table = $section->addTable($tableStyle);
        
        // Header row
        $table->addRow();
        $table->addCell(3000, ['bgColor' => 'f0f0f0'])->addText('Penerima Manfaat', ['bold' => true, 'size' => 10]);
        $table->addCell(2000, ['bgColor' => 'f0f0f0'])->addText('Perempuan', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $table->addCell(2000, ['bgColor' => 'f0f0f0'])->addText('Laki-laki', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $table->addCell(1000, ['bgColor' => 'f0f0f0'])->addText('Lainnya', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $table->addCell(2000, ['bgColor' => 'f0f0f0'])->addText('Sub Total', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);

        // Data rows
        $this->addTableRow($table, 'Dewasa (umur 25 sampai 59 tahun)', 
            $kegiatan->penerimamanfaatdewasaperempuan, 
            $kegiatan->penerimamanfaatdewasalakilaki, 
            $kegiatan->penerimamanfaatdewasatotal);
        
        $this->addTableRow($table, 'Lansia (umur 60 ke atas, berdasarkan Perpres 88 Tahun 2021)', 
            $kegiatan->penerimamanfaatlansiaperempuan, 
            $kegiatan->penerimamanfaatlansialakilaki, 
            $kegiatan->penerimamanfaatlansiatotal);
        
        $this->addTableRow($table, 'Remaja (umur 18 - 24 tahun, berdasarkan BKKBN dengan penyesuaian)', 
            $kegiatan->penerimamanfaatremajaperempuan, 
            $kegiatan->penerimamanfaatremajalakilaki, 
            $kegiatan->penerimamanfaatremajatotal);
        
        $this->addTableRow($table, 'Anak (umur 18 ke bawah, berdasarkan rekomendasi SCI)', 
            $kegiatan->penerimamanfaatanakperempuan, 
            $kegiatan->penerimamanfaatanaklakilaki, 
            $kegiatan->penerimamanfaatanaktotal);
        
        // Grand Total
        $table->addRow();
        $table->addCell(3000, ['bgColor' => 'f0f0f0'])->addText('Grand Total', ['bold' => true, 'size' => 10]);
        $table->addCell(2000, ['bgColor' => 'f0f0f0'])->addText($kegiatan->penerimamanfaatperempuantotal ?? 0, ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $table->addCell(2000, ['bgColor' => 'f0f0f0'])->addText($kegiatan->penerimamanfaatlakilakitotal ?? 0, ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $table->addCell(1000, ['bgColor' => 'f0f0f0'])->addText('0', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $table->addCell(2000, ['bgColor' => 'f0f0f0'])->addText($kegiatan->penerimamanfaattotal ?? 0, ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);

        $section->addTextBreak(1);

        // Disability/Marginal Group Table
        $table2 = $section->addTable($tableStyle);
        
        // Header row
        $table2->addRow();
        $table2->addCell(3000, ['bgColor' => 'f0f0f0'])->addText('Penerima Manfaat', ['bold' => true, 'size' => 10]);
        $table2->addCell(2000, ['bgColor' => 'f0f0f0'])->addText('Perempuan', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $table2->addCell(2000, ['bgColor' => 'f0f0f0'])->addText('Laki-laki', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $table2->addCell(1000, ['bgColor' => 'f0f0f0'])->addText('Lainnya', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $table2->addCell(2000, ['bgColor' => 'f0f0f0'])->addText('Sub Total', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);

        // Data rows
        $this->addTableRow($table2, 'Penyandang disabilitas', 
            $kegiatan->penerimamanfaatdisabilitasperempuan, 
            $kegiatan->penerimamanfaatdisabilitaslakilaki, 
            $kegiatan->penerimamanfaatdisabilitastotal);
        
        $this->addTableRow($table2, 'Non-disabilitas', 
            $kegiatan->penerimamanfaatnondisabilitasperempuan, 
            $kegiatan->penerimamanfaatnondisabilitaslakilaki, 
            $kegiatan->penerimamanfaatnondisabilitastotal);
        
        $this->addTableRow($table2, 'Kelompok marjinal lainnya', 
            $kegiatan->penerimamanfaatmarjinalperempuan, 
            $kegiatan->penerimamanfaatmarjinallakilaki, 
            $kegiatan->penerimamanfaatmarjinaltotal);
        
        // Grand Total
        $table2->addRow();
        $table2->addCell(3000, ['bgColor' => 'f0f0f0'])->addText('Grand Total', ['bold' => true, 'size' => 10]);
        $table2->addCell(2000, ['bgColor' => 'f0f0f0'])->addText($kegiatan->penerimamanfaatperempuantotal ?? 0, ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $table2->addCell(2000, ['bgColor' => 'f0f0f0'])->addText($kegiatan->penerimamanfaatlakilakitotal ?? 0, ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $table2->addCell(1000, ['bgColor' => 'f0f0f0'])->addText('0', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $table2->addCell(2000, ['bgColor' => 'f0f0f0'])->addText($kegiatan->penerimamanfaattotal ?? 0, ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);

        $section->addTextBreak(1);
    }

    /**
     * Add a table row to DOCX
     */

    private function addTableRow($table, $label, $female, $male, $total)
    {
        $table->addRow();
        $table->addCell(3000)->addText($label, ['size' => 10]);
        $table->addCell(2000)->addText($female ?? 0, ['size' => 10], ['alignment' => Jc::CENTER]);
        $table->addCell(2000)->addText($male ?? 0, ['size' => 10], ['alignment' => Jc::CENTER]);
        $table->addCell(1000)->addText('0', ['size' => 10], ['alignment' => Jc::CENTER]);
        $table->addCell(2000)->addText($total ?? 0, ['size' => 10], ['alignment' => Jc::CENTER]);
    }

    // API methods for filters
    public function getPrograms(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $perPage = 20;

        $query = \App\Models\Program::query();
        if ($search) {
            $query->where('nama', 'like', "%{$search}%")->orWhere('kode', 'like', "%{$search}%");
        }

        $total = $query->count();
        $programs = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        return response()->json([
            'results' => $programs->map(fn($p) => ['id' => $p->id, 'text' => $p->kode . ' - ' . $p->nama]),
            'pagination' => ['more' => ($page * $perPage) < $total]
        ]);
    }

    public function getKegiatanByProgram(Request $request)
    {
        $programId = $request->input('program_id');
        $search = $request->input('search');

        $query = Kegiatan::select('programoutcomeoutputactivity_id')->distinct()->with('programOutcomeOutputActivity');

        if ($programId) {
            $query->whereHas('programOutcomeOutputActivity.program_outcome_output.program_outcome.program', 
                fn($q) => $q->where('id', $programId));
        }

        if ($search) {
            $query->whereHas('programOutcomeOutputActivity', 
                fn($q) => $q->where('nama', 'like', "%{$search}%")->orWhere('kode', 'like', "%{$search}%"));
        }

        $activities = $query->get()->map(function ($kegiatan) {
            $activity = $kegiatan->programOutcomeOutputActivity;
            return $activity ? [
                'id' => $activity->id,
                'text' => ($activity->kode ?? '') . ' - ' . ($activity->nama ?? 'N/A')
            ] : null;
        })->filter()->unique('id')->values();

        return response()->json(['results' => $activities, 'pagination' => ['more' => false]]);
    }

    public function getJenisKegiatanByKegiatan(Request $request)
    {
        $activityId = $request->input('activity_id');
        $search = $request->input('search');

        if ($activityId) {
            $jenisIds = Kegiatan::where('programoutcomeoutputactivity_id', $activityId)
                ->distinct()->pluck('jeniskegiatan_id')->filter();
            $query = \App\Models\Jenis_Kegiatan::whereIn('id', $jenisIds);
        } else {
            $query = \App\Models\Jenis_Kegiatan::query();
        }

        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $jenisList = $query->get()->map(fn($j) => ['id' => $j->id, 'text' => $j->nama]);
        return response()->json(['results' => $jenisList, 'pagination' => ['more' => false]]);
    }
}