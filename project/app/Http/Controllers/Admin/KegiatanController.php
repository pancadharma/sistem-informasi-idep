<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class KegiatanController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('kegiatan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.kegiatan.index');
    }
    public function list_kegiatan(Request $request)
    {
        // abort_if(Gate::denies('kegiatan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $kegiatan = Kegiatan::all();
            $data = DataTables::of($kegiatan)
                ->addIndexColumn()
                ->addColumn('action', function ($kegiatan) {
                    $editButton = '';
                    $viewButton = '';
                    $detailsButton = '';

                    if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_edit')) {
                        $editButton = '<button type="button" title="' . __('global.edit') . ' Kegiatan ' . $kegiatan->nama . '" class="btn btn-sm btn-info edit-kegiatan-btn" data-action="edit" data-kegiatan-id="' . $kegiatan->id . '" data-toggle="tooltip" data-placement="top"><i class="bi bi-pencil-square"></i><span class="d-none d-sm-inline"></span></button>';
                    }
                    if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_details_edit') || auth()->user()->can('kegiatan_edit')) {
                        $detailsButton = '<button type="button" title="' . __('global.details') . ' Kegiatan ' . $kegiatan->nama . '" class="btn btn-sm btn-danger details-kegiatan-btn" data-action="details" data-kegiatan-id="' . $kegiatan->id . '" data-toggle="tooltip" data-placement="top"><i class="bi bi-list-ul"></i><span class="d-none d-sm-inline"></span></button>';
                    }
                    if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_view') || auth()->user()->can('kegiatan_access')) {
                        $viewButton = '<button type="button" title="' . __('global.view') . ' Kegiatan ' . $kegiatan->nama . '" class="btn btn-sm btn-primary view-kegiatan-btn" data-action="view" data-kegiatan-id="' . $kegiatan->id . '" data-toggle="tooltip" data-placement="top"><i class="fas fa-folder-open"></i> <span class="d-none d-sm-inline"></span></button>';
                    }
                    return "<div class='button-container'>$editButton $viewButton $detailsButton</div>";
                })
                ->rawColumns(['action'])
                ->make(true);
            return $data;
        }
//         return view('tr.kegiatan.index');
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
