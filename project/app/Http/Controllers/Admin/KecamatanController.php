<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Provinsi;
// use App\Http\Requests\MassDestroyKecamatanRequest;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use PHPUnit\Framework\Exception;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreKecamatanRequest;
use App\Http\Requests\UpdateKecamatanRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;



class KecamatanController extends Controller
{
    public function index(){
        // abort_if(Gate::denies('kecamatan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $kabupaten = Kabupaten::all();
        $provinsi = Provinsi::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');      
        return view("master.kecamatan.index", compact('provinsi'));
    }

    public function store(StoreKecamatanRequest $request){
        // abort_if(Gate::denies('kecamatan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        try {
            $data = $request->validated();
            Kecamatan::create($data);
            
            return response()->json([
                "success"    => true,
                "message"   => __('cruds.data.data') .' '.__('cruds.kecamatan.title') .' '. $request->nama .' '. __('cruds.data.added'),
                "status"    => 201,
                "data"      => $data,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status"    => 400,
                "success"   => false,
                "message"   => $th,
                "data"      => $request->all(),
            ]);
        }catch (ValidationException $e) {
            $status = 'gagal';
            $message = 'Validation failed: ' . implode(', ', $e->errors());
            return response()->json(['status' => $status, 'message' => $message], 422); // Use 422 Unprocessable Entity for validation errors

        } catch (QueryException $e) {
            $status = 'error';
            if ($e->getCode() === '22003') {
                $message = 'The provided code is too large for the database. Please enter a valid code within the allowed range.';
            } else {
                $message = 'Database error: ' . $e->getMessage();
            }
            return response()->json(['status' => $status, 'message' => $message], 400); // Use 400 Bad Request for database errors

        } catch (Exception $e) {
            $status = 'error';
            $message = 'An unexpected error occurred: ' . $e->getMessage();
            return response()->json(['status' => $status, 'message' => $message], 500); // Use 500 Internal Server Error for general errors
        } catch (Exception $e) {
            $status = 'error';
            $message = 'An unexpected error occurred: ' . $e->getMessage(); 
            return response()->json(['status' => $status, 'message' => $message], 419); // Use 500 Internal Server Error for general errors
        }
    }
    public function update(UpdateKecamatanRequest $request, Kecamatan $kecamatan){
        // abort_if(Gate::denies('kecamatan_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    }
    public function show(Kecamatan $kecamatan){

    }

    public function edit(Kecamatan $kecamatan){

    }

    public function provinsi(){
        $provinsi = Provinsi::withActive()->get(['id','kode','nama']);
        return response()->json($provinsi);

    }
    public function provinsi_details(Provinsi $provinsi){
        $provinsi = Provinsi::with('kabupaten_kota:provinsi_id,id,kode,nama,type')
        ->where('id', $provinsi->id)
        ->get(['id','kode','nama']);
        return response()->json($provinsi);

    }
    public function kab(Request $request){
        $kabupaten = Kabupaten::where("provinsi_id", $request->id)->get(['id', 'kode', 'nama']);
        $kab = $kabupaten->map(function($item) {
            return [
                'id'        => $item->id,
                'text'      => $item->kode . ' - ' . $item->nama,
                'kode'      => $item->kode,
            ];
        });
        return response()->json($kab);
    }

    public function kab_details(Kabupaten $kabupaten)
    {
        $kabupaten = Kabupaten::with('kecamatan:kabupaten_id,id,kode,nama')
            ->where('id', $kabupaten->id)
            ->get(['id','kode','nama']);
        return response()->json($kabupaten);
    }
    
    public function kec(Kabupaten $kabupaten)
    {
        $kabupaten = Kecamatan::with('kabupaten')->where('kabupaten_id', $kabupaten->id)
            ->where('aktif', 1)
            ->get(['id', 'nama']);
        return response()->json($kabupaten);
    }

    public function datakecamatan(){
        $kecamatan = Kecamatan::where('aktif', 1)
            ->with('kabupaten:id,nama,provinsi_id', 'kabupaten.provinsi:nama')
            ->get();

        $data = DataTables::of($kecamatan)
            ->addColumn('action', function ($kecamatan) {
                return '<button type="button" class="btn btn-sm btn-info edit-kec-btn" data-action="edit" data-kecamatan-id="'. $kecamatan->id .'" title="'.__('global.edit') .' '. __('cruds.kecamatan.title') .' '. $kecamatan->nama .'"><i class="fas fa-pencil-alt"></i> Edit</button>

                <button type="button" class="btn btn-sm btn-primary view-kec-btn" data-action="view" data-kecamatan-id="'. $kecamatan->id .'" value="'. $kecamatan->id .'" title="'.__('global.view') .' '. __('cruds.kecamatan.title') .' '. $kecamatan->nama .'"><i class="fas fa-folder-open"></i> View</button>';
            })
            ->make(true);
        return $data;
    }
}
