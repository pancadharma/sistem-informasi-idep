<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Program;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use App\Models\Program_Outcome;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Program_Outcome_Output;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Program_Outcome_Output_Activity;
use App\Models\Satuan;

class KegiatanController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('kegiatan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.kegiatan.index');
    }
    public function list_kegiatan(Request $request)
    {
        if (!$request->ajax() && !$request->isJson()) {
            return "Not an Ajax Request & JSON REQUEST";
        }

        $kegiatan = Kegiatan::with('dusun', 'users', 'kategori_lokasi', 'activity.program_outcome_output.program_outcome.program', 'satuan', 'jenis_bantuan')
            ->select('trkegiatan.*')
            ->get()
            ->map(function ($item) {
                // Calculate duration before formatting
                $item->duration_in_days = $item->getDurationInDays();

                // Format dates after calculating duration
                $item->tanggalmulai = Carbon::parse($item->tanggalmulai)->format('d-m-Y');
                $item->tanggalselesai = Carbon::parse($item->tanggalselesai)->format('d-m-Y');

                // Add calculated values
                $program = $item->activity->program_outcome_output->program_outcome->program;
                $item->total_beneficiaries = $program->getTotalBeneficiaries();

                return $item;
            });

        $data = DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('program_name', function ($kegiatan) {
                return $kegiatan->activity->program_outcome_output->program_outcome->program->nama ?? 'N/A';
            })
            ->addColumn('duration_in_days', function ($kegiatan) {
                return $kegiatan->duration_in_days . ' ' . __('cruds.kegiatan.days')  ?? 'N/A';
            })
            ->addColumn('action', function ($kegiatan) {
                $buttons = [];

                if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_edit')) {
                    $buttons[] = $this->generateButton('edit', 'info', 'pencil-square', __('global.edit') . __('cruds.kegiatan.label') . $kegiatan->nama, $kegiatan->id);
                }
                if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_view') || auth()->user()->can('kegiatan_access')) {
                    $buttons[] = $this->generateButton('view', 'primary', 'folder2-open', __('global.view') . __('cruds.kegiatan.label') . $kegiatan->nama, $kegiatan->id);
                }
                if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_details_edit') || auth()->user()->can('kegiatan_edit')) {
                    $buttons[] = $this->generateButton('details', 'danger', 'list-ul', __('global.details') . __('cruds.kegiatan.label') . $kegiatan->nama, $kegiatan->id);
                }
                return "<div class='button-container'>" . implode(' ', $buttons) . "</div>";
            })
            ->rawColumns(['action'])
            ->make(true);

        return $data;
    }


    private function generateButton($action, $class, $icon, $title, $kegiatanId)
    {
        return '<button type="button" title="' . $title . '" class="btn btn-sm btn-' . $class . ' ' . $action . '-kegiatan-btn" data-action="' . $action . '"
                data-kegiatan-id="' . $kegiatanId . '" data-toggle="tooltip" data-placement="top">
                    <i class="bi bi-' . $icon . '"></i>
                    <span class="d-none d-sm-inline"></span>
                </button>';
    }

    public function create()
    {
        if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_edit') || auth()->user()->can('kegiatan_create')) {
            $program = Program::all();
            return view('tr.kegiatan.create', compact('program'));
        }
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => 'Unauthorized Permission. Please ask your administrator to assign permissions to access details of this Page',
        ], Response::HTTP_FORBIDDEN);
    }

    public function store(Request $request)
    {
        return view('tr.kegiatan.create');
    }

    public function show($id)
    {
        return view('tr.kegiatan.show');
    }

    public function edit($id)
    {
        return view('tr.kegiatan.edit');
    }

    public function update(Request $request, $id)
    {
        return view('tr.kegiatan.edit');
    }

    public function destroy($id)
    {
        return false;
    }


    // public function getActivityProgram($programId)
    // {
    //     // Fetch activities using relationships
    //     $activities = Program::with('outcome.output.activities')
    //         ->where('id', $programId)
    //         ->get()
    //         ->pluck('outcome.*.output.*.activities')
    //         ->flatten();

    //     return response()->json($activities);
    // }

    public function getActivityProgram($programId)
    {
        $program = Program::with([
            'outcome.output.activities' => function ($query) {
                $query->select('id', 'deskripsi', 'indikator', 'target', 'programoutcomeoutput_id');
            }
        ])->where('id', $programId)->first();

        // $program = Program::find($programId);

        if (!$program) {
            return response()->json(['message' => 'Program not found'], 404);
        }

        $activities = [];
        if ($program) {
            foreach ($program->outcome as $out) {
                foreach ($out->output as $come_output) {
                    foreach ($come_output->activities as $activity) {
                        $activities[] = $activity;
                    }
                }
            }
        }

        return response()->json($activities);
    }


    public function getSatuan(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'id' => 'nullable|integer', // Add id validation
        ]);

        // Retrieve search, page, and id inputs
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $id = $request->input('id', null);

        // Build query to include both name search and id check
        $satuan = Satuan::when($id, function ($query, $id) {
            return $query->where('id', $id);
        }, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%");
        });
        $satuan = $satuan->paginate(20, ['*'], 'page', $page);
        return response()->json($satuan);
    }
}