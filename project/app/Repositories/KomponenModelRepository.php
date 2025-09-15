<?php

namespace App\Repositories;

use App\Models\Meals_Komponen_Model;
use Illuminate\Support\Facades\DB;

class KomponenModelRepository extends BaseRepository implements KomponenModelRepositoryInterface
{
    public function __construct(Meals_Komponen_Model $model)
    {
        parent::__construct($model);
    }

    public function getDashboardData(array $filters = [])
    {
        $query = $this->model->query();

        if (!empty($filters['program_id'])) {
            $query->where('program_id', $filters['program_id']);
        }

        if (!empty($filters['komponenmodel_id'])) {
            $query->where('komponenmodel_id', $filters['komponenmodel_id']);
        }

        if (!empty($filters['provinsi_id'])) {
            $query->whereHas('lokasi', function ($q) use ($filters) {
                $q->where('provinsi_id', $filters['provinsi_id']);
            });
        }

        if (!empty($filters['tahun'])) {
            $query->whereYear('created_at', $filters['tahun']);
        }

        $totalComponentModels = $query->count();
        $programsWithComponents = $query->distinct('program_id')->count('program_id');

        $geographicCoverageProvinces = DB::table('trmeals_komponen_model_lokasi')->distinct('provinsi_id')->count('provinsi_id');
        $geographicCoverageDistricts = DB::table('trmeals_komponen_model_lokasi')->distinct('kabupaten_id')->count('kabupaten_id');

        // This is a placeholder, as the beneficiary table is not clearly defined yet.
        $totalBeneficiaries = 0;

        $programDistribution = DB::table('trprogram')
            ->join('trmeals_komponen_model', 'trprogram.id', '=', 'trmeals_komponen_model.program_id')
            ->select('trprogram.id as program_id', 'trprogram.nama as program_name', DB::raw('count(trmeals_komponen_model.id) as total_components'))
            ->groupBy('trprogram.id', 'trprogram.nama')
            ->get();

        $provinceLevelSummary = DB::table('provinsi')
            ->join('trmeals_komponen_model_lokasi', 'provinsi.id', '=', 'trmeals_komponen_model_lokasi.provinsi_id')
            ->select('provinsi.id as id', 'provinsi.nama as province_name', DB::raw('count(trmeals_komponen_model_lokasi.id) as component_locations'))
            ->groupBy('provinsi.id', 'provinsi.nama')
            ->get();

        $districtLevelSummary = DB::table('kabupaten')
            ->join('trmeals_komponen_model_lokasi', 'kabupaten.id', '=', 'trmeals_komponen_model_lokasi.kabupaten_id')
            ->select('kabupaten.nama as district_name', 'provinsi.nama as province_name', DB::raw('count(trmeals_komponen_model_lokasi.id) as component_locations'))
            ->join('provinsi', 'kabupaten.provinsi_id', '=', 'provinsi.id')
            ->groupBy('kabupaten.id', 'kabupaten.nama', 'provinsi.nama')
            ->get();

        $interactiveMapData = DB::table('trmeals_komponen_model_lokasi')->get();

        $modelDistribution = DB::table('mkomponenmodel')
            ->join('trmeals_komponen_model', 'mkomponenmodel.id', '=', 'trmeals_komponen_model.komponenmodel_id')
            ->select('mkomponenmodel.id as id', 'mkomponenmodel.nama as model_name', DB::raw('count(trmeals_komponen_model.id) as total_components'), DB::raw('SUM(trmeals_komponen_model.totaljumlah) as total_quantity'))
            ->groupBy('mkomponenmodel.id', 'mkomponenmodel.nama')
            ->get();

        $userAssignment = DB::table('users')
            ->join('trmeals_komponen_model', 'users.id', '=', 'trmeals_komponen_model.user_id')
            ->select('users.nama as user_name', DB::raw('count(trmeals_komponen_model.id) as assigned_components'))
            ->groupBy('users.id', 'users.nama')
            ->get();

        return [
            'totalComponentModels' => $totalComponentModels,
            'programsWithComponents' => $programsWithComponents,
            'geographicCoverageProvinces' => $geographicCoverageProvinces,
            'geographicCoverageDistricts' => $geographicCoverageDistricts,
            'totalBeneficiaries' => $totalBeneficiaries,
            'programDistribution' => $programDistribution,
            'provinceLevelSummary' => $provinceLevelSummary,
            'districtLevelSummary' => $districtLevelSummary,
            'interactiveMapData' => $interactiveMapData,
            'modelDistribution' => $modelDistribution,
            'userAssignment' => $userAssignment,
            'genderDistribution' => $this->getGenderDistribution(),
            'ageGroupAnalysis' => $this->getAgeGroupAnalysis(),
            'programHierarchy' => $this->getProgramHierarchy(),
            'userAssignmentMatrix' => $this->getUserAssignmentMatrix(),
        ];
    }

    private function getGenderDistribution()
    {
        return DB::table('trmeals_penerima_manfaat')
            ->select('jenis_kelamin', DB::raw('count(*) as total'))
            ->groupBy('jenis_kelamin')
            ->get();
    }

    private function getAgeGroupAnalysis()
    {
        return DB::table('trmeals_penerima_manfaat')
            ->select(DB::raw('CASE
                WHEN umur < 18 THEN "Children"
                WHEN umur BETWEEN 18 AND 35 THEN "Youth"
                WHEN umur BETWEEN 36 AND 60 THEN "Adults"
                ELSE "Elderly"
            END as age_group'), DB::raw('count(*) as total'))
            ->groupBy('age_group')
            ->get();
    }

    private function getProgramHierarchy()
    {
        return \App\Models\Program::with('outcome.output.activities')->get();
    }

    private function getUserAssignmentMatrix()
    {
        return \App\Models\User::withCount('trmealsKomponenModels')->get();
    }
}