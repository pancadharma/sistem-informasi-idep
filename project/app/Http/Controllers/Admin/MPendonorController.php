<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\MPendonor;
use Illuminate\Http\Request;
use App\Models\Kategori_Pendonor;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\StoreMpendonorRequest;
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
