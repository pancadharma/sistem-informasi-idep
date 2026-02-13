<?php

namespace App\Http\Controllers\Revisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meals_Komponen_Model;
use App\Models\Meals_Komponen_Model_Lokasi;
use App\Models\Program;
use App\Models\Provinsi;
use App\Models\TargetReinstra;
use App\Models\KomponenModel as Master_KomponenModel;
use DB;

class KomponenModel extends Controller
{
    /**
     * Display the Model Dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch Filter Data
        $programs = Program::select('id', 'nama', 'kode')->orderBy('created_at', 'desc')->get();
        $provinsis = Provinsi::select('id', 'nama')->orderBy('nama')->get();
        // Get unique years from programs
        $years = Program::selectRaw('YEAR(tanggalmulai) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter();

        // Fetch Sektors (TargetReinstra)
        $sektors = TargetReinstra::select('id', 'nama')->where('aktif', 1)->orderBy('nama')->get();

        // Fetch Komponen Models for Jenis Model filter
        $komponenModels = Master_KomponenModel::select('id', 'nama')->orderBy('nama')->get();

        return view('dashboard.revisi.model', compact('programs', 'provinsis', 'years', 'sektors', 'komponenModels'));
    }

    /**
     * Get Dashboard Data via AJAX
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        $programId = $request->input('program_id');
        $provinsiId = $request->input('provinsi_id');
        $tahun = $request->input('tahun');
        $sektorId = $request->input('sektor_id');
        $komponenModelId = $request->input('komponenmodel_id');

        // Base Query for Models
        $query = Meals_Komponen_Model::with(['komponenmodel', 'sektors', 'program', 'lokasi']);

        // Filter by Program
        if ($programId) {
            $query->where('program_id', $programId);
        }

        // Filter by Year (via Program)
        if ($tahun) {
            $query->whereHas('program', function ($q) use ($tahun) {
                $q->whereYear('tanggalmulai', $tahun);
            });
        }

        // Filter by Sektor
        if ($sektorId) {
            $query->whereHas('sektors', function ($q) use ($sektorId) {
                $q->where('mtargetreinstra.id', $sektorId);
            });
        }

        // Filter by Komponen Model (Jenis Model)
        if ($komponenModelId) {
            $query->where('komponenmodel_id', $komponenModelId);
        }

        // Filter by Province (via Lokasi)
        if ($provinsiId) {
            $query->whereHas('lokasi', function ($q) use ($provinsiId) {
                $q->where('provinsi_id', $provinsiId);
            });
        }

        $models = $query->get();
        $modelIds = $models->pluck('id');

        // --- Statistics ---
        $stats = [
            'totalModels' => $models->count(),
            'totalTypes' => $models->groupBy('komponenmodel_id')->count(),
            'totalLocations' => Meals_Komponen_Model_Lokasi::whereIn('mealskomponenmodel_id', $modelIds)->count(),
            'totalQuantity' => Meals_Komponen_Model_Lokasi::whereIn('mealskomponenmodel_id', $modelIds)->sum('jumlah')
        ];

        // --- 1. Map Markers (Locations) ---
        $locations = Meals_Komponen_Model_Lokasi::with(['mealsKomponenModel.komponenmodel', 'mealsKomponenModel.program', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'dusun'])
            ->whereIn('mealskomponenmodel_id', $modelIds)
            ->whereNotNull('lat')
            ->whereNotNull('long')
            ->get()
            ->map(function ($loc) {
                return [
                    'program_id' => $loc->mealsKomponenModel->program_id,
                    'program_nama' => $loc->mealsKomponenModel->program->nama ?? 'Unknown Program',
                    'jenis_model' => $loc->mealsKomponenModel->komponenmodel->nama ?? 'Lainnya',
                    'provinsi' => $loc->provinsi->nama ?? '-',
                    'kabupaten' => $loc->kabupaten->nama ?? '-',
                    'kecamatan' => $loc->kecamatan->nama ?? '-',
                    'desa' => $loc->desa->nama ?? '-',
                'dusun' => $loc->dusun->nama ?? '-',
                    'jumlah' => (float) $loc->jumlah ?? 0,
                    'satuan' => $loc->satuan->nama ?? 'unit', // Adjust if relation differs
                    'lat' => (float) $loc->lat,
                    'long' => (float) $loc->long,
                ];
            });

        // --- 2. Trend Data (Year -> [{jenis_model, total}]) ---
        $trendDataRaw = $models->groupBy(function ($item) {
            return $item->program && $item->program->tanggalmulai
                ? \Carbon\Carbon::parse($item->program->tanggalmulai)->format('Y')
                : 'Unknown';
        })->sortKeys();

        $trendData = [];
        foreach ($trendDataRaw as $year => $yearModels) {
            $byType = $yearModels->groupBy(function ($m) {
                return $m->komponenmodel->nama ?? 'Lainnya';
            })->map(function ($group, $type) {
                return ['jenis_model' => $type, 'total' => $group->count()];
            })->values();
            $trendData[$year] = $byType;
        }

        // --- 3. Sektor Contribution ([{sektor, 'Model A': 10, ...}]) ---
        $sektorMap = [];
        foreach ($models as $model) {
            $modelName = $model->komponenmodel->nama ?? 'Lainnya';
            foreach ($model->sektors as $sektor) {
                $sektorName = $sektor->nama;
                if (!isset($sektorMap[$sektorName])) {
                    $sektorMap[$sektorName] = ['sektor' => $sektorName];
                }
                if (!isset($sektorMap[$sektorName][$modelName])) {
                    $sektorMap[$sektorName][$modelName] = 0;
                }
                $sektorMap[$sektorName][$modelName]++;
            }
        }
        $sektorKontribusi = array_values($sektorMap);

        // --- 4. Jenis Model List (for Legend/Colors) ---
        $jenisModel = $models->map(function ($m) {
            return $m->komponenmodel->nama ?? 'Lainnya';
        })->unique()->values()->map(function ($name, $index) {
            // Assign some default colors cyclically or randomly
            $colors = ['#667eea', '#f5576c', '#43e97b', '#4facfe', '#f093fb', '#fa709a'];
            return [
                'nama' => $name,
                'color' => $colors[$index % count($colors)]
            ];
        });

        // --- 5. Table Data (Grouped by Komponen Model ID) ---
        $tableData = [];
        $groupedModels = $models->groupBy('id');

        foreach ($groupedModels as $modelId => $modelGroup) {
            $model = $modelGroup->first();
            $modelLocations = Meals_Komponen_Model_Lokasi::with(['provinsi', 'kabupaten', 'kecamatan', 'desa', 'satuan'])
                ->where('mealskomponenmodel_id', $modelId)
                ->get();

            // Aggregating Totals per Unit for this model
            $unitBreakdown = $modelLocations->groupBy('satuan.nama')->map(function ($group, $unit) {
                return [
                    'unit' => $unit ?: 'unit',
                    'total' => $group->sum('jumlah')
                ];
            })->values()->toArray();

            $tableData[] = [
                'komponen_id' => $modelId,
                'nama_program' => $model->program->nama ?? '-',
                'kode_program' => $model->program->kode ?? '-',
                'komponen_tipe' => $model->komponenmodel->nama ?? 'Lainnya',
                'total_unit' => $modelLocations->sum('jumlah'),
                'satuan_unit' => $modelLocations->pluck('satuan.nama')->filter()->unique()->implode(', ') ?: 'unit',
                'unit_breakdown' => $unitBreakdown, // Added for detailed tooltips and summaries
                'tahun_program' => $model->program && $model->program->tanggalmulai
                    ? \Carbon\Carbon::parse($model->program->tanggalmulai)->format('Y')
                    : '-',
                'status_program' => $model->program && $model->program->tanggalselesai
                    ? (\Carbon\Carbon::parse($model->program->tanggalselesai)->isFuture() ? 'Active' : 'Completed')
                    : 'Unknown',
                'location_count' => $modelLocations->count(),
                'targets' => $model->sektors->pluck('nama')->toArray(),
                'locations' => $modelLocations->map(function ($loc) {
                    return [
                        'provinsi' => $loc->provinsi->nama ?? '-',
                        'kabupaten' => $loc->kabupaten->nama ?? '-',
                        'kecamatan' => $loc->kecamatan->nama ?? '-',
                        'desa' => $loc->desa->nama ?? '-',
                        'jumlah_per_lokasi' => (float) $loc->jumlah ?? 0,
                        'satuan_per_lokasi' => $loc->satuan->nama ?? 'unit',
                    ];
                })->toArray()
            ];
        }

        return response()->json([
            'stats' => $stats,
            'locations' => $locations,
            'trendData' => $trendData,
            'sektorKontribusi' => $sektorKontribusi,
            'jenisModel' => $jenisModel,
            'tableData' => $tableData
        ]);
    }
}
