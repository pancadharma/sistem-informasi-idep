<?php

namespace App\Http\Controllers\KomponenModel;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\mSektor;
use App\Models\Meals_Komponen_Model;
use App\Models\Kegiatan_Lokasi;
use App\Models\KomponenModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        $programs = Program::select('id', 'nama')->get();
        $sektors = mSektor::select('id', 'nama')->get()->toArray();
        $models = KomponenModel::select('id', 'nama')->distinct()->get();
        $years = Kegiatan_Lokasi::selectRaw('YEAR(created_at) as year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $googleMapsApiKey = config('services.google_maps.api_key');

        return view('tr.komponenmodel.dashboard', compact('programs', 'sektors', 'models', 'years', 'googleMapsApiKey'));
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only(['program_id', 'sektor_id', 'model_id', 'tahun']);
        $charts = [
            'sektorChart' => $request->input('sektorChart'),
            'programChart' => $request->input('programChart'),
            'modelChart' => $request->input('modelChart'),
        ];

        $summary = $this->getSummaryData($filters);

        $pdf = Pdf::loadView('tr.komponenmodel.export_pdf', [
            'summary' => $summary,
            'charts' => $charts,
            'filters' => $request->all(),
        ]);

        return $pdf->download('komponen-model-dashboard.pdf');
    }

    public function exportDocx(Request $request)
    {
        $filters = $request->only(['program_id', 'sektor_id', 'model_id', 'tahun']);
        $charts = [
            'sektorChart' => $request->input('sektorChart'),
            'programChart' => $request->input('programChart'),
            'modelChart' => $request->input('modelChart'),
        ];

        $summary = $this->getSummaryData($filters);

        $headers = [
            'Content-Type' => 'application/vnd.ms-word',
            'Content-Disposition' => 'attachment;filename=komponen-model-dashboard.doc',
        ];

        return response()->make(view('tr.komponenmodel.export_docx', [
            'summary' => $summary,
            'charts' => $charts,
            'filters' => $request->all(),
        ]), 200, $headers);
    }

    private function getSummaryData(array $filters)
    {
        $queryKomponen = Meals_Komponen_Model::query();
        $queryProgram = Program::query();
        $queryLokasi = Kegiatan_Lokasi::query();

        // Apply filters to Komponen Model query
        $queryKomponen->when(isset($filters['program_id']) && $filters['program_id'] !== null, function ($q) use ($filters) {
            return $q->where('program_id', $filters['program_id']);
        });
        $queryKomponen->when(isset($filters['sektor_id']) && $filters['sektor_id'] !== null, function ($q) use ($filters) {
            return $q->where('sektor_id', $filters['sektor_id']);
        });
        $queryKomponen->when(isset($filters['model_id']) && $filters['model_id'] !== null, function ($q) use ($filters) {
            return $q->where('model_id', $filters['model_id']);
        });
        $queryKomponen->when(isset($filters['tahun']) && $filters['tahun'] !== null, function ($q) use ($filters) {
            return $q->whereYear('created_at', $filters['tahun']);
        });

        // Apply filters to Program query (only program_id is directly applicable here)
        $queryProgram->when(isset($filters['program_id']) && $filters['program_id'] !== null, function ($q) use ($filters) {
            return $q->where('id', $filters['program_id']);
        });

        // Apply filters to Kegiatan Lokasi query
        $queryLokasi->when(isset($filters['program_id']) && $filters['program_id'] !== null, function ($q) use ($filters) {
            return $q->where('program_id', $filters['program_id']);
        });
        $queryLokasi->when(isset($filters['sektor_id']) && $filters['sektor_id'] !== null, function ($q) use ($filters) {
            // Assuming Kegiatan_Lokasi has a direct or indirect relation to sektor_id
            // This might need adjustment based on actual schema
            return $q->where('sektor_id', $filters['sektor_id']);
        });
        $queryLokasi->when(isset($filters['model_id']) && $filters['model_id'] !== null, function ($q) use ($filters) {
            // Assuming Kegiatan_Lokasi has a direct or indirect relation to model_id
            // This might need adjustment based on actual schema
            return $q->where('model_id', $filters['model_id']);
        });
        $queryLokasi->when(isset($filters['tahun']) && $filters['tahun'] !== null, function ($q) use ($filters) {
            return $q->whereYear('created_at', $filters['tahun']);
        });

        $totalKomponen = $queryKomponen->count();
        $totalProgram = $queryProgram->count();
        $totalLokasi = $queryLokasi->count();
        // Assuming totalJumlah refers to the count of Komponen Models after filtering
        $totalJumlah = $totalKomponen; // Or sum of a specific column if it exists, e.g., $queryKomponen->sum('some_quantity_column');

        return [
            'totalKomponen' => number_format($totalKomponen),
            'totalProgram' => number_format($totalProgram),
            'totalLokasi' => number_format($totalLokasi),
            'totalJumlah' => number_format($totalJumlah),
        ];
    }
}