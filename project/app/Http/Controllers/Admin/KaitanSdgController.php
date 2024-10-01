<?php

namespace App\Http\Controllers\Admin;

use App\Models\KaitanSdg;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class KaitanSdgController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kaitansdgs = KaitanSdg::all();
        return view('master.kaitan_sdg.index', compact('kaitansdgs'));
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
            KaitanSdg::create($data);
            
            return response()->json([
                "success" => true,
                "message" => 'Kaitan SDG added successfully',
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
     * Update the specified resource in storage.
     */
    // public function update(Request $request, KaitanSdg $kaitanSdg)
    // {
    //     $request->validate([
    //         'nama' => 'required|string|max:200',
    //         'aktif' => 'required|boolean',
    //     ]);

    //     try {
    //         $kaitanSdg->update($request->all());
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Kaitan SDG updated successfully',
    //             'data' => $kaitanSdg,
    //             'response' => Response::HTTP_ACCEPTED,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    //     }

        
    // }

    public function update(Request $request, KaitanSdg $kaitanSdg)
    {
        $request->validate([
            'nama' => 'required|string|max:200',
            'aktif' => 'required|boolean',
        ]);

        try {
            $kaitanSdg->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Kaitan SDG updated successfully',
                'data' => $kaitanSdg,  // Return the updated data
                'response' => Response::HTTP_ACCEPTED,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(KaitanSdg $kaitan_sdg)
    {
        return response()->json($kaitan_sdg);
    }

    public function edit(KaitanSdg $kaitan_sdg)
    {
        return response()->json($kaitan_sdg);
    }

    public function destroy(KaitanSdg $kaitansdg)
    {
        try {
            $kaitansdg->delete();
            return response()->json([
                'success' => true,
                'message' => 'Kaitan SDG deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getData()
    {
        $kaitansdgs = KaitanSdg::get();
        $data = DataTables::of($kaitansdgs)
            ->addColumn('action', function ($kaitansdg) {
                return '<button type="button" class="btn btn-sm btn-info edit-kaitansdg-btn" data-action="edit" data-kaitansdg-id="' . $kaitansdg->id . '"><i class="fas fa-pencil-alt"></i><span class="d-none d-sm-inline">' . trans('global.edit') . '</span></button>
                        <button type="button" class="btn btn-sm btn-primary view-kaitansdg-btn" data-action="view" data-kaitansdg-id="' . $kaitansdg->id . '"><i class="fas fa-folder-open"></i> <span class="d-none d-sm-inline">' . trans('global.view') . '</span></button>';
            })
            ->make(true);
        return $data;
    }

}
