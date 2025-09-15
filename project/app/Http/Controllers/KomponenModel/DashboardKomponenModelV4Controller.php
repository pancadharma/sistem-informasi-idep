<?php

namespace App\Http\Controllers\KomponenModel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\KomponenModelRepositoryInterface;
use App\Models\Program; // For program data
use App\Models\User; // For user data
use App\Models\KomponenModel; // For component model types
use App\Models\Meals_Komponen_Model_Lokasi; // For location data
use App\Models\Provinsi; // For province data
use App\Models\Kabupaten; // For kabupaten data
use Illuminate\Support\Facades\DB; // For complex queries
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\KomponenModelExport; // Will create this later

class DashboardKomponenModelV4Controller extends Controller
{
    protected $komponenModelRepository;

    public function __construct(KomponenModelRepositoryInterface $komponenModelRepository)
    {
        $this->komponenModelRepository = $komponenModelRepository;
    }

    /**
     * Display a listing of the resource for the V4 Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->only(['program_id', 'komponenmodel_id', 'provinsi_id', 'tahun']);
        $dashboardData = $this->komponenModelRepository->getDashboardData($filters);

        if ($request->ajax()) {
            return response()->json($dashboardData);
        }

        // These are just placeholders for now, as they are not yet implemented in the repository
        $completionRate = 0;
        $timelineProgress = [];
        $budgetVsAchievement = [];
        $userAssignment = [];
        $creationTrends = [];
        $vulnerabilityGroups = [];
        $directBeneficiaries = 0;
        $indirectBeneficiaries = 0;
        $componentAllocation = [];
        $targetProgressTracking = [];
        $dataQualityMonitoring = [];

        return view('dashboard.komodel-v4.index', array_merge($dashboardData, compact(
            'completionRate',
            'timelineProgress',
            'budgetVsAchievement',
            'userAssignment',
            'creationTrends',
            'vulnerabilityGroups',
            'directBeneficiaries',
            'indirectBeneficiaries',
            'componentAllocation',
            'targetProgressTracking',
            'dataQualityMonitoring'
        )));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Not typically needed for a dashboard index page
        return view('dashboard.komodel-v4.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Not typically needed for a dashboard index page
        // Validation and storage logic would go here
        return redirect()->route('dashboard.komodel-v4.index')->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // For showing a specific component's detailed dashboard view
        $component = $this->komponenModelRepository->find($id);
        // Further detailed data fetching for this specific component can be done here
        return view('dashboard.komodel-v4.show', compact('component'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Not typically needed for a dashboard index page
        $item = $this->komponenModelRepository->find($id);
        return view('dashboard.komodel-v4.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Not typically needed for a dashboard index page
        // Validation and update logic would go here
        return redirect()->route('dashboard.komodel-v4.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Not typically needed for a dashboard index page
        // Deletion logic would go here
        return redirect()->route('dashboard.komodel-v4.index')->with('success', 'Item deleted successfully.');
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only(['program_id', 'komponenmodel_id', 'provinsi_id', 'tahun']);
        $dashboardData = $this->komponenModelRepository->getDashboardData($filters);
        $pdf = Pdf::loadView('dashboard.komodel-v4.pdf', compact('dashboardData'));
        return $pdf->download('komponen_model_dashboard.pdf');
    }

    public function exportXls(Request $request)
    {
        $filters = $request->only(['program_id', 'komponenmodel_id', 'provinsi_id', 'tahun']);
        $dashboardData = $this->komponenModelRepository->getDashboardData($filters);
        // return Excel::download(new KomponenModelExport($dashboardData), 'komponen_model_dashboard.xlsx'); // Uncomment after creating export class
        return Excel::download(new \App\Exports\KomponenModelExport($dashboardData), 'komponen_model_dashboard.xlsx');
    }
}
