<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SatuanController extends Controller
{
    public function index(){

    }
    public function show(Satuan $satuan){
        return response()->json($satuan);
    }
    public function edit(Satuan $satuan){
        return response()->json($satuan);
    }
    public function create(){

    }
    public function store(){

    }
    public function update(){

    }
    public function getSatuan(Request $request){
        if ($request->ajax()) {
            $query = Satuan::all();
            $data = DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status', function($satuan) {
                    return match ($satuan->aktif) {
                        1 => '<div class="icheck-primary d-inline">
                                <input id="aktif_' . $satuan->id . '" data-aktif-id="' . $satuan->id . '" class="icheck-primary" alt="☑️ aktif" title="' . __("cruds.status.aktif") . '" type="checkbox" checked>
                                <label for="aktif_' . $satuan->id . '"></label>
                              </div>',
                        0 => '<div class="icheck-primary d-inline">
                                <input id="aktif_' . $satuan->id . '" data-aktif-id="' . $satuan->id . '" class="icheck-primary" alt="aktif" title="' . __("cruds.status.aktif") . '" type="checkbox">
                                <label for="aktif_' . $satuan->id . '"></label>
                              </div>',
                    };
                })
                ->addColumn('action', function($satuan) {
                    $editButton = '';
                    $viewButton = '';
                    $deleteButton = '';
                    if (auth()->user()->can("satuan_edit")) {
                        $editButton = "<button type=\"button\" class=\"btn btn-sm btn-info edit-satuan-btn\"
                        data-action=\"edit\" data-satuan-id=\"{$satuan->id}\"
                        title=\"" . __('global.edit') . " " . __('cruds.satuan.title') . " {$satuan->nama}\">
                        <i class=\"fas fa-pencil-alt\"></i> " . __('global.edit') . "</button>";
                    }
                    $viewButton = "<button type=\"button\" class=\"btn btn-sm btn-primary view-satuan-btn\"
                    data-action=\"view\" data-satuan-id=\"{$satuan->id}\"
                    title=\"" . __('global.view') . " " . __('cruds.satuan.title') . " {$satuan->nama}\">
                    <i class=\"fas fa-folder-open\"></i> " . __('global.view') . "</button>";
                    return "$editButton $viewButton";
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
            return $data;
        }
    }
}
