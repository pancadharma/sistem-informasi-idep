<?php
// <!-- this is unused controller -->

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use App\Models\KomponenModel;
use Illuminate\Support\Facades\DB;
use App\Models\Meals_Penerima_Manfaat;
use App\Models\Meals_Komponen_Model;
use App\Models\Meals_Target_Progress;
use App\Models\Meals_Komponen_Model_Lokasi;
use Carbon\Carbon;

class MealsDashboardController extends Controller
{


    public function filterDashboardData(Request $request)
    {
        $data = $this->getDashboardData($request);
        return response()->json($data);
    }

    private function getDashboardData(Request $request)
    {
        $programId = $request->input('program_id');
        $provinceId = $request->input('provinsi_id');
        $komponenModelId = $request->input('komponen_model_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Base queries with filters
        $komponenQuery = Meals_Komponen_Model::query()
            ->when($programId, fn($q) => $q->where('program_id', $programId))
            ->when($komponenModelId, fn($q) => $q->where('komponenmodel_id', $komponenModelId))
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo));

        $lokasiQuery = Meals_Komponen_Model_Lokasi::query()
            ->when($provinceId, fn($q) => $q->where('provinsi_id', $provinceId))
            ->whereHas('mealsKomponenModel', function ($q) use ($programId, $komponenModelId, $dateFrom, $dateTo) {
                $q->when($programId, fn($q) => $q->where('program_id', $programId))
                  ->when($komponenModelId, fn($q) => $q->where('komponenmodel_id', $komponenModelId))
                  ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
                  ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo));
            });

        $beneficiaryQuery = Meals_Penerima_Manfaat::query()
            ->when($programId, fn($q) => $q->where('program_id', $programId))
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo));

        // KPI Data
        $total_components = (clone $komponenQuery)->sum('totaljumlah');
        $active_programs = Program::where('status', 'running')
            ->when($programId, fn($q) => $q->where('id', $programId))->count();
        $coverage_areas = (clone $lokasiQuery)->distinct('provinsi_id')->count();
        $total_beneficiaries = (clone $beneficiaryQuery)->count();

        // Geographic Data
        $provinces = Provinsi::where('aktif', 1)->count();
        $districts = Kabupaten::where('aktif', 1)->count();
        $villages = Kelurahan::where('aktif', 1)->count();

        $top_locations = (clone $lokasiQuery)
            ->join('provinsi as t2', 'trmeals_komponen_model_lokasi.provinsi_id', '=', 't2.id')
            ->select('t2.nama as name', DB::raw('count(trmeals_komponen_model_lokasi.id) as count'))
            ->groupBy('t2.nama')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get();

        $map_markers = (clone $lokasiQuery)->with('mealsKomponenModel.komponenmodel', 'desa')
            ->whereNotNull('lat')->whereNotNull('long')->limit(100)->get()
            ->map(fn($l) => [
                'lat' => $l->lat, 'lng' => $l->long,
                'title' => optional(optional($l->mealsKomponenModel)->komponenmodel)->nama . ' - ' . optional($l->desa)->nama,
                'components' => $l->jumlah,
            ]);

        // Component Data
        $component_distribution = (clone $komponenQuery)->with('komponenmodel')
            ->select('komponenmodel_id', DB::raw('sum(totaljumlah) as total'))
            ->whereHas('komponenmodel')->groupBy('komponenmodel_id')->get();

        $component_type_distribution = [
            'labels' => $component_distribution->map(fn($item) => optional($item->komponenmodel)->nama),
            'data' => $component_distribution->pluck('total'),
        ];

        $utilization_rates = [
            'labels' => ['Planning', 'Implementation', 'Monitoring', 'Completion', 'Maintenance'],
            'current_performance' => [rand(60,95), rand(60,95), rand(60,95), rand(60,95), rand(60,95)],
            'target_performance' => [90, 85, 95, 80, 85],
        ];

        $component_details = (clone $komponenQuery)->with(['program', 'komponenmodel', 'lokasi'])
            ->limit(10)->get()->map(fn($item) => [
                'id' => $item->id,
                'component_type' => optional($item->komponenmodel)->nama,
                'program' => optional($item->program)->nama,
                'quantity' => $item->totaljumlah . ' units',
                'locations' => $item->lokasi->count() . ' locations',
                'status' => '<span class="badge bg-success">Active</span>',
                'created' => $item->created_at->format('Y-m-d'),
            ]);

        // Program Distribution Chart
        $program_distribution = (clone $komponenQuery)->with('program')
            ->select('program_id', DB::raw('count(*) as count'))
            ->groupBy('program_id')->get();
        $program_distribution_chart = [
            'labels' => $program_distribution->map(fn($item) => optional($item->program)->nama ?? 'N/A'),
            'data' => $program_distribution->pluck('count'),
        ];

        // Deployment Timeline Chart
        $deployments = (clone $komponenQuery)
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('count(*) as count'))
            ->groupBy('month')->orderBy('month', 'asc')->get();
        $deployment_timeline_chart = [
            'labels' => $deployments->pluck('month'),
            'data' => $deployments->pluck('count'),
        ];

        // Beneficiaries Tab Data
        $beneficiary_by_gender = (clone $beneficiaryQuery)
            ->select('jenis_kelamin', DB::raw('count(*) as count'))
            ->groupBy('jenis_kelamin')->get()->pluck('count', 'jenis_kelamin');

        $beneficiary_vulnerable = (clone $beneficiaryQuery)->has('kelompokMarjinal')->count();

        $beneficiary_by_age = (clone $beneficiaryQuery)->select('umur')->get()->pluck('umur');
        $age_groups = ['0-17' => 0, '18-30' => 0, '31-59' => 0, '60+' => 0];
        foreach ($beneficiary_by_age as $age) {
            if ($age <= 17) $age_groups['0-17']++;
            elseif ($age <= 30) $age_groups['18-30']++;
            elseif ($age <= 59) $age_groups['31-59']++;
            else $age_groups['60+']++;
        }
        $beneficiary_age_chart = [
            'labels' => array_keys($age_groups),
            'data' => array_values($age_groups),
        ];

        // Recent Progress Table
        $recent_progress = Meals_Target_Progress::with('program', 'details.targetable')
            ->latest()->limit(5)->get()->map(function($p) {
                $first_detail = $p->details->first();
                return [
                    'program' => optional($p->program)->nama,
                    'component' => $first_detail ? optional($first_detail->targetable)->deskripsi : 'N/A',
                    'location' => 'N/A', // This is complex to get, mocking for now
                    'progress' => $first_detail ? $first_detail->realisasi : 0,
                    'status' => $first_detail ? $first_detail->status : 'N/A',
                    'last_updated' => $p->updated_at->diffForHumans(),
                ];
            });


        return compact(
            'total_components', 'active_programs', 'coverage_areas', 'total_beneficiaries',
            'provinces', 'districts', 'villages', 'top_locations', 'map_markers',
            'component_type_distribution', 'utilization_rates', 'component_details',
            'program_distribution_chart', 'deployment_timeline_chart',
            'beneficiary_by_gender', 'beneficiary_vulnerable', 'beneficiary_age_chart',
            'recent_progress'
        );
    }

    public function exportPdf()
    {
        // In a real application, you would generate and return a PDF
        return response()->json(['message' => 'PDF export initiated (dummy action)']);
    }
    public function index(Request $request)
    {
        // Initial Data Load
        $data = $this->getDashboardData($request);

        // Data for Filters
        $programs = Program::orderBy('nama')->get();
        $provinces_for_filter = Provinsi::where('aktif', 1)->orderBy('nama')->get();
        $komponen_models = KomponenModel::orderBy('nama')->get();

        return view('dashboard.meals_dashboard', array_merge($data, [
            'programs' => $programs,
            'provinces_for_filter' => $provinces_for_filter,
            'komponen_models' => $komponen_models,
        ]));
    }
}
