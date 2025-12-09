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
            'status' => $request->input('status'),
            'program_id' => $request->input('program_id'),
            'kegiatan_id' => $request->input('kegiatan_id'),
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
     * API: Get kegiatan by program (trkegiatan based on program through relations)
     */
    public function getKegiatanByProgram(Request $request)
    {
        $programId = $request->input('program_id');
        $search = $request->input('search');

        $query = Kegiatan::with(['programOutcomeOutputActivity', 'jenisKegiatan']);

        // Filter by program if selected
        if ($programId) {
            $query->whereHas('programOutcomeOutputActivity.program_outcome_output.program_outcome.program', function ($q) use ($programId) {
                $q->where('id', $programId);
            });
        }

        // Search
        if ($search) {
            $query->whereHas('programOutcomeOutputActivity', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        $kegiatanList = $query->orderBy('tanggalmulai', 'desc')->get();

        $results = $kegiatanList->map(function ($kegiatan) {
            $activity = $kegiatan->programOutcomeOutputActivity;
            return [
                'id' => $kegiatan->id,
                'text' => ($activity->kode ?? '') . ' - ' . ($activity->nama ?? 'N/A'),
                'jeniskegiatan_id' => $kegiatan->jeniskegiatan_id
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => ['more' => false]
        ]);
    }

    /**
     * API: Get jenis kegiatan by kegiatan or all if no kegiatan selected 
     */
    public function getJenisKegiatanByKegiatan(Request $request)
    {
        $kegiatanId = $request->input('kegiatan_id');
        $search = $request->input('search');

        // If kegiatan selected, only return the jenis for that kegiatan
        if ($kegiatanId) {
            $kegiatan = Kegiatan::with('jenisKegiatan')->find($kegiatanId);
            if ($kegiatan && $kegiatan->jenisKegiatan) {
                return response()->json([
                    'results' => [
                        [
                            'id' => $kegiatan->jenisKegiatan->id,
                            'text' => $kegiatan->jenisKegiatan->nama
                        ]
                    ],
                    'pagination' => ['more' => false]
                ]);
            }
        }

        // Return all jenis kegiatan
        $query = \App\Models\Jenis_Kegiatan::query();

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

    public function exportPdf($id, Request $request)
    {
        $kegiatan = BTOR::getData($id);
        $viewPath = BTOR::getViewPath($kegiatan->jeniskegiatan_id);
        $showButtons = false; // Hide buttons for PDF

        $orientation = $request->get('orientation', 'landscape');

        $pdf = Pdf::loadView('tr.btor.print', compact('kegiatan', 'viewPath', 'showButtons'))
            ->setPaper('a4', $orientation)
            ->setOption([
                'margin-top' => 10,
                'margin-bottom' => 10,
                'margin-left' => 30,
                'margin-right' => 30,
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
