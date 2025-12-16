<?php

namespace App\Http\Controllers\Admin;

use App\Models\Program;
use App\Models\Program_Pendonor;
use Exception;
use App\Models\MPendonor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Kategori_Pendonor;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\StoreMpendonorRequest;
use App\Http\Requests\UpdateMpendonorRequest;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DonationExport;
use App\Models\Program_Pendonor as TrProgramPendonor;

class MPendonorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master.mpendonor.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mpendonorkategori_id = Kategori_Pendonor::withActive()->get(['id', 'nama']);
        return response()->json($mpendonorkategori_id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMpendonorRequest $request)
    {

        try {
            $data = $request->validated();
            MPendonor::create($data);

            return response()->json([
                "success" => true,
                "message" => __('cruds.data.data') . ' ' . __('cruds.mpendonor.title') . ' ' . $request->nama . ' ' . __('cruds.data.added'),
                "status" => 201,
                "data" => $data,
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                "status" => 400,
                "success" => false,
                "message" => $th,
                "data" => $request->all(),
            ]);
        } catch (ValidationException $e) {
            $status = 'error';
            $message = 'Validation failed: ' . implode(', ', $e->errors());
            return response()->json(['status' => $status, 'message' => $message], 422); // Use 422 Unprocessable Entity for validation errors

        } catch (QueryException $e) {
            $status = 'error';
            if ($e->getCode() === '22003') {
                $message = 'The provided code is too large for the database. Please enter a valid code within the allowed range.';
            } else {
                $message = 'Database error: ' . $e->getMessage();
            }
            return response()->json(['status' => $status, 'message' => $message], 400); // Use 400 Bad Request for database errors

        } catch (Exception $e) {
            $status = 'error';
            $message = 'An unexpected error occurred: ' . $e->getMessage();
            return response()->json(['status' => $status, 'message' => $message], 500); // Use 500 Internal Server Error for general errors
        } catch (Exception $e) {
            $status = 'error';
            $message = 'An unexpected error occurred: ' . $e->getMessage();
            return response()->json(['status' => $status, 'message' => $message], 419); // Use 500 Internal Server Error for general errors
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MPendonor $pendonor)
    {
        //dump($mpendonor);
        $pendonor->load('mpendonnorkategori');
        //dd($mpendonor);
        return response()->json($pendonor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MPendonor $pendonor)
    {
        $mpendonorkategori_id = Kategori_Pendonor::withActive()->get(['id', 'nama']);
        $pendonor->load('mpendonnorkategori');
        return [$pendonor, "results" => $mpendonorkategori_id];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMpendonorRequest $request, MPendonor $pendonor)
    {
        //dump($request);
        try {
            // Validate the request data
            $data = $request->validated();
            // Update  model with the validated data
            $pendonor->update($data);
            // Update manual kolom data "aktif" karena tidak ke detect otomatis
            // $pendonor->aktif = $request->input('aktif');
            // $pendonor->save();
            $status = "success";
            $message = "Data " . $request->nama . " was updated successfully!";
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200); // Use 200 OK for successful updates

        } catch (ValidationException $e) {
            $status = 'error';
            $message = 'Validation failed: ' . implode(', ', $e->errors());
            return response()->json(['status' => $status, 'message' => $message], 422); // Use 422 Unprocessable Entity for validation errors

        } catch (QueryException $e) {
            // Handle specific database errors
            $status = 'error';
            if ($e->getCode() === '22003') {
                // MySQL numeric value out of range
                $message = 'The provided code is too large for the database. Please enter a valid code within the allowed range.';
            } else {
                // Other database errors
                $message = 'Database error: ' . $e->getMessage();
            }
            return response()->json(['status' => $status, 'message' => $message], 400); // Use 400 Bad Request for database errors

        } catch (Exception $e) {
            // Handle any other exceptions
            $status = 'error';
            $message = 'An unexpected error occurred: ' . $e->getMessage();
            return response()->json(['status' => $status, 'message' => $message], 500); // Use 500 Internal Server Error for general errors
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // public function datapendonor(){
    //     $mpendonor = new MPendonor();
    //     $data = $mpendonor->datapendonor();
    //     return $data;
    // }

    public function datapendonor()
    {
        $pendonor = MPendonor::with('mpendonnorkategori')
            ->withCount('donations as donation_count')
            ->withSum('donations as total_donation_value', 'nilaidonasi')
            ->get();

        $data = DataTables::of($pendonor)
            ->addColumn('donation_count', function ($mpendonor) {
                return '<span class="badge badge-primary">' . ($mpendonor->donation_count ?? 0) . ' donasi</span>';
            })
            ->addColumn('total_donation_value', function ($mpendonor) {
                return 'Rp ' . number_format($mpendonor->total_donation_value ?? 0, 0, ',', '.');
            })
            ->addColumn('action', function ($mpendonor) {
                $buttons = '';

                if (auth()->user()->can('pendonor_edit')) {
                    $buttons .= '<button type="button" class="btn btn-sm btn-info edit-mpendonor-btn" 
                    data-action="edit" 
                    data-mpendonor-id="' . $mpendonor->id . '" 
                    title="' . __('global.edit') . ' ' . __('cruds.mpendonor.title') . ' ' . $mpendonor->nama . '">
                    <i class="fas fa-pencil-alt"></i> 
                </button> ';
                }

                if (auth()->user()->can('pendonor_show')) {
                    $buttons .= '<button type="button" class="btn btn-sm btn-primary view-mpendonor-btn" 
                    data-action="view" 
                    data-mpendonor-id="' . $mpendonor->id . '" 
                    value="' . $mpendonor->id . '" 
                    title="' . __('global.view') . ' ' . __('cruds.mpendonor.title') . ' ' . $mpendonor->nama . '">
                    <i class="fas fa-folder-open"></i>
                </button> ';
                }

                // Tambahkan button untuk dashboard
                $buttons .= '<a href="' . route('pendonor.dashboard', $mpendonor->id) . '" class="btn btn-sm btn-success" 
                title="Dashboard Donasi">
                <i class="fas fa-chart-line"></i>
            </a>';

                return $buttons ?: '-';
            })
            ->rawColumns(['donation_count', 'total_donation_value', 'action'])
            ->make(true);
        return $data;
    }

    public function dashboard($id = null)
    {
        $pendonors = MPendonor::active()->orderBy('nama')->get();
        $programs = Program::where('status', '!=', 'deleted')->orderBy('nama')->get();

        $selectedPendonor = $id ? MPendonor::findOrFail($id) : null;

        return view('master.mpendonor.dashboard', compact('pendonors', 'programs', 'selectedPendonor'));
    }

    public function getDonationData(Request $request)
    {
        $query = Program_Pendonor::with(['pendonor', 'program']);

        // Filter berdasarkan tahun
        if ($request->filled('year')) {
            $query->whereHas('program', function ($q) use ($request) {
                $q->whereYear('tanggalmulai', $request->year)
                    ->orWhereYear('tanggalselesai', $request->year);
            });
        }

        // Filter berdasarkan program
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        // Filter berdasarkan pendonor
        if ($request->filled('pendonor_id')) {
            $query->where('pendonor_id', $request->pendonor_id);
        }

        $donations = $query->get();

        // Hitung statistik
        $totalDonations = $donations->count();
        $totalValue = $donations->sum('nilaidonasi');
        $avgDonation = $totalDonations > 0 ? $totalValue / $totalDonations : 0;

        // Data untuk chart - Donasi per pendonor
        $donationsByDonor = $donations->groupBy('pendonor_id')->map(function ($group) {
            return [
                'name' => $group->first()->pendonor->nama,
                'count' => $group->count(),
                'total' => $group->sum('nilaidonasi')
            ];
        })->values();

        // Data untuk chart - Donasi per program
        $donationsByProgram = $donations->groupBy('program_id')->map(function ($group) {
            return [
                'name' => $group->first()->program?->nama ?? '-',
                'kode' => $group->first()->program?->kode ?? '-',
                'count' => $group->count(),
                'total' => $group->sum('nilaidonasi')
            ];
        })->values();

        // Data untuk timeline (per bulan) - filter out null created_at
        $timeline = $donations->filter(function ($donation) {
            return $donation->created_at !== null;
        })->groupBy(function ($donation) {
            return \Carbon\Carbon::parse($donation->created_at)->format('Y-m');
        })->map(function ($group, $month) {
            $date = \Carbon\Carbon::parse($month . '-01');
            return [
                'month' => $date->format('M Y'), // Format: "Jan 2024"
                'sort_key' => $month, // For sorting
                'count' => $group->count(),
                'total' => $group->sum('nilaidonasi')
            ];
        })->sortBy('sort_key')->values();

        return response()->json([
            'statistics' => [
                'total_donations' => $totalDonations,
                'total_value' => $totalValue,
                'average_donation' => $avgDonation,
                'unique_donors' => $donations->unique('pendonor_id')->count(),
                'unique_programs' => $donations->unique('program_id')->count(),
            ],
            'charts' => [
                'by_donor' => $donationsByDonor,
                'by_program' => $donationsByProgram,
                'timeline' => $timeline,
            ],
            'details' => $donations->map(function ($donation) {
                return [
                    'pendonor' => $donation->pendonor->nama ?? '-',
                    'program' => $donation->program->nama ?? '-',
                    'program_year' => $donation->program?->tanggalmulai ? \Carbon\Carbon::parse($donation->program->tanggalmulai)->format('Y') : '-',
                    'nilaidonasi' => $donation->nilaidonasi,
                    'tanggal' => $donation->created_at?->format('d M Y') ?? '-',
                ];
            })
        ]);
    }

    public function exportDonation(Request $request)
    {
        $query = TrProgramPendonor::with(['pendonor', 'program']);

        // Apply filters
        if ($request->filled('year')) {
            $query->whereHas('program', function ($q) use ($request) {
                $q->whereYear('tanggalmulai', $request->year)
                    ->orWhereYear('tanggalselesai', $request->year);
            });
        }

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        if ($request->filled('pendonor_id')) {
            $query->where('pendonor_id', $request->pendonor_id);
        }

        $donations = $query->get();

        $filename = 'donasi_' . date('YmdHis') . '.xlsx';

        // Store temporarily then download to fix filename issue
        Excel::store(new DonationExport($donations), $filename, 'local');

        $path = storage_path('app/' . $filename);

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }


}
