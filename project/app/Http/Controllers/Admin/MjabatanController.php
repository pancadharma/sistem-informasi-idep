<?php

namespace App\Http\Controllers\Admin;

use App\Models\MDivisi;
use App\Models\Mjabatan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

// class MjabatanController extends Controller
// {
//     public function index()
//     {
//         $mjabatans = Mjabatan::all();
//         return view('master.mjabatan.index', compact('mjabatans'));
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'nama' => 'required|string|max:200',
//             'aktif' => 'required|boolean',
//         ]);

//         try {
//             $data = $request->all();
//             Mjabatan::create($data);
            
//             return response()->json([
//                 "success" => true,
//                 "message" => 'Jabatan added successfully',
//                 "status" => 201,
//                 "data" => $data,
//             ]);
//         } catch (ValidationException $e) {
//             return response()->json(['status' => 'error', 'message' => $e->errors()], 422);
//         } catch (QueryException $e) {
//             return response()->json(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()], 400);
//         } catch (\Exception $e) {
//             return response()->json(['status' => 'error', 'message' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
//         }
//     }

//     public function update(Request $request, Mjabatan $mjabatan)
//     {
//         $request->validate([
//             'nama' => 'required|string|max:200',
//             'aktif' => 'required|boolean',
//         ]);

//         try {
//             $mjabatan->update($request->all());
//             return response()->json([
//                 'success' => true,
//                 'message' => 'Jabatan updated successfully',
//                 'data' => $mjabatan,
//                 'response' => Response::HTTP_ACCEPTED,
//             ]);
//         } catch (\Exception $e) {
//             return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
//         }
//     }

//     public function show(Mjabatan $mjabatan)
//     {
//         return response()->json($mjabatan);
//     }

//     public function edit(Mjabatan $mjabatan)
//     {
//         return response()->json($mjabatan);
//     }

//     public function destroy(Mjabatan $mjabatan)
//     {
//         try {
//             $mjabatan->delete();
//             return response()->json([
//                 'success' => true,
//                 'message' => 'Jabatan deleted successfully',
//             ]);
//         } catch (\Exception $e) {
//             return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
//         }
//     }

//     public function getData()
//     {
//         $mjabatans = Mjabatan::get();
//         $data = DataTables::of($mjabatans)
//             ->addColumn('action', function ($mjabatan) {
//                 return '<button type="button" class="btn btn-sm btn-info edit-mjabatan-btn" data-action="edit" data-mjabatan-id="' . $mjabatan->id . '"><i class="fas fa-pencil-alt"></i><span class="d-none d-sm-inline">' . trans('global.edit') . '</span></button>
//                         <button type="button" class="btn btn-sm btn-primary view-mjabatan-btn" data-action="view" data-mjabatan-id="' . $mjabatan->id . '"><i class="fas fa-folder-open"></i> <span class="d-none d-sm-inline">' . trans('global.view') . '</span></button>';
//             })
//             ->make(true);
//         return $data;
//     }
// }

class MjabatanController extends Controller
{
    /* =====================================================
     * INDEX
     * ===================================================== */
    public function index()
    {
        $divisis = MDivisi::where('aktif', 1)
            ->orderBy('nama')
            ->get();

        return view('master.mjabatan.index', compact('divisis'));
    }

    /* =====================================================
     * STORE
     * ===================================================== */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:200',
            'divisi_id'   => 'required|exists:mdivisi,id',
            'is_manager'  => 'nullable|boolean',
            'aktif'       => 'required|boolean',
        ]);

        try {
            $jabatan = Mjabatan::create([
                'nama'       => $validated['nama'],
                'divisi_id'  => $validated['divisi_id'],
                'is_manager' => $validated['is_manager'] ?? 0,
                'aktif'      => $validated['aktif'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil ditambahkan',
                'data'    => $jabatan,
            ], Response::HTTP_CREATED);

        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error',
            ], 400);
        }
    }

    /* =====================================================
     * EDIT (FETCH DATA)
     * ===================================================== */
    public function edit(Mjabatan $mjabatan)
    {
        return response()->json([
            'id'         => $mjabatan->id,
            'nama'       => $mjabatan->nama,
            'divisi_id'  => $mjabatan->divisi_id,
            'is_manager' => $mjabatan->is_manager,
            'aktif'      => $mjabatan->aktif,
        ]);
    }

    /* =====================================================
     * UPDATE
     * ===================================================== */
    public function update(Request $request, Mjabatan $mjabatan)
    {
        
        $validated = $request->validate([
            'nama'        => 'required|string|max:200',
            'divisi_id'   => 'required|exists:mdivisi,id',
            'is_manager'  => 'nullable|boolean',
            'aktif'       => 'required|boolean',
        ]);

        try {
            $mjabatan->update([
                'nama'       => $validated['nama'],
                'divisi_id'  => $validated['divisi_id'],
                'is_manager' => $validated['is_manager'],
                'aktif'      => $validated['aktif'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil diperbarui',
            ], Response::HTTP_ACCEPTED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update jabatan',
            ], 500);
        }
    }

    /* =====================================================
     * SHOW
     * ===================================================== */
    public function show(Mjabatan $mjabatan)
    {
        return response()->json([
            'nama'       => $mjabatan->nama,
            'divisi'     => optional($mjabatan->divisi)->nama,
            'is_manager' => $mjabatan->is_manager,
            'aktif'      => $mjabatan->aktif,
        ]);
    }

    /* =====================================================
     * DELETE
     * ===================================================== */
    public function destroy(Mjabatan $mjabatan)
    {
        try {
            $mjabatan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jabatan',
            ], 500);
        }
    }

    /* =====================================================
     * DATATABLE
     * ===================================================== */
    public function getData()
    {
        $query = Mjabatan::with('divisi');

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-info edit-mjabatan-btn"
                        data-mjabatan-id="'.$row->id.'">
                        <i class="fas fa-pencil-alt"></i>
                    </button>

                    <button class="btn btn-sm btn-primary view-mjabatan-btn"
                        data-mjabatan-id="'.$row->id.'">
                        <i class="fas fa-eye"></i>
                    </button>
                ';
            })

            ->rawColumns(['action'])
            ->make(true);
    }
}