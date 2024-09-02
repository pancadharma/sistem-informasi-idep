<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dusun;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

class DusunController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('dusun_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $provinsi = Provinsi::pluck('nama', 'id')->prepend(trans('global.selectProv'), '');
        return view('master.dusun.index', compact('provinsi'));
    }

    public function create()
    {
        abort_if(Gate::denies('dusun_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('master.dusun.create');
    }

    public function store(Request $request)
    {
        // Code to store a new Dusun
    }

    public function show($dusun)
    {
        abort_if(Gate::denies('dusun_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('master.dusun.show', compact('id'));
    }

    public function edit($dusun)
    {
        abort_if(Gate::denies('dusun_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('master.dusun.edit', compact('id'));
    }

    public function update(Request $request, $dusun)
    {
        
    }

    public function destroy($dusun)
    {
        
    }


    function getDusun(Request $request) {
        if ($request->ajax()) {
            $query = Dusun::select('dusun.id', 'dusun.kode', 'dusun.nama', 'dusun.aktif', 'dusun.kode_pos' , 'dusun.desa_id')->with('desa:id,nama');
            $data = DataTables::of($query)
            ->addColumn('action', function ($dusun) {
                return '<button type="button" class="btn btn-sm btn-info edit-dusun-btn" data-action="edit"
                data-desa-id="'. $dusun->id .'" title="'.__('global.edit') .' '. __('cruds.dusun.title') .' '. $dusun->nama .'">
                <i class="fas fa-pencil-alt"></i> Edit</button>
                <button type="button" class="btn btn-sm btn-primary view-dusun-btn" data-action="view"
                data-desa-id="'. $dusun->id .'" value="'. $dusun->id .'" title="'.__('global.view') .' '. __('cruds.dusun.title') .' '. $dusun->nama .'">
                <i class="fas fa-folder-open"></i> View</button>';
            })
            ->make(true);
            return $data;
        }
    }
}
