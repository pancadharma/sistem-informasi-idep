<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\MPendonor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Kategori_Pendonor;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\StoreMpendonorRequest;
use App\Http\Requests\UpdateMpendonorRequest;
use Illuminate\Validation\ValidationException;

class MPendonorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master.mpendonor.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mpendonorkategori_id = Kategori_Pendonor::withActive()->get(['id', 'nama']);
        return response()->json($mpendonorkategori_id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMpendonorRequest $request)
    {
        
        try {
            $data = $request->validated();
            MPendonor::create($data);
            
            return response()->json([
                "success"    => true,
                "message"   => __('cruds.data.data') .' '.__('cruds.mpendonor.title') .' '. $request->nama .' '. __('cruds.data.added'),
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
            $status = 'error';
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

    /**
     * Display the specified resource.
     */
    public function show(MPendonor $pendonor)
    {
        //dump($mpendonor);
        $pendonor->load('mpendonnorkategori');
        //dd($mpendonor);
        return response()->json($pendonor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MPendonor $pendonor)
    {
        $mpendonorkategori_id = Kategori_Pendonor::withActive()->get(['id', 'nama']);
        $pendonor->load('mpendonnorkategori');
        return [$pendonor, "results" => $mpendonorkategori_id];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMpendonorRequest $request, MPendonor $pendonor)
    {
        $data = $request->all();
        //dd($data);
        try {
            $data = $request->validated();
            $pendonor->update($data);
            
            return response()->json([
                'status'    => 'success',
                // 'message'   => "Data ". $request->nama ." Updated Successfully",
                "message"   => __('cruds.data.data') .' '.__('cruds.mpendonor.title') .' '. $request->nama .' '. __('cruds.data.updated'),
                'data'      => $pendonor,
            ],201);

        } catch (Exception $e) {
            $status = 'error';
            $message = $e->getMessage();
            return response()->json([
                'status'    => $status,
                'message'   => $message,
                'data'      => $data,
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // public function datapendonor(){
    //     $mpendonor = new MPendonor();
    //     $data = $mpendonor->datapendonor();
    //     return $data;
    // }

    public function datapendonor(){
        $pendonor = MPendonor::with('mpendonnorkategori')->get();
        // Prepare data for DataTables (without modifying original collection)
        
        $data = DataTables::of($pendonor)
        
            ->addColumn('action', function ($mpendonor) {
                $editUrl = route('pendonor.edit', $mpendonor->id);
                $viewUrl = route('pendonor.show', $mpendonor->id);
                // return '<a href="'.$editUrl.'" class="btn btn-sm btn-info" title="'.__('global.edit') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-pencil-alt"></i></a> <a href="'.$viewUrl.'" class="btn btn-sm btn-primary" title="'.__('global.view') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-folder-open"></i></a>';
                //<button type="button" class="btn btn-sm btn-info edit-province-btn" data-province-id="{{ $province->id }}" title="'.__('global.edit') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-pencil-alt"></i></a>Edit</button>

                return '<button type="button" class="btn btn-sm btn-info edit-mpendonor-btn" data-action="edit" data-mpendonor-id="'. $mpendonor->id .'" title="'.__('global.edit') .' '. __('cruds.mpendonor.title') .' '. $mpendonor->nama .'"><i class="fas fa-pencil-alt"></i> Edit</button>
                <button type="button" class="btn btn-sm btn-primary view-mpendonor-btn" data-action="view" data-mpendonor-id="'. $mpendonor->id .'" value="'. $mpendonor->id .'" title="'.__('global.view') .' '. __('cruds.mpendonor.title') .' '. $mpendonor->nama .'"><i class="fas fa-folder-open"></i> View</button>';
            })
            ->make(true);
        return $data;
    }
   
}
