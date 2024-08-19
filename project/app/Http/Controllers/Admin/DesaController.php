<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreDesaRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationData;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DesaController extends Controller
{
    function index() {
        abort_if(Gate::denies('desa_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $provinsi = Provinsi::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('master.desa.index', compact('provinsi'));
    }

    function store(StoreDesaRequest $request){
        try {
            $data = $request->validated();
            Kelurahan::create($data);
            return response()->json([
                'success'   => true,
                'message'   =>  __('cruds.data.data') .' '.__('cruds.desa.title') .' '. $request->nama .' '. __('cruds.data.added'),
                'status'    => Response::HTTP_CREATED,
                'data'      => $data,
            ]);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
                'data'  => $request,
            ], 422);
    
        } catch (ModelNotFoundException $e) {
            // Handle model not found errors
            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
                'data'  => $request,
            ], 404);
    
        } catch (HttpException $e) {
            // Handle HTTP-specific exceptions
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'  => $request,
            ], $e->getStatusCode());
    
        } catch (Exception $e) {
            // Handle all other exceptions
            return response()->json([
                'success' => false,
                'data'  => $request,
                'message' => 'An error occurred.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    function getDesa(Request $request) {
        if ($request->ajax()) {
            $query = Kelurahan::select('kelurahan.id', 'kelurahan.kode', 'kelurahan.nama', 'kelurahan.aktif', 'kelurahan.kecamatan_id')->with('kecamatan:id,nama');
            $data = DataTables::of($query)
            ->addColumn('action', function ($desa) {
                return '<button type="button" class="btn btn-sm btn-info edit-kec-btn" data-action="edit" 
                data-desa-id="'. $desa->id .'" title="'.__('global.edit') .' '. __('cruds.desa.title') .' '. $desa->nama .'">
                <i class="fas fa-pencil-alt"></i> Edit</button>              
                <button type="button" class="btn btn-sm btn-primary view-kec-btn" data-action="view"
                data-desa-id="'. $desa->id .'" value="'. $desa->id .'" title="'.__('global.view') .' '. __('cruds.desa.title') .' '. $desa->nama .'">
                <i class="fas fa-folder-open"></i> View</button>';
            })
            ->make(true);
            return $data;            
        }
    }

    function getKecamatan(Request $request){
        $kecamatan = Kecamatan::where('kabupaten_id', $request->id)
        ->get(['id', 'kode', 'nama'])
        ->map(function ($item) {
            return [
                'id'   => $item->id,
                'kode' => $item->kode,
                'text' => "{$item->kode} - {$item->nama}",
            ];
        });
        return response()->json($kecamatan);
    }

    public function show(Kelurahan $desa){
        $desa->load('kecamatan');
        return response()->json($desa); // Return province data as JSON
    }
    public function edit(Kelurahan $desa){
        // abort_if(Gate::denies('kecamatan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $provinsi = Provinsi::all(['id', 'kode', 'nama']);
        $kabupaten = Kabupaten::where('provinsi_id', $desa->kecamatan->kabupaten->provinsi_id)->get(['id', 'kode', 'nama']);
        $kecamatan = Kecamatan::where('kabupaten_id', $desa->kecamatan->kabupaten->id)->get(['id', 'kode', 'nama']);
        $desa->load('kecamatan');
        $kecamatan->load('kabupaten');
        return response()->json([
            'desa'       => $desa,
            'kecamatan'  => $kecamatan,
            'kabupaten'  => $kabupaten,
            'provinsi'   => $provinsi
        ]);

        // return response()->json($desa); // Return province data as JSON
    }
    public function update(Request $request, Kelurahan $desa){
        // abort_if(Gate::denies('kecamatan_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }
    
}
