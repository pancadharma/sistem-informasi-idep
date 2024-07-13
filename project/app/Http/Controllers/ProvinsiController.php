<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\StoreProvinsiRequest;





class ProvinsiController extends Controller
{

    public function index()
    {
        // abort_if(Gate::denies('provinsi_access'), Response::HTTP_FORBIDDEN, '403 Forbidden'); //Uncomment to apply permission provinsi_access index
        $province = Provinsi::where('aktif', 1)->get();
        return view('master.provinsi.index', compact('province'));
    }

    public function dataprovinsi(){
        $activeProvinsi = Provinsi::withActive()->get();
        // Prepare data for DataTables (without modifying original collection)
        $data = DataTables::of($activeProvinsi)
            ->addColumn('action', function ($provinsi) {
                $editUrl = route('provinsi.edit', $provinsi->id);
                $viewUrl = route('provinsi.show', $provinsi->id);

                // return '<a href="'.$editUrl.'" class="btn btn-sm btn-info" title="'.__('global.edit') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-pencil-alt"></i></a> <a href="'.$viewUrl.'" class="btn btn-sm btn-primary" title="'.__('global.view') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-folder-open"></i></a>';
                //<button type="button" class="btn btn-sm btn-info edit-province-btn" data-province-id="{{ $province->id }}" title="'.__('global.edit') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-pencil-alt"></i></a>Edit</button>

                return '<button type="button" class="btn btn-sm btn-info edit-province-btn" data-action="edit" data-province-id="'. $provinsi->id .'" title="'.__('global.edit') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-pencil-alt"></i> Edit</button>
                <button type="button" class="btn btn-sm btn-primary view-province-btn" data-action="view" data-province-id="'. $provinsi->id .'" value="'. $provinsi->id .'" title="'.__('global.view') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-folder-open"></i> View</button>';

            })
            ->make(true);
        return $data;
    }

    public function create()
    {
        //
    }

    public function store(StoreProvinsiRequest $request)
    {
        $provinsi = Provinsi::create($request->all());

        $status = 'success';
        $message = 'Data '. $request->nama.' submitted successfully!';
        if (!$status) {
            $message = 'There was an error processing your data.';
        }
        return response()->json(['status' => $status, 'message' => $message], ($status === 'success') ? 200 : 400); // Adjust codes as needed
    }

    public function show(Provinsi $provinsi)
    {
        //        $provinsi = Provinsi::find($provinsi);
        //        return response()->json($provinsi);
        return response()->json($provinsi); // Return province data as JSON
        //        return(view('master.provinsi.show', compact('provinsi')));
    }

    public function edit(Provinsi $provinsi)
    {
        return response()->json($provinsi); // Return province data as JSON
    }

    public function update(Request $request, Provinsi $provinsi)
    {
        $provinsi->update($request->all());
        return response()->json(['message' => 'Province updated successfully!']); // Success message
    }

    public function destroy(Provinsi $provinsi)
    {
        //
    }
}
