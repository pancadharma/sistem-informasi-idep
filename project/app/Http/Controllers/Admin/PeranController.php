<?php

namespace App\Http\Controllers\Admin;

use App\Models\Peran;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class PeranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perans = Peran::all(); // Fetch all Peran records from the database
        return view('master.peran.index', compact('perans')); // Return the view with the data
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
        $request->validate([
            'nama' => 'required|string|max:200',
            'aktif' => 'required|boolean',
        ]);

        try {
            $data = $request->all();
            Peran::create($data);
            
            return response()->json([
                "success" => true,
                "message" => 'Peran added successfully',
                "status" => 201,
                "data" => $data,
            ]);
        } 
        catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => $e->errors()], 422);
        } 
        catch (QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()], 400);
        } 
        catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Peran $peran)
    {
        return response()->json($peran);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peran $peran)
    {
        return response()->json($peran);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peran $peran)
    {
        $request->validate([
            'nama' => 'required|string|max:200',
            'aktif' => 'required|boolean',
        ]);

        try {
            $peran->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Peran updated successfully',
                'data' => $peran,
                'response' => Response::HTTP_ACCEPTED,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getData()
    {
        $perans = Peran::get();
        $data = DataTables::of($perans)
            ->addColumn('action', function ($peran) {
                return '<button type="button" class="btn btn-sm btn-info edit-peran-btn" data-action="edit" data-peran-id="' . $peran->id . '"><i class="fas fa-pencil-alt"></i><span class="d-none d-sm-inline">' . trans('global.edit') . '</span></button>
                        <button type="button" class="btn btn-sm btn-primary view-peran-btn" data-action="view" data-peran-id="' . $peran->id . '"><i class="fas fa-folder-open"></i> <span class="d-none d-sm-inline">' . trans('global.view') . '</span></button>';
            })
            ->make(true);
        return $data;
    }
}
