<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mjabatan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class MjabatanController extends Controller
{
    public function index()
    {
        $mjabatans = Mjabatan::all();
        return view('master.mjabatan.index', compact('mjabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:200',
            'aktif' => 'required|boolean',
        ]);

        try {
            $data = $request->all();
            Mjabatan::create($data);
            
            return response()->json([
                "success" => true,
                "message" => 'Jabatan added successfully',
                "status" => 201,
                "data" => $data,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => $e->errors()], 422);
        } catch (QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Mjabatan $mjabatan)
    {
        $request->validate([
            'nama' => 'required|string|max:200',
            'aktif' => 'required|boolean',
        ]);

        try {
            $mjabatan->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Jabatan updated successfully',
                'data' => $mjabatan,
                'response' => Response::HTTP_ACCEPTED,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Mjabatan $mjabatan)
    {
        return response()->json($mjabatan);
    }

    public function edit(Mjabatan $mjabatan)
    {
        return response()->json($mjabatan);
    }

    public function destroy(Mjabatan $mjabatan)
    {
        try {
            $mjabatan->delete();
            return response()->json([
                'success' => true,
                'message' => 'Jabatan deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getData()
    {
        $mjabatans = Mjabatan::get();
        $data = DataTables::of($mjabatans)
            ->addColumn('action', function ($mjabatan) {
                return '<button type="button" class="btn btn-sm btn-info edit-mjabatan-btn" data-action="edit" data-mjabatan-id="' . $mjabatan->id . '"><i class="fas fa-pencil-alt"></i><span class="d-none d-sm-inline">' . trans('global.edit') . '</span></button>
                        <button type="button" class="btn btn-sm btn-primary view-mjabatan-btn" data-action="view" data-mjabatan-id="' . $mjabatan->id . '"><i class="fas fa-folder-open"></i> <span class="d-none d-sm-inline">' . trans('global.view') . '</span></button>';
            })
            ->make(true);
        return $data;
    }
}