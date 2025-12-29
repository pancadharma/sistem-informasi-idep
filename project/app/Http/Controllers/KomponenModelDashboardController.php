<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Meals_Komponen_Moldel_Target_Reinstra as Sektor;
use App\Models\KomponenModel as Model;
use App\Models\Meals;
use App\Models\Meals_Komponen_Model;
use App\Models\TargetReinstra;
use Illuminate\Support\Facades\DB;

class KomponenModelDashboardController extends Controller
{


        // New method for indexV3
    public function index()
    {
        $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');
        return view('tr.komponenmodel.dashboard-v3', compact('googleMapsApiKey'));
    }

    public function index_old()
    {
        $programs = Program::all();
        // $sektors = Sektor::all();
        $sektors =  TargetReinstra::all()->map(function ($sektor) {
            return [
                'id' => $sektor->id,
                'nama' => $sektor->nama,
            ];
        })->toArray(); // sesuaikan dengan model Sektor yang digunakan
        // $models = Model::all()->pluck('id')->toArray(); // sesuaikan juga disini
        // 'sektorTerpilih'  => $komodel->sektors->pluck('id')->toArray(), // sesuaikan juga disini

        // return $sektors;
        $models = Model::all();
        $years = Meals_Komponen_Model::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');

        return view('tr.komponenmodel.dashboard', compact('programs', 'sektors', 'models', 'years', 'googleMapsApiKey'));
    }


    public function indexV2()
    {
        $programs = Program::all();
        $sektors =  TargetReinstra::all()->map(function ($sektor) {
            return [
                'id' => $sektor->id,
                'nama' => $sektor->nama,
            ];
        })->toArray();
        $models = Model::all();
        $years = Meals_Komponen_Model::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');

        $trendData = Meals_Komponen_Model::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(id) as total')
        )
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();

        return view('tr.komponenmodel.dashboard_v2', compact('programs', 'sektors', 'models', 'years', 'googleMapsApiKey', 'trendData'));
    }

    public function getMapMarkers(Request $request)
    {
        $query = DB::table('trmeals_komponen_model_lokasi as l')
            ->join('trmeals_komponen_model as km', 'l.mealskomponenmodel_id', '=', 'km.id')
            ->join('mkomponenmodel as mm', 'km.komponenmodel_id', '=', 'mm.id')
            ->join('provinsi as prov', 'l.provinsi_id', '=', 'prov.id')
            ->select(
                'l.lat',
                'l.long',
                'mm.nama as nama_komponen_model',
                'prov.nama as nama_provinsi'
            );

        if ($request->has('program_id') && $request->program_id) {
            $query->where('km.program_id', $request->program_id);
        }

        if ($request->has('targetreinstra_id') && $request->sektor_id) {
            $query->whereIn('km.id', function ($subquery) use ($request) {
                $subquery->select('mealskomponenmodel_id')
                    ->from('trmeals_komponen_model_targetreinstra')
                    ->where('targetreinstra_id', $request->sektor_id);
            });
        }

        if ($request->has('komponenmodel_id') && $request->model_id) {
            $query->where('km.komponenmodel_id', $request->model_id);
        }

        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('km.created_at', $request->tahun);
        }

        if ($request->filled('model_name')) {
            $query->where('mm.nama', $request->model_name);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function getProgramsByModel(Request $request)
    {
        $query = Program::query();

        if ($request->filled('model_id')) {
            $query->whereHas('mealsKomponenModel', function ($q) use ($request) {
                $q->where('komponenmodel_id', $request->model_id);
            });
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function getSektorChartData(Request $request)
    {
        $query = DB::table('trmeals_komponen_model_targetreinstra as kms')
            ->join('mtargetreinstra as s', 'kms.targetreinstra_id', '=', 's.id')
            ->join('trmeals_komponen_model as km', 'kms.mealskomponenmodel_id', '=', 'km.id')
            ->select('s.nama as sektor_name', DB::raw('count(km.id) as total'))
            ->groupBy('s.nama');

        if ($request->has('program_id') && $request->program_id) {
            $query->where('km.program_id', $request->program_id);
        }

        if ($request->has('komponenmodel_id') && $request->model_id) {
            $query->where('km.komponenmodel_id', $request->model_id);
        }

        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('km.created_at', $request->tahun);
        }

        if ($request->filled('model_name')) {
            $query->where('mm.nama', $request->model_name);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function getProgramChartData(Request $request)
    {
        $query = DB::table('trmeals_komponen_model as km')
            ->join('trprogram as p', 'km.program_id', '=', 'p.id')
            ->select('p.nama as program_name', DB::raw('sum(km.totaljumlah) as total'))
            ->groupBy('p.nama');

        if ($request->filled('program_id')) {
            $query->where('km.program_id', $request->program_id);
        }

        if ($request->filled('sektor_id')) {
            $query->whereIn('km.id', function ($subquery) use ($request) {
                $subquery->select('mealskomponenmodel_id')
                    ->from('trmeals_komponen_model_targetreinstra')
                    ->where('targetreinstra_id', $request->sektor_id);
            });
        }

        if ($request->filled('model_id')) {
            $query->where('km.komponenmodel_id', $request->model_id);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('km.created_at', $request->tahun);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function getModelChartData(Request $request)
    {
        $query = DB::table('trmeals_komponen_model as km')
            ->join('mkomponenmodel as m', 'km.komponenmodel_id', '=', 'm.id')
            ->select('m.nama as model_name', DB::raw('count(km.id) as total'))
            ->groupBy('m.nama');

        if ($request->filled('program_id')) {
            $query->where('km.program_id', $request->program_id);
        }

        if ($request->filled('sektor_id')) {
            $query->whereIn('km.id', function ($subquery) use ($request) {
                $subquery->select('mealskomponenmodel_id')
                    ->from('trmeals_komponen_model_targetreinstra')
                    ->where('targetreinstra_id', $request->sektor_id);
            });
        }

        if ($request->filled('model_id')) {
            $query->where('km.komponenmodel_id', $request->model_id);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('km.created_at', $request->tahun);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function getSummaryData(Request $request)
    {
        $komponenQuery = Meals_Komponen_Model::query();

        if ($request->filled('program_id')) {
            $komponenQuery->where('program_id', $request->program_id);
        }

        if ($request->filled('targetreinstra_id')) {
            $komponenQuery->whereHas('sektors', function ($q) use ($request) {
                $q->where('trmeals_komponen_model_targetreinstra.id', $request->sektor_id);
            });
        }

        if ($request->filled('model_id')) {
            $komponenQuery->where('komponenmodel_id', $request->model_id);
        }

        if ($request->filled('tahun')) {
            $komponenQuery->whereYear('created_at', $request->tahun);
        }

        // Clone the query for different calculations
        $lokasiQuery = DB::table('trmeals_komponen_model_lokasi')
            ->whereIn('mealskomponenmodel_id', $komponenQuery->pluck('id'));

        $totalKomponen = $komponenQuery->count();
        $totalProgram = $komponenQuery->distinct('program_id')->count('program_id');
        $totalLokasi = $lokasiQuery->count();
        $totalJumlah = $komponenQuery->sum('totaljumlah');

        return response()->json([
            'totalKomponen' => $totalKomponen,
            'totalProgram' => $totalProgram,
            'totalLokasi' => $totalLokasi,
            'totalJumlah' => number_format($totalJumlah),
        ]);
    }


    public function getInitialData()
    {
        try {
            // Fetch all data required for the dashboard view
            $dashboard_data = $this->fetchData();

            // Fetch data for filters
            $programs = Program::select('id', 'nama')->orderBy('nama')->get();
            $komponen_models = Model::select('id', 'nama')->orderBy('nama')->get();
            $provinces = DB::table('provinsi')->select('id', 'nama')->orderBy('nama')->get();
            // Extract unique years from the 'tanggalmulai' field of all programs
            $years = Program::select(DB::raw('YEAR(tanggalmulai) as year'))
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');

            return response()->json([
                'filters' => [
                    'programs' => $programs,
                    'komponen_models' => $komponen_models,
                    'provinces' => $provinces,
                    'years' => $years,
                ],
                'dashboard_data' => $dashboard_data,
                'komponen_model_distribution' => $this->fetchKomponenModelDistribution()
            ]);
        } catch (\Exception $e) {
            // Log the error and return an error response
            \Log::error('Dashboard Initialization Error: ' . $e->getMessage());
            return response()->json(['error' => 'Could not initialize dashboard data.'], 500);
        }
    }

    /**
     * Aggregated komponen-model distribution for charting (program count per komponen model).
     */
    private function fetchKomponenModelDistribution(Request $request = null)
    {
        $query = DB::table('mkomponenmodel as mk')
            ->leftJoin('trmeals_komponen_model as tkm', function ($join) {
                $join->on('mk.id', '=', 'tkm.komponenmodel_id')
                    ->whereNull('tkm.deleted_at');
            })
            ->leftJoin('trprogram as tp', 'tkm.program_id', '=', 'tp.id')
            ->leftJoin('trmeals_komponen_model_lokasi as tkml', function ($join) {
                $join->on('tkm.id', '=', 'tkml.mealskomponenmodel_id')
                    ->whereNull('tkml.deleted_at');
            })
            ->select(
                'mk.id as komponenmodel_id',
                'mk.nama as komponen_model_name',
                DB::raw('COUNT(DISTINCT tp.id) as total_programs')
            )
            ->groupBy('mk.id', 'mk.nama')
            ->orderBy('mk.nama');

        if ($request) {
            if ($request->filled('komponenmodel_id') && $request->komponenmodel_id !== 'all') {
                $query->where('mk.id', $request->komponenmodel_id);
            }

            if ($request->filled('program_id') && $request->program_id !== 'all') {
                $query->where(function ($q) use ($request) {
                    $q->where('tkm.program_id', $request->program_id)
                        ->orWhereNull('tkm.id');
                });
            }

            if ($request->filled('provinsi_id') && $request->provinsi_id !== 'all') {
                $query->where(function ($q) use ($request) {
                    $q->where('tkml.provinsi_id', $request->provinsi_id)
                        ->orWhereNull('tkml.id');
                });
            }

            if ($request->filled('tahun') && $request->tahun !== 'all') {
                $query->where(function ($q) use ($request) {
                    $q->whereYear('tp.tanggalmulai', $request->tahun)
                        ->orWhereNull('tp.id');
                });
            }
        }

        return $query->get();
    }

    /**
     * Provides filtered dashboard data based on request parameters.
     */
    public function getDashboardData(Request $request)
    {
        try {
            // Fetch data using the reusable fetchData method with request filters
            $dashboard_data = $this->fetchData($request);
            $distribution = $this->fetchKomponenModelDistribution($request);

            return response()->json([
                'dashboard_data' => $dashboard_data,
                'komponen_model_distribution' => $distribution
            ]);
        } catch (\Exception $e) {
            // Log the error and return an error response
            \Log::error('Dashboard Data Fetch Error: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve dashboard data.'], 500);
        }
    }

    /**
     * A reusable private method to fetch and structure dashboard data.
     * It can accept an optional request object for filtering.
     *
     * @param Request|null $request
     * @return \Illuminate\Support\Collection
     */
    private function fetchData(Request $request = null)
    {

        // Start building the query - Changed to LEFT JOIN for locations to include komponen models without locations
        $query = DB::table('trmeals_komponen_model as tkm')
            ->join('trprogram as tp', 'tkm.program_id', '=', 'tp.id')
            ->join('mkomponenmodel as mkm', 'tkm.komponenmodel_id', '=', 'mkm.id')
            ->leftJoin('trmeals_komponen_model_lokasi as tkml', 'tkm.id', '=', 'tkml.mealskomponenmodel_id')
            ->leftJoin('msatuan as ms', 'tkml.satuan_id', '=', 'ms.id')
            ->leftJoin('provinsi as p', 'tkml.provinsi_id', '=', 'p.id')
            ->leftJoin('kabupaten as kab', 'tkml.kabupaten_id', '=', 'kab.id')
            ->leftJoin('kecamatan as kec', 'tkml.kecamatan_id', '=', 'kec.id')
            ->leftJoin('kelurahan as kel', 'tkml.desa_id', '=', 'kel.id')
            ->select(
                'tkm.id as komponen_id',
                'mkm.nama as komponen_tipe',
                'tkm.totaljumlah as total_unit',
                'ms.nama as satuan_unit', // Assuming the main unit is tied to the location's unit
                'tp.id as program_id',
                'tp.nama as nama_program',
                'tp.status as status_program',
                DB::raw('YEAR(tp.tanggalmulai) as tahun_program'),
                'p.nama as provinsi',
                'kab.nama as kabupaten',
                'kec.nama as kecamatan',
                'kel.nama as desa',
                'tkml.jumlah as jumlah_per_lokasi',
                'ms.nama as satuan_per_lokasi',
                'tkml.lat',
                'tkml.long'
            )
            ->whereNull('tkm.deleted_at')
            ->where(function ($q) {
                $q->whereNull('tkml.deleted_at')
                  ->orWhereNull('tkml.id'); // Include komponen models without locations
            });

        // Apply filters if a request object is provided
        if ($request) {
            if ($request->filled('program_id') && $request->program_id !== 'all') {
                $query->where('tkm.program_id', $request->program_id);
            }
            if ($request->filled('komponenmodel_id') && $request->komponenmodel_id !== 'all') {
                $query->where('tkm.komponenmodel_id', $request->komponenmodel_id);
            }
            if ($request->filled('provinsi_id') && $request->provinsi_id !== 'all') {
                $query->where('tkml.provinsi_id', $request->provinsi_id);
            }
            if ($request->filled('tahun') && $request->tahun !== 'all') {
                $query->whereYear('tp.tanggalmulai', $request->tahun);
            }
        }

        // Execute the main query
        $results = $query->get();

        // Get all related strategic targets (reinstra) in a separate efficient query
        $komponenIds = $results->pluck('komponen_id')->unique();
        if ($komponenIds->isNotEmpty()) {
            $targets = DB::table('trmeals_komponen_model_targetreinstra as tkmtr')
                ->join('mtargetreinstra as mtr', 'tkmtr.targetreinstra_id', '=', 'mtr.id')
                ->whereIn('tkmtr.mealskomponenmodel_id', $komponenIds)
                ->whereNull('tkmtr.deleted_at')
                ->select('tkmtr.mealskomponenmodel_id as komponen_id', 'mtr.nama as target_reinstra')
                ->get()
                ->groupBy('komponen_id');
        } else {
            $targets = collect();
        }

        // Map the targets back to the main results
        return $results->map(function ($item) use ($targets) {
            $itemTargets = $targets->get($item->komponen_id);
            $item->target_reinstra = $itemTargets ? $itemTargets->pluck('target_reinstra')->implode(';') : null;
            return $item;
        });
    }
}
