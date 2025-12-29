<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Export\BTOR;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\SimpleType\LineSpacingRule;
use PhpOffice\PhpWord\Writer\Word2007\Element\ParagraphAlignment;

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

        // Use pdf-export.blade.php with inline CSS (most reliable for DomPDF)
        $pdf = Pdf::loadView('tr.btor.pdf-export', compact('kegiatan', 'viewPath'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isRemoteEnabled' => true,
                'encoding' => 'UTF-8',
            ]);

        $filename = 'BTOR_' . $kegiatan->id . '_' . date('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportDocx($id)
    {
        $tmpDoc = null;
        try {
            // Load kegiatan
            $kegiatan = BTOR::getData($id);
            
            // CRITICAL FIX #1: Ensure relationships are loaded
            $this->ensureRelationshipsLoaded($kegiatan);
            
            // Validate export is possible
            $validationErrors = $this->validateKegiatanForExport($kegiatan);
            if (!empty($validationErrors)) {
                Log::warning('Kegiatan validation issues', [
                    'kegiatan_id' => $id,
                    'errors' => $validationErrors
                ]);
                // Continue anyway, but with defaults for missing data
            }

            // Create PHPWord document
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            // ✅ ADD HEADER (repeats on every page)
            $header = $section->addHeader();

            $section = $phpWord->addSection([
                // Body content uses these margins
                'marginTop' => 1417,       // 2.5 cm
                'marginBottom' => 1417,    // 2.5 cm
                'marginLeft' => 1417,      // 2.5 cm
                'marginRight' => 1417,     // 2.5 cm
                
                // Header has its own space
                'headerHeight' => 283,    // 2 cm for header image
                'headerDistance' => 283,   // 0.5 cm gap after header
                
                // Footer has its own space
                'footerHeight' => 567,     // 1 cm for footer
                'footerDistance' => 283,   // 0.5 cm gap before footer

                // 'headerFromTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1), // 1.5 cm dari tepi atas

                // // 3. MARGIN FOOTER (Posisi Footer dari tepi bawah kertas)
                // // Harus lebih kecil dari marginBottom
                // 'footerFromBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
            ]);

            
            
            $imagePath = public_path('images/uploads/header.png');
            if (file_exists($imagePath)) {
                $header->addImage($imagePath, [
                    'width' => 395,      // Width in pixels
                    'height' => 38,         // Height in pixels 
                    'alignment' => 'center'
                ]);
            } else {
                // Fallback text if image not found
                $header->addText('YAYASAN IDEP', ['bold' => true, 'size' => 14]);
            }

            // ✅ ADD FOOTER (repeats on every page)
            $footerStyle = $footerBodyStyle = new \PhpOffice\PhpWord\Style\Font();

            $footerStyle = ['bold' => true, 'name' => 'Tahoma', 'size' => 8, 'color' => '0D654D'];
            $footerBodyStyle = ['name' => 'Tahoma', 'size' => 8, 'color' => '0F7001'];
            $pStyle = [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'spaceBefore' => 0.25,
                'spaceAfter' => 0.25,
                'lineHeight' => 1.0
            ];

            $footerLineStyle = [
                'borderBottomStyle' => 'thinThickMediumGap', 
                'borderBottomSize'  => 25,
                'borderBottomColor' => '000000',
                'spaceBefore'    => 1,
                'lineHeight' => 1.0,
                'spaceAfter'     => 1,
                'alignment'      => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
            ];

            $footer = $section->addFooter();

            $footer->addText('', [], $footerLineStyle);
            $footer->addPreserveText('Yayasan IDEP Selaras Alam', $footerStyle, $pStyle);
            $footer->addPreserveText('Office &amp; Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali, Indonesia', $footerBodyStyle, $pStyle);
            $footer->addPreserveText(' Telp/Fax : +62-361-908-2983 / +62-812 4658 5137', $footerBodyStyle, $pStyle);
            $footer->addPreserveText('Dihasilkan pada: ' . date('d-m-Y H:i:s'), $footerBodyStyle, $pStyle);

            // Add document content
            $this->addDocxHeader($section, $kegiatan);
            $this->addDocxContent($section, $kegiatan);

            // Generate temp file
            $tmpDoc = tempnam(sys_get_temp_dir(), 'btor_' . time() . '_');
            if (!$tmpDoc) {
                throw new \Exception('Tidak dapat membuat file temporary');
            }

            // Save to temp file
            $phpWord->save($tmpDoc, 'Word2007');

            // Validate temp file was created
            if (!file_exists($tmpDoc) || filesize($tmpDoc) === 0) {
                throw new \Exception('File DOCX tidak terbuat dengan benar');
            }

            $filename = $this->generateFilename($kegiatan, 'docx');

            // CRITICAL FIX #2: Clean output buffers before download
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            // Return download response
            return response()->download($tmpDoc, $filename)
                ->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('BTOR DOCX Export Error', [
                'kegiatan_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Clean up temp file if it exists
            if ($tmpDoc && file_exists($tmpDoc)) {
                @unlink($tmpDoc);
            }

            return redirect()->back()->with('error', 'Gagal mengekspor laporan DOCX: ' . $e->getMessage());
        }
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

        // Use pdf-export-bulk.blade.php with inline CSS (most reliable for DomPDF)
        $pdf = Pdf::loadView('tr.btor.pdf-export-bulk', compact('kegiatanList'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isRemoteEnabled' => true,
                'encoding' => 'UTF-8',
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
            return redirect()->back()->with('error', 'Pilih minimal 1 laporan untuk diekspor');
        }

        if (count($ids) > 20) {
            return redirect()->back()->with('error', 'Maksimal 20 laporan sekaligus');
        }

        $tmpDoc = null;
        try {
            $phpWord = new PhpWord();
            $sections = [];

            // Create all sections first
            foreach ($ids as $id) {
                $sections[] = $phpWord->addSection();
            }

            // Then populate each section
            foreach ($ids as $index => $id) {
                $kegiatan = BTOR::getData($id);
                $this->ensureRelationshipsLoaded($kegiatan);
                
                $section = $sections[$index];  // Use array index directly
                
                $this->addDocxHeader($section, $kegiatan);
                $this->addDocxContent($section, $kegiatan);
            }

            $tmpDoc = tempnam(sys_get_temp_dir(), 'btor_bulk_' . time() . '_');
            $phpWord->save($tmpDoc, 'Word2007');

            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            $filename = 'BTOR_Bulk_' . count($ids) . '_Reports_' . date('YmdHis') . '.docx';
            return response()->download($tmpDoc, $filename)
                ->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            \Log::error('BTOR Bulk DOCX Export Error', [
                'ids' => $ids,
                'count' => count($ids),
                'error' => $e->getMessage()
            ]);

            if ($tmpDoc && file_exists($tmpDoc)) {
                @unlink($tmpDoc);
            }

            return redirect()->back()->with('error', 'Gagal mengekspor bulk laporan: ' . $e->getMessage());
        }
    }

    private function addDocxHeader($section, $kegiatan)
    {
        $reportTitleStyle = ['bold' => true, 'name' => 'Tahoma', 'size' => 10, 'color' => '000000'];
        $hBodyStyle = ['name' => 'Tahoma', 'size' => 10, 'color' => '000000'];

        $hStyle = [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
            'spaceBefore' => 0.25,
            'spaceAfter' => 0.25,
            'lineHeight' => 1.0,
        ];

        $borderStyle = [
            'lineHeight' => 1.5,
            'borderTopSize'  => 10, // Pakai Bottom untuk di bawah teks
            'borderTopColor' => '000000',
            'borderTopStyle' => 'single', // Style tebal-tipis yang tadi
            'spaceAfter'        => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6),
            'spaceBefore'        => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6),
            'alignment'         => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
        ];
        
        $section->addPreserveText('BACK TO OFFICE REPORT', $reportTitleStyle, ['alignment' => Jc::CENTER]);

        // Basic information with proper null handling and encoding
        $program = $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program;
        
        $section->addText('Departemen : Program', $hBodyStyle, $hStyle) ;
        $section->addText('Program : ' . $this->safeValue($program?->nama), $hBodyStyle, $hStyle);
        $section->addText('Nama Kegiatan : ' . $this->safeValue($kegiatan->programOutcomeOutputActivity?->nama), $hBodyStyle, $hStyle);
        $section->addText('Kode budget : ' . $this->safeValue($kegiatan->programOutcomeOutputActivity?->kode), $hBodyStyle, $hStyle);

        // Penulis (authors)
        $penulis = $kegiatan->kegiatan_penulis && $kegiatan->kegiatan_penulis->count() > 0
            ? $kegiatan->kegiatan_penulis->map(fn($p) => $this->safeValue($p->user?->nama))->filter()->implode(', ')
            : '-';
        $section->addText('Penulis laporan : ' . $penulis, $hBodyStyle, $hStyle);

        // Jabatan (positions)
        $jabatan = $kegiatan->kegiatan_penulis && $kegiatan->kegiatan_penulis->count() > 0
            ? $kegiatan->kegiatan_penulis->map(fn($p) => $this->safeValue($p->peran?->nama))->filter()->implode(', ')
            : '-';
        $section->addText('Jabatan : ' . $jabatan, $hBodyStyle, $hStyle);
        
        $section->addText('', [], $borderStyle);
        
    }

    /**
     * Add DOCX content sections
     */

    /**
     * Add DOCX content with proper encoding
     * FIXES: Character encoding, null safety, consistent formatting
     */
    private function addDocxContent($section, $kegiatan)
    {

        // A. Latar Belakang
        $section->addText('A. Latar Belakang Kegiatan', ['bold' => true, 'size' => 12]);
        $section->addText($this->safeText($kegiatan->deskripsilatarbelakang), ['size' => 11]);
        $section->addTextBreak(1);

        // B. Tujuan
        $section->addText('B. Tujuan Kegiatan', ['bold' => true, 'size' => 12]);
        $section->addText($this->safeText($kegiatan->deskripsitujuan), ['size' => 11]);
        $section->addTextBreak(1);

        // C. Detail Kegiatan
        $section->addText('C. Detail Kegiatan', ['bold' => true, 'size' => 12]);
        
        // Date formatting
        $tanggalMulai = $kegiatan->tanggalmulai 
            ? Carbon::parse($kegiatan->tanggalmulai)->locale('id')->isoFormat('dddd, D MMMM Y')
            : 'Tidak ditentukan';
        $tanggalSelesai = $kegiatan->tanggalselesai
            ? Carbon::parse($kegiatan->tanggalselesai)->locale('id')->isoFormat('dddd, D MMMM Y')
            : 'Tidak ditentukan';
        
        $dateText = 'Hari, tanggal : ' . $tanggalMulai;
        if ($kegiatan->tanggalmulai && $kegiatan->tanggalselesai && $kegiatan->tanggalmulai != $kegiatan->tanggalselesai) {
            $dateText .= ' - ' . $tanggalSelesai;
            if ($kegiatan->getDurationInDays()) {
                $dateText .= ' (' . $kegiatan->getDurationInDays() . ' hari)';
            }
        }
        $section->addText($dateText, ['size' => 11]);

        // Location
        $lokasiList = $kegiatan->lokasi && $kegiatan->lokasi->count() > 0
            ? $kegiatan->lokasi->map(fn($l) => $this->safeValue($l->lokasi))->toArray()
            : [];
        
        $section->addText('Tempat : ' . (count($lokasiList) > 0 ? implode(', ', $lokasiList) : '-'), ['size' => 11]);

        // Partners
        if ($kegiatan->mitra && $kegiatan->mitra->count() > 0) {
            $section->addText('Pihak yang terlibat :', ['size' => 11]);
            foreach ($kegiatan->mitra as $index => $mitra) {
                $section->addText(($index + 1) . '. ' . $this->safeValue($mitra->nama), ['size' => 11]);
            }
        }
        
        $section->addTextBreak(1);

        // Location Table
        if ($kegiatan->lokasi && $kegiatan->lokasi->count() > 0) {
            $section->addText('Tabel Lokasi', ['bold' => true, 'size' => 11]);
            $this->addLocationTable($section, $kegiatan);
            $section->addTextBreak(1);
        }

        // D. Hasil Kegiatan - Beneficiaries
        $section->addText('D. Hasil Kegiatan', ['bold' => true, 'size' => 12]);
        $section->addText('a. Jumlah partisipan yang terlibat dan disagregat', ['size' => 11]);
        
        if ($kegiatan->penerimamanfaattotal > 0) {
            $this->addBeneficiariesTable($section, $kegiatan);
        } else {
            $section->addText('Tidak ada data penerima manfaat', ['size' => 11]);
        }
        
        $section->addText('b. Hasil pertemuan', ['size' => 11]);
        $section->addText($this->safeText($kegiatan->deskripsikeluaran), ['size' => 11]);
        $section->addTextBreak(1);

        // Get specific data based on jenis kegiatan
        $specificData = $this->getSpecificKegiatanData($kegiatan);

        // E. Tantangan dan Solusi
        $section->addText('E. Tantangan dan Solusi', ['bold' => true, 'size' => 12]);
        $section->addText($this->safeText($specificData['kendala'] ?? 'Tidak ada data tantangan.'), ['size' => 11]);
        $section->addTextBreak(1);

        // F. Isu yang Perlu Diperhatikan
        $section->addText('F. Isu yang Perlu Diperhatikan / Rekomendasi', ['bold' => true, 'size' => 12]);
        $section->addText($this->safeText($specificData['isu'] ?? 'Tidak ada data isu.'), ['size' => 11]);
        $section->addTextBreak(1);

        // G. Pembelajaran
        $section->addText('G. Pembelajaran', ['bold' => true, 'size' => 12]);
        $section->addText($this->safeText($specificData['pembelajaran'] ?? 'Tidak ada data pembelajaran.'), ['size' => 11]);
        $section->addTextBreak(1);

        // H. Dokumen Pendukung
        $section->addText('H. Dokumen Pendukung', ['bold' => true, 'size' => 12]);
        $dokumen = $kegiatan->getDokumenPendukung();
        $media = $kegiatan->getMediaPendukung();
        
        if (($dokumen && $dokumen->count() > 0) || ($media && $media->count() > 0)) {
            if ($dokumen && $dokumen->count() > 0) {
                $section->addText('Dokumen (' . $dokumen->count() . ')', ['size' => 11]);
                foreach ($dokumen as $doc) {
                    $section->addText('- ' . $this->safeValue($doc->name), ['size' => 11]);
                }
            }
            if ($media && $media->count() > 0) {
                $section->addText('Media Pendukung (' . $media->count() . ')', ['size' => 11]);
                foreach ($media as $item) {
                    $section->addText('- ' . $this->safeValue($item->name), ['size' => 11]);
                }
            }
        } else {
            $section->addText('Tidak ada dokumen pendukung.', ['size' => 11]);
        }
        $section->addTextBreak(2);
    }


        /**
     * Get specific data based on jenis kegiatan
     * FIXES: Comprehensive null safety
     */
    private function getSpecificKegiatanData($kegiatan)
    {
        $specificData = null;

        switch ($kegiatan->jenis_kegiatan_id) {
            case 1:
                $specificData = $kegiatan->assessment;
                break;
            case 2:
                $specificData = $kegiatan->sosialisasi;
                break;
            case 3:
                $specificData = $kegiatan->pelatihan;
                break;
            case 4:
                $specificData = $kegiatan->pembelanjaan;
                break;
            case 5:
                $specificData = $kegiatan->pengembangan;
                break;
            case 6:
                $specificData = $kegiatan->kampanye;
                break;
            case 7:
                $specificData = $kegiatan->pemetaan;
                break;
            case 8:
                $specificData = $kegiatan->monitoring;
                break;
            case 9:
                $specificData = $kegiatan->kunjungan;
                break;
            case 10:
                $specificData = $kegiatan->konsultasi;
                break;
            case 11:
                $specificData = $kegiatan->lainnya;
                break;
        }

        // Extract data with proper null handling
        return [
            'kendala' => $specificData?->kendala ?? $specificData?->unggahan ?? 'Tidak ada data tantangan.',
            'isu' => $specificData?->isu ?? 'Tidak ada data isu.',
            'pembelajaran' => $specificData?->pembelajaran ?? 'Tidak ada data pembelajaran.',
        ];
    }

        /**
     * Safely extract and clean text for DOCX
     * FIXES: Character encoding, HTML entities
     */
    private function safeText($text, $default = '-')
    {
        if (empty($text)) {
            return $default;
        }

        return htmlspecialchars(
            strip_tags((string)$text),
            ENT_XML1,
            'UTF-8'
        );
    }

    /**
     * Safely get value with default
     * FIXES: Null coalescing, encoding
     */
    private function safeValue($value, $default = '-')
    {
        if (is_null($value) || $value === '') {
            return $default;
        }

        return htmlspecialchars(
            (string)$value,
            ENT_XML1,
            'UTF-8'
        );
    }
    /**
     * Ensure critical relationships are loaded
     */
    private function ensureRelationshipsLoaded($kegiatan)
    {
        $relationships = [
            'programOutcomeOutputActivity.program_outcome_output.program_outcome.program',
            'lokasi.desa.kecamatan.kabupaten.provinsi',
            'mitra',
            'kegiatan_penulis.user',
            'kegiatan_penulis.peran',
            'assessment',
            'sosialisasi',
            'pelatihan',
            'pembelanjaan',
            'pengembangan',
            'kampanye',
            'pemetaan',
            'monitoring',
            'kunjungan',
            'konsultasi',
            'lainnya'
        ];

        foreach ($relationships as $relation) {
            if (!$kegiatan->relationLoaded($relation)) {
                try {
                    $kegiatan->load($relation);
                } catch (\Exception $e) {
                    Log::warning("Failed to load relationship: $relation", [
                        'kegiatan_id' => $kegiatan->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }

    /**
     * Validate kegiatan has required relationships
     */
    private function validateKegiatanForExport($kegiatan)
    {
        $errors = [];

        if (!$kegiatan->programOutcomeOutputActivity) {
            $errors[] = 'Kegiatan tidak memiliki activity terhubung';
        }

        if (!$kegiatan->lokasi || $kegiatan->lokasi->isEmpty()) {
            $errors[] = 'Kegiatan tidak memiliki lokasi';
        }

        if (!$kegiatan->kegiatan_penulis || $kegiatan->kegiatan_penulis->isEmpty()) {
            $errors[] = 'Kegiatan tidak memiliki penulis';
        }

        return $errors;
    }

    /**
     * Generate download filename
     */

    // Then use this method:
    private function generateFilename($kegiatan, $format = 'docx')
    {
        $nama = Str::slug($kegiatan->programOutcomeOutputActivity?->nama ?? 'kegiatan');
        return "BTOR_{$nama}_{$kegiatan->id}_" . date('YmdHis') . ".{$format}";
    }


    /**
     * Add location table to DOCX
     * FIXES: Proper null handling, encoding
     */
    private function addLocationTable($section, $kegiatan)
    {
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'width' => 9000,
            'unit' => 'pct'
        ];
        
        $table = $section->addTable($tableStyle);

        // Header row
        $headerCells = ['No', 'Lokasi', 'Desa', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Koordinat'];
        $row = $table->addRow();
        foreach ($headerCells as $cell) {
            $row->addCell(1500, ['bgColor' => 'f0f0f0'])->addText(
                $cell,
                ['bold' => true, 'size' => 9],
                ['alignment' => new Jc(Jc::CENTER)]
            );
        }

        // Data rows
        foreach ($kegiatan->lokasi as $index => $lokasi) {
            $row = $table->addRow();
            
            $row->addCell(500)->addText(
                (string)($index + 1),
                ['size' => 9],
                ['alignment' => new Jc(Jc::CENTER)]
            );
            
            $row->addCell(1500)->addText($this->safeValue($lokasi->lokasi), ['size' => 9]);
            $row->addCell(1500)->addText($this->safeValue($lokasi->desa?->nama), ['size' => 9]);
            $row->addCell(1500)->addText($this->safeValue($lokasi->desa?->kecamatan?->nama), ['size' => 9]);
            $row->addCell(1500)->addText($this->safeValue($lokasi->desa?->kecamatan?->kabupaten?->nama), ['size' => 9]);
            $row->addCell(1500)->addText($this->safeValue($lokasi->desa?->kecamatan?->kabupaten?->provinsi?->nama), ['size' => 9]);
            
            $koordinat = '-';
            if ($lokasi->lat && $lokasi->long) {
                $koordinat = number_format($lokasi->lat, 6) . ', ' . number_format($lokasi->long, 6);
            }
            $row->addCell(1500)->addText(
                $koordinat,
                ['size' => 9],
                ['alignment' => new Jc(Jc::CENTER)]
            );
        }
    }

    /**
     * Add beneficiaries table
     * FIXES: Proper null handling
     */
    private function addBeneficiariesTable($section, $kegiatan)
    {
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'width' => 9000,
            'unit' => 'pct'
        ];
        
        $table = $section->addTable($tableStyle);

        // Header
        $headers = ['Kategori', 'Perempuan', 'Laki-laki', 'Sub Total'];
        $row = $table->addRow();
        foreach ($headers as $header) {
            $row->addCell(2500, ['bgColor' => 'f0f0f0'])->addText(
                $header,
                ['bold' => true, 'size' => 10],
                ['alignment' => new Jc(Jc::CENTER)]
            );
        }

        // Data rows
        $this->addTableRow($table, 'Dewasa (25-59 tahun)', 
            $kegiatan->penerimamanfaatdewasaperempuan,
            $kegiatan->penerimamanfaatdewasalakilaki,
            $kegiatan->penerimamanfaatdewasatotal);

        $this->addTableRow($table, 'Lansia (60+ tahun)', 
            $kegiatan->penerimamanfaatlansiaperempuan,
            $kegiatan->penerimamanfaatlansialakilaki,
            $kegiatan->penerimamanfaatlansiatotal);

        $this->addTableRow($table, 'Remaja (18-24 tahun)', 
            $kegiatan->penerimamanfaatremajaperempuan,
            $kegiatan->penerimamanfaatremajalakilaki,
            $kegiatan->penerimamanfaatremajatotal);

        $this->addTableRow($table, 'Anak (< 18 tahun)', 
            $kegiatan->penerimamanfaatanakperempuan,
            $kegiatan->penerimamanfaatanaklakilaki,
            $kegiatan->penerimamanfaatanaktotal);

        // Total
        $row = $table->addRow();
        $row->addCell(2500, ['bgColor' => 'f0f0f0'])->addText(
            'TOTAL',
            ['bold' => true, 'size' => 10]
        );
        $row->addCell(2500, ['bgColor' => 'f0f0f0'])->addText(
            (string)($kegiatan->penerimamanfaatperempuantotal ?? 0),
            ['bold' => true, 'size' => 10],
            ['alignment' => new Jc(Jc::CENTER)]
        );
        $row->addCell(2500, ['bgColor' => 'f0f0f0'])->addText(
            (string)($kegiatan->penerimamanfaatlakilakitotal ?? 0),
            ['bold' => true, 'size' => 10],
            ['alignment' => new Jc(Jc::CENTER)]
        );
        $row->addCell(2500, ['bgColor' => 'f0f0f0'])->addText(
            (string)($kegiatan->penerimamanfaattotal ?? 0),
            ['bold' => true, 'size' => 10],
            ['alignment' => new Jc(Jc::CENTER)]
        );
    }

    /**
     * Add table row with proper formatting
     */
    private function addTableRow($table, $label, $female, $male, $total)
    {
        $row = $table->addRow();
        
        $row->addCell(2500)->addText($this->safeValue($label), ['size' => 10]);
        
        $row->addCell(2500)->addText(
            (string)($female ?? 0),
            ['size' => 10],
            ['alignment' => new Jc(Jc::CENTER)]
        );
        
        $row->addCell(2500)->addText(
            (string)($male ?? 0),
            ['size' => 10],
            ['alignment' => new Jc(Jc::CENTER)]
        );
        
        $row->addCell(2500)->addText(
            (string)($total ?? 0),
            ['size' => 10],
            ['alignment' => new Jc(Jc::CENTER)]
        );
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