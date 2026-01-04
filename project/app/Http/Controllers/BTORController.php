<?php
namespace App\Http\Controllers;

use App\Models\Export\BTOR;
use App\Models\Jenis_Kegiatan;
use App\Models\Kegiatan;
use App\Models\Program;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Helper\Html;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\SimpleType\LineSpacingRule;
use PhpOffice\PhpWord\Style\Font;
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
        // 1. Get Data
        $kegiatan = BTOR::getData($id);

        if (!$kegiatan) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        // 2. Ensure Relations & Specific Data are loaded (Same logic as Bulk)
        $this->ensureRelationshipsLoaded($kegiatan);

        // Pre-calculate specific data (Challenges, Issues, etc.)
        $specificData = $this->getSpecificKegiatanData($kegiatan);

        // 3. Wrap in 'dataList' structure 
        // This allows us to reuse the 'pdf-export-bulk' view perfectly
        $dataList = [
            (object) [
                'kegiatan' => $kegiatan,
                'specific' => $specificData
            ]
        ];

        // 4. Load the Shared View
        $pdf = Pdf::loadView('tr.btor.pdf-export-bulk', compact('dataList'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isRemoteEnabled' => true,
                'dpi' => 96,
                'defaultFont' => 'Figtree'
            ]);

        // 5. Generate Filename using your helper
        // Note: generateFilename includes 'BTOR_' prefix and extension
        $filename = $this->generateFilename($kegiatan, 'pdf');

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

            $h1Style = ['bold' => true, 'name' => 'Figtree', 'size' => 10, 'color' => '000000'];
            $h2Style = ['name' => 'Figtree', 'size' => 10, 'color' => '000000'];
            $normalStyle = ['name' => 'Figtree', 'size' => 10, 'color' => '000000'];

            $h1ParagraphStyle = ['alignment' => 'both', 'spaceAfter' => 240];
            $h2ParagraphStyle = ['alignment' => 'both', 'spaceAfter' => 120];
            $normalParagraphStyle = ['alignment' => 'both', 'spaceAfter' => 120];
            
            // Create PHPWord document
            $phpWord = new PhpWord();
            $phpWord->addTitleStyle(1, $h1Style, ['spaceAfter' => 120, 'spaceBefore' => 240]);
            $phpWord->addTitleStyle(2, $h2Style, ['spaceAfter' => 120, 'spaceBefore' => 240]);

            $phpWord->setDefaultFontName('Figtree');
            $phpWord->setDefaultFontSize(10);


            // $section = $phpWord->addSection();


            $section = $phpWord->addSection([
                // Body content uses these margins
                'marginTop' => 1417,       // 2.5 cm
                'marginBottom' => 1417,
                'marginLeft' => 1417,
                'marginRight' => 1417,
                
                'headerHeight' => 283,
                'headerDistance' => 283,
                
                'footerHeight' => 567,
                'footerDistance' => 283,
            ]);

            $header = $section->addHeader();
            $imagePath = public_path('images/uploads/header.png');
            if (file_exists($imagePath)) {
                $header->addImage($imagePath, [
                    'width' => 395,
                    'height' => 38,
                    'alignment' => 'center'
                ]);
            } else {
                // Fallback text if image not found
                $header->addText('YAYASAN IDEP', ['bold' => true, 'size' => 14]);
            }

            // ✅ ADD FOOTER (repeats on every page)
            $footerStyle = $footerBodyStyle = new \PhpOffice\PhpWord\Style\Font();

            $footerStyle = ['bold' => true, 'name' => 'Figtree', 'size' => 8, 'color' => '0D654D'];
            $footerBodyStyle = ['name' => 'Figtree', 'size' => 8, 'color' => '0F7001'];
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

    // public function exportBulkPdf(Request $request)
    // {
    //     $ids = is_string($request->input('ids')) 
    //         ? array_filter(explode(',', $request->input('ids'))) 
    //         : $request->input('ids', []);

    //     if (empty($ids)) {
    //         return back()->with('error', 'Pilih minimal 1 laporan untuk diekspor');
    //     }

    //     $kegiatanList = collect($ids)->map(function ($id) {
    //         $kegiatan = BTOR::getData($id);
    //         return [
    //             'kegiatan' => $kegiatan,
    //             'viewPath' => BTOR::getViewPath($kegiatan->jeniskegiatan_id)
    //         ];
    //     });

    //     // Use pdf-export-bulk.blade.php with inline CSS (most reliable for DomPDF)
    //     $pdf = Pdf::loadView('tr.btor.pdf-export-bulk', compact('kegiatanList'))
    //         ->setPaper('a4', 'portrait')
    //         ->setOption([
    //             'isRemoteEnabled' => true,
    //             'encoding' => 'UTF-8',
    //         ]);

    //     $filename = 'BTOR_Bulk_' . count($ids) . '_Reports_' . date('Ymd_His') . '.pdf';
    //     return $pdf->download($filename);
    // }

    public function exportBulkPdf(Request $request)
    {
        // 1. Sanitize IDs
        $ids = is_string($request->input('ids'))
            ? array_filter(explode(',', $request->input('ids')))
            : $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal 1 laporan untuk diekspor');
        }

        // 2. Prepare Data Collection
        $dataList = [];

        foreach ($ids as $id) {
            // Use your existing getData model method
            $kegiatan = BTOR::getData($id);

            // SAFETY CHECK: If ID is invalid/deleted, skip it to prevent "on null" error
            if (!$kegiatan) {
                continue;
            }

            // Ensure relations are loaded (using your existing helper)
            $this->ensureRelationshipsLoaded($kegiatan);

            // Pre-calculate the "Specific Data" (Challenges, Issues, Lessons)
            // We reuse the private helper logic you already wrote for DOCX
            $specificData = $this->getSpecificKegiatanData($kegiatan);

            // Add to list
            $dataList[] = (object) [
                'kegiatan' => $kegiatan,
                'specific' => $specificData
            ];
        }

        if (empty($dataList)) {
            return back()->with('error', 'Data tidak ditemukan untuk ID yang dipilih.');
        }

        // 3. Load View with 'dataList'
        $pdf = Pdf::loadView('tr.btor.pdf-export-bulk', compact('dataList'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isRemoteEnabled' => true,
                'dpi' => 96,
                'defaultFont' => 'Figtree'
            ]);

        $filename = 'BTOR_Bulk_' . count($dataList) . '_Reports_' . date('Ymd_His') . '.pdf';

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
            $phpWord = new \PhpOffice\PhpWord\PhpWord();

            // --- 1. GLOBAL STYLES (Matches exportDocx) ---
            $phpWord->setDefaultFontName('Figtree');
            $phpWord->setDefaultFontSize(10);

            // Define Heading Styles globally
            $h1Style = ['bold' => true, 'name' => 'Figtree', 'size' => 10, 'color' => '000000'];
            $h2Style = ['name' => 'Figtree', 'size' => 10, 'color' => '000000'];

            // Register Title Styles so addTitle() works correctly in the content
            $phpWord->addTitleStyle(1, $h1Style, ['spaceAfter' => 120, 'spaceBefore' => 240]);
            $phpWord->addTitleStyle(2, $h2Style, ['spaceAfter' => 120, 'spaceBefore' => 240]);

            // --- 2. LOOP THROUGH DATA ---
            foreach ($ids as $id) {
                $kegiatan = BTOR::getData($id);
                // Ensure all relations are loaded prevents lazy loading errors
                $this->ensureRelationshipsLoaded($kegiatan);

                // --- 3. CREATE SECTION (New Page per ID) ---
                // Using exact margins from your single export
                $section = $phpWord->addSection([
                    'marginTop' => 1417,       // 2.5 cm
                    'marginBottom' => 1417,
                    'marginLeft' => 1417,
                    'marginRight' => 1417,
                    'headerHeight' => 283,
                    'headerDistance' => 283,
                    'footerHeight' => 567,
                    'footerDistance' => 283,
                ]);

                // --- 4. ADD HEADER (Per Section) ---
                $header = $section->addHeader();
                $imagePath = public_path('images/uploads/header.png');
                if (file_exists($imagePath)) {
                    $header->addImage($imagePath, [
                        'width' => 395,
                        'height' => 38,
                        'alignment' => 'center'
                    ]);
                } else {
                    $header->addText('YAYASAN IDEP', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
                }

                // --- 5. ADD FOOTER (Per Section) ---
                $footer = $section->addFooter();

                // Footer Styles
                $footerStyle = ['bold' => true, 'name' => 'Figtree', 'size' => 8, 'color' => '0D654D'];
                $footerBodyStyle = ['name' => 'Figtree', 'size' => 8, 'color' => '0F7001'];
                $footerPStyle = [
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

                // Add Footer Content
                $footer->addText('', [], $footerLineStyle);
                $footer->addPreserveText('Yayasan IDEP Selaras Alam', $footerStyle, $footerPStyle);
                $footer->addPreserveText('Office &amp; Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali, Indonesia', $footerBodyStyle, $footerPStyle);
                $footer->addPreserveText(' Telp/Fax : +62-361-908-2983 / +62-812 4658 5137', $footerBodyStyle, $footerPStyle);
                $footer->addPreserveText('Dihasilkan pada: ' . date('d-m-Y H:i:s'), $footerBodyStyle, $footerPStyle);

                // --- 6. ADD CONTENT ---
                // Reusing your existing helper functions
                $this->addDocxHeader($section, $kegiatan);
                $this->addDocxContent($section, $kegiatan);
            }

            // --- 7. SAVE & DOWNLOAD ---
            $tmpDoc = tempnam(sys_get_temp_dir(), 'btor_bulk_' . time() . '_');
            if (!$tmpDoc) {
                throw new \Exception('Tidak dapat membuat file temporary');
            }

            $phpWord->save($tmpDoc, 'Word2007');

            // Clean output buffer to prevent corrupted files
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            $filename = 'BTOR_Bulk_' . count($ids) . '_Reports_' . date('YmdHis') . '.docx';

            return response()->download($tmpDoc, $filename)
                ->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('BTOR Bulk DOCX Export Error', [
                'ids' => $ids,
                'count' => count($ids),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($tmpDoc && file_exists($tmpDoc)) {
                @unlink($tmpDoc);
            }

            return redirect()->back()->with('error', 'Gagal mengekspor bulk laporan: ' . $e->getMessage());
        }
    }

    private function addDocxHeader($section, $kegiatan)
    {
        // --- DEFINISI STYLE ---
        $reportTitleStyle = ['bold' => true, 'name' => 'Figtree', 'size' => 10, 'color' => '000000'];
        $hBodyStyle = ['name' => 'Figtree', 'size' => 10, 'color' => '000000'];
        $labelStyle = array_merge($hBodyStyle, ['bold' => true]);

        $hStyle = [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
            'spaceBefore' => 0.25,
            'spaceAfter' => 0.25,
            'lineHeight' => 1.0,
        ];

        $borderStyle = [
            // 'lineHeight' => 1.5,
            'borderTopSize'  => 10,
            'borderTopColor' => '000000',
            'borderTopStyle' => 'single',
            'spaceAfter'     => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0),
            'spaceBefore'    => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6),
            'alignment'      => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
        ];
        
        $pStyleLeft = [
            'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(2),
            'spaceAfter'  => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(2),
            'alignment'   => \PhpOffice\PhpWord\SimpleType\Jc::START,
        ];

        // --- LOGIKA DATA ---
        $programNama = $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama;
        $kegiatanNama = $kegiatan->programOutcomeOutputActivity?->nama;
        $kodeBudget = $kegiatan->programOutcomeOutputActivity?->kode;

        // Ambil daftar penulis dari relasi kegiatan_penulis
        $penulisList = $kegiatan->kegiatan_penulis ?? collect();

        $penulis = $penulisList->count() > 0
            ? $penulisList->map(fn($p) => $this->safeValue($p->user?->nama))->filter()->implode(', ')
            : '-';
        $jabatan = $penulisList->count() > 0
            ? $penulisList->map(fn($p) => $this->safeValue($p->peran?->nama))->filter()->implode(', ')
            : '-';

        // --- PEMBUATAN TABEL ---

        $table = $section->addTable(['setBorderColor' => 'none']);
        

        // Helper function untuk baris tabel
        $addRow = function($table, $label, $value) use ($labelStyle, $hBodyStyle, $pStyleLeft) {
            $table->addRow();
            // Kolom 1: Label (Bold) - Lebar 3000 twips (~5.3cm)
            $table->addCell(2500)->addText($label, $labelStyle, $pStyleLeft); 
            // Kolom 2: Titik Dua - Lebar 200 twips
            $table->addCell(200)->addText(':', $hBodyStyle, $pStyleLeft); 
            // Kolom 3: Value - Lebar 6000 twips
            $table->addCell(6000)->addText($value, $hBodyStyle, $pStyleLeft); 
        };

        // Eksekusi pengisian tabel
        $addRow($table, 'Departemen', 'Program');
        $addRow($table, 'Program', $this->safeValue($programNama));
        $addRow($table, 'Nama Kegiatan', $this->safeValue($kegiatanNama));
        $addRow($table, 'Kode Budget', $this->safeValue($kodeBudget));
        $addRow($table, 'Penulis Laporan', $penulis);
        $addRow($table, 'Jabatan', $jabatan);

        $section->addText('', [], $borderStyle);
        
    }

    /**
     * Add DOCX content sections
     */

    /**
     * Add DOCX content with proper encoding
     * FIXES: Character encoding, null safety, consistent formatting
     * Isi Dari File Docx Konten Dinamis
     */
    private function addDocxContent($section, $kegiatan)
    {
        // --- DEFINISI STYLE ---
        $h1Style = ['name' => 'Figtree', 'size' => 10, 'color' => '000000', 'bold' => true];
        $h2Style = ['name' => 'Figtree', 'size' => 10, 'color' => '000000'];
        $normalStyle = ['name' => 'Figtree', 'size' => 10, 'color' => '000000'];


        $labelStyle = array_merge($h2Style, ['bold' => true]);

        $pNormalStyle = [
            'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(2),
            'spaceAfter'  => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(2),
            'alignment'   => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
        ];

        $pStyleLeft = [
            'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(2),
            'spaceAfter'  => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(2),
            'alignment'   => \PhpOffice\PhpWord\SimpleType\Jc::START,
        ];
        
        $hStyle = [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
            'spaceBefore' => 0.25,
            'spaceAfter' => 0.25,
            'lineHeight' => 1.0,
        ];


        // A. Latar Belakang
        $section->addTitle('A. Latar Belakang Kegiatan', 1);
        $this->addHtmlToSection($section, $kegiatan->deskripsilatarbelakang);

        // B. Tujuan
        $section->addTitle('B. Tujuan Kegiatan', 1);
        $this->addHtmlToSection($section, $kegiatan->deskripsitujuan);

        // C. Detail Kegiatan
        $section->addTitle('C. Detail Kegiatan', 1);

        // 2. Date Logic - Minimizing potential null errors
        $m = $kegiatan->tanggalmulai ?? null;
        $s = $kegiatan->tanggalselesai ?? null;

        $tanggalMulaiText = $m ? Carbon::parse($m)->locale('id')->isoFormat('dddd, D MMMM Y') : 'Tidak ditentukan';
        $tanggalSelesaiText = $s ? Carbon::parse($s)->locale('id')->isoFormat('dddd, D MMMM Y') : null;

        $dateText = $this->safeValue($tanggalMulaiText);

        // Append end date if it exists and is different from start date
        if ($m && $s && $m != $s) {
            $dateText .= ' - ' . $this->safeValue($tanggalSelesaiText);
            
            // Check if method exists before calling to prevent fatal error
            if (method_exists($kegiatan, 'getDurationInDays') && $kegiatan->getDurationInDays()) {
                $dateText .= ' (' . $kegiatan->getDurationInDays() . ' hari)';
            }
        }

        // 3. Location Logic - Using safeValue inside map
        $lokasiList = ($kegiatan->lokasi && count($kegiatan->lokasi) > 0)
            ? $kegiatan->lokasi->map(fn($l) => $this->safeValue($l->lokasi ?? ''))->filter()->toArray()
            : [];
        $lokasiString = count($lokasiList) > 0 ? implode(', ', $lokasiList) : '-';

        // 4. Partners Logic
        $mitraList = ($kegiatan->mitra && count($kegiatan->mitra) > 0)
            ? $kegiatan->mitra->map(fn($m) => $this->safeValue($m->nama ?? ''))->filter()->toArray()
            : [];
        $mitraString = count($mitraList) > 0 ? implode(', ', $mitraList) : '-';

        // 5. Table Rendering
        $table = $section->addTable(['setBorderColor' => 'none']);

        /**
         * Closure to handle row adding with safety checks
         */
        $addRow = function($table, $label, $value) use ($labelStyle, $h2Style, $pStyleLeft) {
            $table->addRow();
            // Label
            $table->addCell(2500)->addText($label, $labelStyle, $pStyleLeft); 
            // Separator
            $table->addCell(200)->addText(':', $h2Style, $pStyleLeft); 
            // Value (Safe String)
            $table->addCell(6000)->addText($value, $h2Style, $pStyleLeft); 
        };

        // Populate Table
        $addRow($table, 'Hari, Tanggal', $dateText);
        $addRow($table, 'Tempat', $lokasiString);
        $addRow($table, 'Pihak yang terlibat', $mitraString);
        $section->addTextBreak(1);

        // Location Table
        if ($kegiatan->lokasi && $kegiatan->lokasi->count() > 0) {
            $section->addText('Tabel Lokasi', $labelStyle);
            $this->addLocationTable($section, $kegiatan);
        }

        // D. Hasil Kegiatan - Beneficiaries
        $section->addTitle('D. Hasil Kegiatan', 1);
        $section->addText('a. Jumlah partisipan yang terlibat dan disagregat', $labelStyle);
        $section->addText('Silakan mengisi tabel berikut:', $normalStyle, $pNormalStyle);
        
        if ($kegiatan->penerimamanfaattotal > 0) {
            $this->addBeneficiariesTable($section, $kegiatan);
        } else {
            $section->addText('Tidak ada data penerima manfaat', $normalStyle, $pNormalStyle);
        }
        $section->addTextBreak(1);
        $section->addText('b. Hasil pertemuan', $labelStyle);
        $this->addHtmlToSection($section, $kegiatan->deskripsikeluaran);
        $section->addTextBreak(1);

        // Get specific data based on jenis kegiatan
        $specificData = $this->getSpecificKegiatanData($kegiatan);

        // E. Tantangan dan Solusi
        $section->addTitle('E. Tantangan dan Solusi', 1);
        $this->addHtmlToSection($section, $specificData['kendala'] ?? 'Tidak ada data tantangan.');
        $section->addTextBreak(1);

        // F. Isu yang Perlu Diperhatikan
        $section->addTitle('F. Isu yang Perlu Diperhatikan / Rekomendasi', 1);

        $richTextHTML =  $specificData['isu'] ?? '';
        \Log::info($richTextHTML);
        $this->addHtmlToSection($section, $richTextHTML);
        
        // $this->addHtmlToSection($section, $specificData['isu'] ?? 'Tidak ada data isu.');
        $section->addTextBreak(1);

        // G. Pembelajaran
        $section->addTitle('G. Pembelajaran', 1);
        $this->addHtmlToSection($section, $specificData['pembelajaran'] ?? 'Tidak ada data pembelajaran.');
        $section->addTextBreak(1);

        // H. Dokumen Pendukung
        $section->addTitle('H. Dokumen Pendukung', 1);
        $dokumen = $kegiatan->getDokumenPendukung();
        $media = $kegiatan->getMediaPendukung();
        
        if (($dokumen && $dokumen->count() > 0) || ($media && $media->count() > 0)) {
            if ($dokumen && $dokumen->count() > 0) {
                $section->addText('Dokumen (' . $dokumen->count() . ')', $normalStyle, $pNormalStyle);
                foreach ($dokumen as $doc) {
                    $section->addText('- ' . $this->safeValue($doc->name), $normalStyle, $pNormalStyle);
                }
            }
            if ($media && $media->count() > 0) {
                $section->addText('Media Pendukung (' . $media->count() . ')', $normalStyle, $pNormalStyle);
                foreach ($media as $item) {
                    $section->addText('- ' . $this->safeValue($item->name), $normalStyle, $pNormalStyle);
                }
            }
        } else {
            $section->addText('Tidak ada dokumen pendukung.', $normalStyle, $pNormalStyle);
        }
        $section->addTextBreak(2);
    }

    /**
     * Get specific data based on jenis kegiatan
     * REVIEW: Refactored field mapping to use correct prefixed fields (e.g., assessmentkendala) 
     * for Word export extraction.
     */
    private function getSpecificKegiatanData($kegiatan)
    {
        $data = [
            'kendala' => null,
            'isu' => null,
            'pembelajaran' => null,
        ];

        // Specific fields mapping based on jeniskegiatan_id
        // This ensures the generic 'kendala', 'isu', and 'pembelajaran' keys
        // used in the export logic are correctly populated from model-specific fields.
        switch ($kegiatan->jeniskegiatan_id) {
            case 1: // Assessment
                $data['kendala'] = $kegiatan->assessment?->assessmentkendala;
                $data['isu'] = $kegiatan->assessment?->assessmentisu;
                $data['pembelajaran'] = $kegiatan->assessment?->assessmentpembelajaran;
                break;
            case 2: // Sosialisasi
                $data['kendala'] = $kegiatan->sosialisasi?->sosialisasikendala;
                $data['isu'] = $kegiatan->sosialisasi?->sosialisasiisu;
                $data['pembelajaran'] = $kegiatan->sosialisasi?->sosialisasipembelajaran;
                break;
            case 3: // Pelatihan
                $data['kendala'] = $kegiatan->pelatihan?->pelatihankendala;
                $data['isu'] = $kegiatan->pelatihan?->pelatihanisu;
                $data['pembelajaran'] = $kegiatan->pelatihan?->pelatihanpembelajaran;
                break;
            case 4: // Pembelanjaan
                $data['kendala'] = $kegiatan->pembelanjaan?->pembelanjaankendala;
                $data['isu'] = $kegiatan->pembelanjaan?->pembelanjaanisu;
                $data['pembelajaran'] = $kegiatan->pembelanjaan?->pembelanjaanpembelajaran;
                break;
            case 5: // Pengembangan
                $data['kendala'] = $kegiatan->pengembangan?->pengembangankendala;
                $data['isu'] = $kegiatan->pengembangan?->pengembanganisu;
                $data['pembelajaran'] = $kegiatan->pengembangan?->pengembanganpembelajaran;
                break;
            case 6: // Kampanye
                $data['kendala'] = $kegiatan->kampanye?->kampanyekendala;
                $data['isu'] = $kegiatan->kampanye?->kampanyeisu;
                $data['pembelajaran'] = $kegiatan->kampanye?->kampanyepembelajaran;
                break;
            case 7: // Pemetaan
                $data['kendala'] = $kegiatan->pemetaan?->pemetaankendala;
                $data['isu'] = $kegiatan->pemetaan?->pemetaanisu;
                $data['pembelajaran'] = $kegiatan->pemetaan?->pemetaanpembelajaran;
                break;
            case 8: // Monitoring
                $data['kendala'] = $kegiatan->monitoring?->monitoringkendala;
                $data['isu'] = $kegiatan->monitoring?->monitoringisu;
                $data['pembelajaran'] = $kegiatan->monitoring?->monitoringpembelajaran;
                break;
            case 9: // Kunjungan
                $data['kendala'] = $kegiatan->kunjungan?->kunjungankendala;
                $data['isu'] = $kegiatan->kunjungan?->kunjunganisu;
                $data['pembelajaran'] = $kegiatan->kunjungan?->kunjunganpembelajaran;
                break;
            case 10: // Konsultasi
                $data['kendala'] = $kegiatan->konsultasi?->konsultasikendala;
                $data['isu'] = $kegiatan->konsultasi?->konsultasiisu;
                $data['pembelajaran'] = $kegiatan->konsultasi?->konsultasipembelajaran;
                break;
            case 11: // Lainnya
                $data['kendala'] = $kegiatan->lainnya?->lainnyakendala;
                $data['isu'] = $kegiatan->lainnya?->lainnyaisu;
                $data['pembelajaran'] = $kegiatan->lainnya?->lainnyapembelajaran;
                break;
        }

        return [
            'kendala'      => $data['kendala'] ?: 'Tidak ada data tantangan.',
            'isu'          => $data['isu'] ?: 'Tidak ada data isu.',
            'pembelajaran' => $data['pembelajaran'] ?: 'Tidak ada data pembelajaran.',
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

        // Decode HTML entities (like &nbsp;) to actual characters
        $decoded = html_entity_decode((string)$text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Remove HTML tags
        $stripped = strip_tags($decoded);

        // Encode for XML output
        return htmlspecialchars(
            $stripped,
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
     * Add HTML content to DOCX section
     * Converts HTML from rich text editors (Summernote) to Word formatting
     * Preserves paragraphs, line breaks, tables, and text formatting
     */
    private function addHtmlToSection($section, $html, $fontStyle = [], $paragraphStyle = [])
    {
        if (empty($html)) {
            return;
        }
        
        // Decode HTML entities first (e.g., &nbsp; to actual space)
        $decoded = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Clean and sanitize HTML to make it XML-compatible
        $cleaned = $this->sanitizeHtmlForPhpWord($decoded);
        
        try {
            // Use PHPWord's HTML parser to add HTML content
            // This preserves <p>, <br>, <table>, <b>, <i>, <ul>, <ol>, etc.
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $cleaned, false, false);
        } catch (\Exception $e) {
            // Fallback: if HTML parsing fails, add as plain text
            Log::warning('HTML parsing failed in DOCX export, using plain text fallback', [
                'error' => $e->getMessage(),
                'html_preview' => substr($cleaned, 0, 200)
            ]);
            
            // Use default styles if not provided
            if (empty($fontStyle)) {
                $fontStyle = ['name' => 'Figtree', 'size' => 10];
            }
            if (empty($paragraphStyle)) {
                $paragraphStyle = [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
                    'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(2),
                    'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(2),
                ];
            }
            
            $section->addText($this->safeText($html), $fontStyle, $paragraphStyle);
        }
    }

    /**
     * Sanitize HTML to make it compatible with PHPWord's XML parser
     * Fixes common issues from Google Docs and Summernote editors
     */
    private function sanitizeHtmlForPhpWord($html)
    {
        if (empty($html)) {
            return '';
        }

        // Remove problematic Google Docs metadata
        $html = preg_replace('/\<span[^\>]*id=\"docs-internal-guid[^\"]*\"[^\>]*\>\<\/span\>/i', '', $html);

        // Fix critical self-closing tags to XML format
        $selfClosingTags = ['br', 'hr', 'img', 'col'];
        foreach ($selfClosingTags as $tag) {
            $html = preg_replace('/\<' . $tag . '(\s+[^\>\/]*)?\s*\>/i', '\<' . $tag . '$1 /\>', $html);
        }

        // Remove colgroup tags - they cause XML parsing issues
        $html = preg_replace('/\<\/?colgroup[^\>]*\>/i', '', $html);

        return $html;
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
        // Width 5000 with unit 'pct' means 100% of the page width (within margins)
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'width' => 5000,
            'unit' => 'pct',
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
        ];

        $table = $section->addTable($tableStyle);

        // Header Style
        $headerFontStyle = ['bold' => true, 'size' => 9, 'color' => 'FFFFFF']; // White Text
        $headerParagraphStyle = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceBefore' => 120];
        $headerCellStyles = ['bgColor' => '385623', 'valign' => 'center'];

        // Header row
        $headerCells = ['No', 'Lokasi', 'Desa', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Koordinat'];
        $row = $table->addRow();
        foreach ($headerCells as $cell) {
            // We define specific widths for columns so they don't look uneven
            // Total should roughly balance out; PHPWord handles the 'pct' math.
            $width = ($cell === 'No') ? 500 : 1000;
            $row->addCell($width, $headerCellStyles)->addText($cell, $headerFontStyle, $headerParagraphStyle);
        }

        // Data rows
        foreach ($kegiatan->lokasi as $index => $lokasi) {
            $row = $table->addRow();

            // No
            $row->addCell(500)->addText((string)($index + 1), ['size' => 10], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

            // Use safeValue and optional chaining for all location data
            $row->addCell(1000)->addText($this->safeValue($lokasi->lokasi), ['size' => 10]);
            $row->addCell(1000)->addText($this->safeValue($lokasi->desa?->nama), ['size' => 10]);
            $row->addCell(1000)->addText($this->safeValue($lokasi->desa?->kecamatan?->nama), ['size' => 10]);
            $row->addCell(1000)->addText($this->safeValue($lokasi->desa?->kecamatan?->kabupaten?->nama), ['size' => 10]);
            $row->addCell(1000)->addText($this->safeValue($lokasi->desa?->kecamatan?->kabupaten?->provinsi?->nama), ['size' => 10]);

            // Coordinate Logic
            $koordinat = '-';
            if (!empty($lokasi->lat) && !empty($lokasi->long)) {
                $koordinat = number_format($lokasi->lat, 8) . ', ' . number_format($lokasi->long, 8);
            }

            $row->addCell(1500)->addText(
                $koordinat,
                ['size' => 10],
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
            );
        }
    }

    /**
     * Add beneficiaries table
     * FIXES: Proper null handling
     */
    private function addBeneficiariesTable($section, $kegiatan)
    {
        $hBodyStyle = ['name' => 'Figtree', 'size' => 10, 'color' => '000000'];
        $labelStyle = array_merge($hBodyStyle, ['bold' => true]);

        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'width' => 5000,
            'unit' => 'pct',
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
        ];

        $table = $section->addTable($tableStyle);

        // Header Style
        $headerFontStyle = ['bold' => true, 'size' => 10, 'color' => 'FFFFFF']; // White Text
        $headerParagraphStyle = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'valign' => 'center', 'spaceBefore' => 120];
        $headerCellStyles = ['bgColor' => '385623', 'valign' => 'center'];
        $cellStyles = ['valign' => 'center', 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceBefore' => 120];

        // Header
        $headers = ['Kategori', 'Perempuan', 'Laki-laki', 'Sub Total'];
        $row = $table->addRow();
        foreach ($headers as $header) {
            // 'Kategori' gets more space, others are equal
            $width = ($header === 'Kategori') ? 2000 : 1000;
            $row->addCell($width, $headerCellStyles)->addText(
                $header,
                $headerFontStyle,
                $headerParagraphStyle
            );
        }

        // Data rows - using (int) to handle nulls safely
        $this->addTableRow(
            $table,
            'Dewasa (25-59 tahun)',
            (int)$kegiatan->penerimamanfaatdewasaperempuan,
            (int)$kegiatan->penerimamanfaatdewasalakilaki,
            (int)$kegiatan->penerimamanfaatdewasatotal,
        );

        $this->addTableRow(
            $table,
            'Lansia (60+ tahun)',
            (int)$kegiatan->penerimamanfaatlansiaperempuan,
            (int)$kegiatan->penerimamanfaatlansialakilaki,
            (int)$kegiatan->penerimamanfaatlansiatotal,
        );

        $this->addTableRow(
            $table,
            'Remaja (18-24 tahun)',
            (int)$kegiatan->penerimamanfaatremajaperempuan,
            (int)$kegiatan->penerimamanfaatremajalakilaki,
            (int)$kegiatan->penerimamanfaatremajatotal,
        );

        $this->addTableRow(
            $table,
            'Anak (< 18 tahun)',
            (int)$kegiatan->penerimamanfaatanakperempuan,
            (int)$kegiatan->penerimamanfaatanaklakilaki,
            (int)$kegiatan->penerimamanfaatanaktotal,
        );

        // Grand Total Row
        $row = $table->addRow();

        // Cell 1: Label
        $row->addCell(2000)->addText('GRAND TOTAL', $labelStyle, $headerParagraphStyle);

        // Cell 2: Total Perempuan
        $row->addCell(1000)->addText(
            (string)($kegiatan->penerimamanfaatperempuantotal ?? 0),
            $labelStyle,
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );

        // Cell 3: Total Laki-laki
        $row->addCell(1000)->addText(
            (string)($kegiatan->penerimamanfaatlakilakitotal ?? 0),
            $labelStyle,
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );

        // Cell 4: Overall Total
        $row->addCell(1000)->addText(
            (string)($kegiatan->penerimamanfaattotal ?? 0),
            $labelStyle,
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );
    }

    /**
     * Add table row with proper formatting
     */
    private function addTableRow($table, $label, $female, $male, $total)
    {
        $row = $table->addRow();

        $row->addCell(2500)->addText($this->safeValue($label), ['size' => 10], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        $row->addCell(2500)->addText(
            (string)($female ?? 0),
            ['size' => 10],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );

        $row->addCell(2500)->addText(
            (string)($male ?? 0),
            ['size' => 10],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );

        $row->addCell(2500)->addText(
            (string)($total ?? 0),
            ['size' => 10],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
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
