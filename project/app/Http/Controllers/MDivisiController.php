<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MDivisi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;

class MDivisiController extends Controller
{
    public function index()
    {
        return view('master.mdivisi.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'  => 'required|string|max:200',
            'aktif' => 'required|boolean',
        ]);

        $divisi = MDivisi::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Divisi berhasil ditambahkan',
            'data'    => $divisi,
        ], Response::HTTP_CREATED);
    }

    public function edit(MDivisi $mdivisi)
    {
        return response()->json($mdivisi);
    }

    public function update(Request $request, MDivisi $mdivisi)
    {
        $validated = $request->validate([
            'nama'  => 'required|string|max:200',
            'aktif' => 'required|boolean',
        ]);

        $mdivisi->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Divisi berhasil diperbarui',
        ], Response::HTTP_ACCEPTED);
    }

    public function show(MDivisi $mdivisi)
    {
        return response()->json($mdivisi);
    }

    public function destroy(MDivisi $mdivisi)
    {
        $mdivisi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Divisi berhasil dihapus',
        ]);
    }

    public function getData()
{
    $divisi = MDivisi::query();

    return DataTables::of($divisi)
        ->addColumn('action', function ($divisi) {
            return '
                <button class="btn btn-sm btn-info edit-mdivisi-btn" data-mdivisi-id="'.$divisi->id.'">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <button class="btn btn-sm btn-primary view-mdivisi-btn" data-mdivisi-id="'.$divisi->id.'">
                    <i class="fas fa-eye"></i>
                </button>
            ';
        })
        ->rawColumns(['action'])
        ->make(true);
}
}