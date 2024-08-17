<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Provinsi;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
// use Yajra\DataTables\DataTables;
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
                data-kecamatan-id="'. $desa->id .'" title="'.__('global.edit') .' '. __('cruds.kecamatan.title') .' '. $desa->nama .'">
                <i class="fas fa-pencil-alt"></i> Edit</button>              
                <button type="button" class="btn btn-sm btn-primary view-kec-btn" data-action="view"
                data-kecamatan-id="'. $desa->id .'" value="'. $desa->id .'" title="'.__('global.view') .' '. __('cruds.kecamatan.title') .' '. $desa->nama .'">
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
    
}
