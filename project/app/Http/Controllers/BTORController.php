<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Export\BTOR;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
}
