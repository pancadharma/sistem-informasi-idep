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
        $aggregates = $this->getAggregates($filters);

        $filterLabels = [
            'program' => optional(Program::select('nama')->find($filters['program_id'] ?? null))->nama,
            'sektor'  => optional(mSektor::select('nama')->find($filters['sektor_id'] ?? null))->nama,
            'model'   => optional(KomponenModel::select('nama')->find($filters['model_id'] ?? null))->nama,
            'tahun'   => $filters['tahun'] ?? null,
        ];

        $pdf = Pdf::loadView('tr.komponenmodel.export_pdf', [
            'summary' => $summary,
            'charts' => $charts,
            'filters' => $request->all(),
            'filter_labels' => $filterLabels,
            'aggregates' => $aggregates,
        ])->setPaper('a4', 'landscape')->setOption('isRemoteEnabled', true);

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
        $aggregates = $this->getAggregates($filters);

        $filterLabels = [
            'program' => optional(Program::select('nama')->find($filters['program_id'] ?? null))->nama,
            'sektor'  => optional(mSektor::select('nama')->find($filters['sektor_id'] ?? null))->nama,
            'model'   => optional(KomponenModel::select('nama')->find($filters['model_id'] ?? null))->nama,
            'tahun'   => $filters['tahun'] ?? null,
        ];

        $headers = [
            'Content-Type' => 'application/vnd.ms-word',
            'Content-Disposition' => 'attachment;filename=komponen-model-dashboard.doc',
        ];

        return response()->make(view('tr.komponenmodel.export_docx', [
            'summary' => $summary,
            'charts' => $charts,
            'filters' => $request->all(),
            'filter_labels' => $filterLabels,
            'aggregates' => $aggregates,
        ]), 200, $headers);
    }

    public function aggregates(Request $request)
    {
        $filters = $request->only(['program_id', 'sektor_id', 'model_id', 'tahun']);
        $data = $this->getAggregates($filters);
        return response()->json($data);
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

    private function getAggregates(array $filters): array
    {
        // Base builder joining lokasi to parent komodel and lookups
        $base = \DB::table('trmeals_komponen_model_lokasi as l')
            ->join('trmeals_komponen_model as m', 'm.id', '=', 'l.mealskomponenmodel_id');

        // Filters
        if (!empty($filters['program_id'])) {
            $base->where('m.program_id', $filters['program_id']);
        }
        if (!empty($filters['model_id'])) {
            $base->where('m.komponenmodel_id', $filters['model_id']);
        }
        if (!empty($filters['tahun'])) {
            $base->whereYear('m.created_at', $filters['tahun']);
        }
        if (!empty($filters['sektor_id'])) {
            $base->join('trmeals_komponen_model_targetreinstra as mt', 'mt.mealskomponenmodel_id', '=', 'm.id')
                 ->where('mt.targetreinstra_id', $filters['sektor_id']);
        }

        // Clone builders for different groupings
        $perProgram = (clone $base)
            ->join('trprogram as p', 'p.id', '=', 'm.program_id')
            ->select('p.nama as program')
            ->selectRaw('SUM(l.jumlah) as total_jumlah, COUNT(l.id) as total_lokasi')
            ->groupBy('p.nama')
            ->orderByDesc('total_jumlah')
            ->get();

        $perModel = (clone $base)
            ->join('mkomponenmodel as km', 'km.id', '=', 'm.komponenmodel_id')
            ->select('km.nama as model')
            ->selectRaw('SUM(l.jumlah) as total_jumlah, COUNT(l.id) as total_lokasi')
            ->groupBy('km.nama')
            ->orderByDesc('total_jumlah')
            ->get();

        $perProvinsi = (clone $base)
            ->join('provinsi as pr', 'pr.id', '=', 'l.provinsi_id')
            ->select('pr.nama as provinsi')
            ->selectRaw('SUM(l.jumlah) as total_jumlah, COUNT(l.id) as total_lokasi')
            ->groupBy('pr.nama')
            ->orderByDesc('total_jumlah')
            ->get();

        $perSatuan = (clone $base)
            ->leftJoin('msatuan as s', 's.id', '=', 'l.satuan_id')
            ->selectRaw('COALESCE(s.nama, "-") as satuan')
            ->selectRaw('SUM(l.jumlah) as total_jumlah, COUNT(l.id) as total_lokasi')
            ->groupBy('s.nama')
            ->orderByDesc('total_jumlah')
            ->get();

        $topKabupaten = (clone $base)
            ->leftJoin('kabupaten as k', 'k.id', '=', 'l.kabupaten_id')
            ->select('k.nama as kabupaten')
            ->selectRaw('SUM(l.jumlah) as total_jumlah, COUNT(l.id) as total_lokasi')
            ->groupBy('k.nama')
            ->orderByDesc('total_jumlah')
            ->limit(10)
            ->get();

        return [
            'perProgram' => $perProgram,
            'perModel' => $perModel,
            'perProvinsi' => $perProvinsi,
            'perSatuan' => $perSatuan,
            'topKabupaten' => $topKabupaten,
        ];
    }
}
