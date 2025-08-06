<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Meals_Komponen_Moldel_Target_Reinstra as Sektor;
use App\Models\KomponenModel as Model;
use App\Models\Meals_Komponen_Model;
use Illuminate\Support\Facades\DB;

class KomponenModelDashboardController extends Controller
{
    public function index()
    {
        $programs = Program::all();
        $sektors = Sektor::all();
        $models = Model::all();
        $years = Meals_Komponen_Model::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');

        return view('tr.komponenmodel.dashboard', compact('programs', 'sektors', 'models', 'years', 'googleMapsApiKey'));
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

        $data = $query->get();

        return response()->json($data);
    }

    public function getSektorChartData(Request $request)
    {
        $query = DB::table('trmeals_komponen_model_targetreinstra as kms')
            ->join('msektor as s', 'kms.targetreinstra_id', '=', 's.id')
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

        $data = $query->get();

        return response()->json($data);
    }

    public function getProgramChartData(Request $request)
    {
        $query = DB::table('trmeals_komponen_model as km')
            ->join('trprogram as p', 'km.program_id', '=', 'p.id')
            ->select('p.nama as program_name', DB::raw('sum(km.totaljumlah) as total'))
            ->groupBy('p.nama');

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

        $data = $query->get();

        return response()->json($data);
    }

    public function getSummaryData(Request $request)
    {
        $komponenQuery = TrMealsKomponenModel::query();

        if ($request->filled('program_id')) {
            $komponenQuery->where('program_id', $request->program_id);
        }

        if ($request->filled('sektor_id')) {
            $komponenQuery->whereHas('sektors', function ($q) use ($request) {
                $q->where('msektor.id', $request->sektor_id);
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
}
