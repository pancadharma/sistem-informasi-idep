<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Kategori_Pendonor;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreKategoripendonorRequest;
use App\Http\Requests\UpdateKategoripendonorRequest;

class KategoripendonorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master.kategoripendonor.index');
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
    public function store(StoreKategoripendonorRequest $request)
    {
        try {
            $data = $request->validated();
            Kategori_Pendonor::create($data);
            
            return response()->json([
                "success"    => true,
                "message"   => __('cruds.data.data') .' '.__('cruds.kategoripendonor.title') .' '. $request->nama .' '. __('cruds.data.added'),
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
    public function show(Kategori_Pendonor $kategoripendonor)
    {
        return response()->json($kategoripendonor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori_Pendonor $kategoripendonor)
    {
        return response()->json($kategoripendonor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKategoripendonorRequest $request, Kategori_Pendonor $kategoripendonor)
    {
        try {
            // Validate the request data
            $data = $request->validated();
            // Update the provinsi model with the validated data
            $kategoripendonor->update($data);
            $status = "success";
            $message = "Data " . $request->nama . " was updated successfully!";
            return response()->json(['status' => $status, 'message' => $message, 'data' =>$data], 200); // Use 200 OK for successful updates

        } catch (ValidationException $e) {
            $status = 'error';
            $message = 'Validation failed: ' . implode(', ', $e->errors());
            return response()->json(['status' => $status, 'message' => $message], 422); // Use 422 Unprocessable Entity for validation errors

        } catch (QueryException $e) {
            // Handle specific database errors
            $status = 'error';
            if ($e->getCode() === '22003') {
                // MySQL numeric value out of range
                $message = 'The provided code is too large for the database. Please enter a valid code within the allowed range.';
            } else {
                // Other database errors
                $message = 'Database error: ' . $e->getMessage();
            }
            return response()->json(['status' => $status, 'message' => $message], 400); // Use 400 Bad Request for database errors

        } catch (Exception $e) {
            // Handle any other exceptions
            $status = 'error';
            $message = 'An unexpected error occurred: ' . $e->getMessage();
            return response()->json(['status' => $status, 'message' => $message], 500); // Use 500 Internal Server Error for general errors
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function datakategoripendonor(){
        $activekategoripendonor = Kategori_Pendonor::get();
        // Prepare data for DataTables (without modifying original collection)
        $data = DataTables::of($activekategoripendonor)
            ->addColumn('action', function ($kategoripendonor) {
                $editUrl = route('kategoripendonor.edit', $kategoripendonor->id);
                $viewUrl = route('kategoripendonor.show', $kategoripendonor->id);
                // return '<a href="'.$editUrl.'" class="btn btn-sm btn-info" title="'.__('global.edit') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-pencil-alt"></i></a> <a href="'.$viewUrl.'" class="btn btn-sm btn-primary" title="'.__('global.view') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-folder-open"></i></a>';
                //<button type="button" class="btn btn-sm btn-info edit-province-btn" data-province-id="{{ $province->id }}" title="'.__('global.edit') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-pencil-alt"></i></a>Edit</button>

                return '<button type="button" class="btn btn-sm btn-info edit-kategoripendonor-btn" data-action="edit" data-kategoripendonor-id="'. $kategoripendonor->id .'" title="'.__('global.edit') .' '. __('cruds.kategoripendonor.title') .' '. $kategoripendonor->nama .'"><i class="fas fa-pencil-alt"></i> Edit</button>
                <button type="button" class="btn btn-sm btn-primary view-kategoripendonor-btn" data-action="view" data-kategoripendonor-id="'. $kategoripendonor->id .'" value="'. $kategoripendonor->id .'" title="'.__('global.view') .' '. __('cruds.kategoripendonor.title') .' '. $kategoripendonor->nama .'"><i class="fas fa-folder-open"></i> View</button>';
            })
            ->make(true);
        return $data;
    }

}
