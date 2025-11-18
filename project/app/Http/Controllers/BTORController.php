<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Export\BTOR;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BTORExport;

class BTORController extends Controller
{
    /**
     * Display a listing of BTOR reports
     */
    public function index(Request $request)
    {
        $filters = [
            'jeniskegiatan_id' => $request->input('jeniskegiatan_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => $request->input('status'),
            'program_id' => $request->input('program_id'),
        ];

        $kegiatanList = BTOR::getFilteredList($filters);

        // Get filter options
        $jenisKegiatanList = \App\Models\Jenis_Kegiatan::all();
        $programList = \App\Models\Program::all();

        return view('tr.btor.index', compact('kegiatanList', 'jenisKegiatanList', 'programList', 'filters'));
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

    /**
     * Show print preview
     */
    public function print($id)
    {
        $kegiatan = BTOR::getData($id);
        $viewPath = BTOR::getViewPath($kegiatan->jeniskegiatan_id);

        // Debug: Check if view exists
        if (!view()->exists('tr.btor.print')) {
            abort(500, 'Print view template not found');
        }

        if (!view()->exists($viewPath)) {
            abort(500, "Back to Office type view not found: {$viewPath}");
        }

        return view('tr.btor.print', compact('kegiatan', 'viewPath'));
    }


    /**
     * Export to PDF
     */
    public function exportPdf($id)
    {
        $kegiatan = BTOR::getData($id);
        $viewPath = BTOR::getViewPath($kegiatan->jeniskegiatan_id);

        $pdf = Pdf::loadView('tr.btor.print', compact('kegiatan', 'viewPath'))
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 15)
            ->setOption('margin-right', 15);

        $filename = 'BTOR_' . $kegiatan->id . '_' . date('Ymd') . '.pdf';

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
     * Export multiple reports to ZIP
     */
    public function exportBulkPdf(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal 1 laporan untuk diekspor');
        }

        $zip = new \ZipArchive();
        $zipFileName = 'BTOR_Bulk_' . date('Ymd_His') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Create temp directory if not exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($ids as $id) {
                $kegiatan = BTOR::getData($id);
                $viewPath = BTOR::getViewPath($kegiatan->jeniskegiatan_id);

                $pdf = Pdf::loadView('tr.btor.print', compact('kegiatan', 'viewPath'))
                    ->setPaper('a4', 'portrait');

                $pdfContent = $pdf->output();
                $pdfFileName = 'BTOR_' . $kegiatan->id . '_' . str_slug($kegiatan->programOutcomeOutputActivity?->nama ?? 'report') . '.pdf';

                $zip->addFromString($pdfFileName, $pdfContent);
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
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
}
