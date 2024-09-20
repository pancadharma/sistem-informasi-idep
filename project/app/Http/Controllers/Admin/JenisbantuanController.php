<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Models\Jenis_Bantuan;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJenisbantuanRequest;
use App\Http\Requests\UpdateJenisbantuanRequest;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class JenisbantuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master.jenisbantuan.index');
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
    public function store(StoreJenisbantuanRequest $request)
    {
        // try {
            // $data = Jenis_Bantuan::create($request->all());

        //     $status = 'success';
        //     $message = 'Data ' . $request->nama . ' submitted successfully!';

        //     return response()->json(['status' => $status, 'message' => $message], 201); // Use 201 Created for successful creation

        // } catch (QueryException $e) {
        //     $status = 'error';

        //     // Handle specific MySQL error for numeric value out of range
        //     if ($e->getCode() === 22003) {
        //         $message = 'The provided code is too large for the database. Please enter a valid code within the allowed range.';
        //     } else {
        //         // Handle other database errors more generically
        //         $message = 'There was an error processing your data: ' . $e->getMessage();
        //     }

        //     return response()->json(['status' => $status, 'message' => $message], 400); // Use 400 Bad Request for validation errors
        // }
        
        // $request->validate([
        //     'nama' => ['required', 'string', 'max:200'],
        //     'aktif' => ['required'] 
        // ]);

        try {
            $data = $request->validated();
            Jenis_Bantuan::create($data);
            //$data = Jenis_Bantuan::create($request->all());
            
            return response()->json([
                "success"    => true,
                "message"   => __('cruds.data.data') .' '.__('cruds.jenisbantuan.title') .' '. $request->nama .' '. __('cruds.data.added'),
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
    public function show(Jenis_Bantuan $jenisbantuan)
    {
        return response()->json($jenisbantuan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jenis_Bantuan $jenisbantuan)
    {
        return response()->json($jenisbantuan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJenisbantuanRequest $request, Jenis_Bantuan $jenisbantuan)
    {
        // $request->validate([
        //     'nama' => ['required', 'string', 'max:200'],
        //     'aktif' => ['required'] 
        // ]);

        try {
            // Validate the request data
            $data = $request->validated();
            // Update the provinsi model with the validated data
            $jenisbantuan->update($data);
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

    public function datajenisbantuan(){
        $activeJenisbantuan = Jenis_Bantuan::get();
        // Prepare data for DataTables (without modifying original collection)
        $data = DataTables::of($activeJenisbantuan)
            ->addColumn('action', function ($jenisbantuan) {
                $editUrl = route('jenisbantuan.edit', $jenisbantuan->id);
                $viewUrl = route('jenisbantuan.show', $jenisbantuan->id);
                // return '<a href="'.$editUrl.'" class="btn btn-sm btn-info" title="'.__('global.edit') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-pencil-alt"></i></a> <a href="'.$viewUrl.'" class="btn btn-sm btn-primary" title="'.__('global.view') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-folder-open"></i></a>';
                //<button type="button" class="btn btn-sm btn-info edit-province-btn" data-province-id="{{ $province->id }}" title="'.__('global.edit') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-pencil-alt"></i></a>Edit</button>

                return '<button type="button" class="btn btn-sm btn-info edit-jenisbantuan-btn" data-action="edit" data-jenisbantuan-id="'. $jenisbantuan->id .'" title="'.__('global.edit') .' '. __('cruds.jenisbantuan.title') .' '. $jenisbantuan->nama .'"><i class="fas fa-pencil-alt"></i> Edit</button>
                <button type="button" class="btn btn-sm btn-primary view-jenisbantuan-btn" data-action="view" data-jenisbantuan-id="'. $jenisbantuan->id .'" value="'. $jenisbantuan->id .'" title="'.__('global.view') .' '. __('cruds.jenisbantuan.title') .' '. $jenisbantuan->nama .'"><i class="fas fa-folder-open"></i> View</button>';
            })
            ->make(true);
        return $data;
    }
}
