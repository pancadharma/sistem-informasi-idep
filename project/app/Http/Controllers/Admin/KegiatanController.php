<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;


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
            return view('tr.kegiatan.create');
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
}
