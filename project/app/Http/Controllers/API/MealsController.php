<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dusun;
use App\Models\Kegiatan;
use App\Models\Kelurahan;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class MealsController extends Controller
{
    public function getMealsDatatable(Request $request)
    {
        // if (!$request->ajax() && !$request->isJson()) {
        //     return "Not an Ajax Request & JSON REQUEST";
        // }

        $kegiatan = Kegiatan::with('dusun', 'users', 'kategori_lokasi', 'activity.program_outcome_output.program_outcome.program', 'satuan', 'jenis_bantuan')
            ->select('trkegiatan.*')
            ->get()
            ->map(function ($item) {
                $item->duration_in_days = $item->getDurationInDays();
                $item->tanggalmulai = Carbon::parse($item->tanggalmulai)->format('d-m-Y');
                $item->tanggalselesai = Carbon::parse($item->tanggalselesai)->format('d-m-Y');
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


    public function getPrograms(Request $request)
    {
        if ($request->ajax()) {
            $query = Program::query();

            return DataTables::of($query)
                ->addColumn('action', function($row) {
                    return '<button type="button" class="btn btn-sm btn-danger select-program" data-id="' . $row->id . '" data-kode="' . $row->kode . '" data-nama="' . $row->nama . '">
                    <i class="bi bi-plus"></i> </button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getDesa(Request $request)
    {
        $search = $request->search;
        $page = $request->page ?? 1;
        $perPage = 100;

        $cacheKey = "desas_search_{$search}_page_{$page}";

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($search, $page, $perPage) {
            $query = Kelurahan::query();

            if ($search) {
                $query->where('nama', 'like', "%{$search}%");
            }

            $total = $query->count();

            $desas = $query->select('id', 'nama as text')
                           ->orderBy('nama')
                           ->skip(($page - 1) * $perPage)
                           ->take($perPage)
                           ->get();

            return [
                'data' => $desas,
                'total' => $total,
            ];
        });
    }

    public function getDusuns(Request $request)
    {
        $search = $request->search;
        $desaId = $request->desa_id;
        $page = $request->page ?? 1;
        $perPage = 100;

        $cacheKey = "dusuns_desa_{$desaId}_search_{$search}_page_{$page}";

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($search, $desaId, $page, $perPage) {
            $query = Dusun::where('desa_id', $desaId);

            if ($search) {
                $query->where('nama', 'like', "%{$search}%");
            }

            $total = $query->count();

            $dusuns = $query->select('id', 'nama as text')
                            ->orderBy('nama')
                            ->skip(($page - 1) * $perPage)
                            ->take($perPage)
                            ->get();

            return [
                'data' => $dusuns,
                'total' => $total,
            ];
        });
    }
}
