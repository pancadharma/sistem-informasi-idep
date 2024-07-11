<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProvinsiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // abort_if(Gate::denies('provinsi_access'), Response::HTTP_FORBIDDEN, '403 Forbidden'); //Uncomment to apply permission provinsi_access index
        $province = Provinsi::where('aktif', 1)->get();
        // return $province;
        return view('master.provinsi.index', compact('province'));
    }

    public function dataprovinsi(){

        $activeProvinsi = Provinsi::withActive()->get();

        // Prepare data for DataTables (without modifying original collection)
        $data = DataTables::of($activeProvinsi)
            ->addColumn('action', function ($provinsi) {
                $editUrl = route('provinsi.edit', $provinsi->id);
                $viewUrl = route('provinsi.show', $provinsi->id);

                return '<a href="'.$editUrl.'" class="btn btn-sm btn-info" title="'.__('global.edit') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-pencil-alt"></i></a> <a href="'.$viewUrl.'" class="btn btn-sm btn-primary" title="'.__('global.view') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-folder-open"></i></a>';
            })
            ->make(true);

        return $data;


        // $activeProvinsi = Provinsi::withActive()->get();
        // $data = DataTables::of($activeProvinsi)
        // ->make(true);

        // foreach($activeProvinsi as $item){
        //     $item['action'] = '<a href="'.route('provinsi.edit', $item['id']).'" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>
        //                        <a href="'.route('provinsi.show', $item['id']).'" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i> View</a>';
        // }

        // return $data;

        // return response()->json($activeProvinsi);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Provinsi $provinsi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Provinsi $provinsi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Provinsi $provinsi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provinsi $provinsi)
    {
        //
    }
}
