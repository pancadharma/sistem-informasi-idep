<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dusun;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDusunRequest;
use App\Models\Partner;
use App\Models\User;

class WilayahController extends Controller
{

    function getProvinsi()
    {
        // $provinsi = Provinsi::pluck('nama', 'id')->prepend(trans('global.selectProv'), '');
        $provinsi = Provinsi::get(['id', 'kode', 'nama'])
            ->map(function ($item) {
                return [
                    'id'   => $item->id,
                    'kode' => $item->kode,
                    'text' => "{$item->kode} - {$item->nama}",
                ];
            });
        return response()->json($provinsi);
    }
    function getKabupaten(Request $request)
    {
        $kabupaten = Kabupaten::where('provinsi_id', $request->id)
            ->get(['id', 'kode', 'nama'])
            ->map(function ($item) {
                return [
                    'id'   => $item->id,
                    'kode' => $item->kode,
                    'text' => "{$item->kode} - {$item->nama}",
                ];
            });
        return response()->json($kabupaten);
    }
    function getKecamatan(Request $request)
    {
        $kecamatan = Kecamatan::where('kabupaten_id', $request->id)
            ->get(['id', 'kode', 'nama'])
            ->map(function ($item) {
                return [
                    'id'   => $item->id,
                    'kode' => $item->kode,
                    'text' => "{$item->kode} - {$item->nama}",
                ];
            });
        return response()->json($kecamatan);
    }
    function getDesa(Request $request)
    {
        $desa = Kelurahan::where('kecamatan_id', $request->id)
            ->get(['id', 'kode', 'nama'])
            ->map(function ($item) {
                return [
                    'id'   => $item->id,
                    'kode' => $item->kode,
                    'text' => "{$item->kode} - {$item->nama}",
                ];
            });
        return response()->json($desa);
    }
    function getDusun(Request $request)
    {
        $dusun = Dusun::where('desa_id', $request->id)
            ->get(['id', 'kode', 'nama'])
            ->map(function ($item) {
                return [
                    'id'   => $item->id,
                    'kode' => $item->kode,
                    'text' => "{$item->kode} - {$item->nama}",
                ];
            });
        return response()->json($dusun);
    }


    public function getProgramLokasi(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $provinsi = Provinsi::where('nama', 'like', "%{$search}%")->get();
        return response()->json($provinsi);
    }

    // public function getKegiatanDesa(Request $request)
    // {
    //     if ($request->has('id')) {
    //         $desa = Kelurahan::find($request->id);
    //         if ($desa) {
    //             return response()->json([
    //                 'id' => $desa->id,
    //                 'text' => $desa->nama,
    //             ]);
    //         }
    //     }
    //     $query = $request->input('q');
    //     $desa =  Kelurahan::where('nama', 'LIKE', "%{$query}%")->paginate(20); // Adjust the number of results per page
    //     $data = $desa->map(function ($item) {
    //         return [
    //             'id' => $item->id,
    //             'text' => $item->nama,
    //         ];
    //     });
    //     return response()->json($data);
    // }

    function loadKegiatanDesa(Request $request)
    {
        $dusun = Kelurahan::where('id', $request->id)
            ->get(['id', 'kode', 'nama'])
            ->map(function ($item) {
                return [
                    'id'   => $item->id,
                    // 'kode' => $item->kode,
                    'text' => "{$item->kode} - {$item->nama}",
                ];
            });
        return response()->json($dusun);
    }

    public function getKegiatanDesa(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'id' => 'nullable|integer', // Add id validation
        ]);

        // Retrieve search, page, and id inputs
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $id = $request->input('id', null);

        // Build query to include both name search and id check
        $desa = Kelurahan::when($id, function ($query, $id) {
            return $query->where('id', $id);
        }, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%");
        });
        $desa = $desa->paginate(20, ['*'], 'page', $page);
        return response()->json($desa);
    }

    // public function getKegiatanMitra(Request $request)
    // {
    //     // Validate request inputs
    //     $request->validate([
    //         'search' => 'nullable|string|max:255',
    //         'page' => 'nullable|integer|min:1',
    //         'id' => 'nullable|integer', // Add id validation
    //     ]);

    //     // Retrieve search, page, and id inputs
    //     $search = $request->input('search', '');
    //     $page = $request->input('page', 1);
    //     $id = $request->input('id', null);

    //     // Build query to include both name search and id check
    //     $mitra = Partner::when($id, function ($query, $id) {
    //         return $query->where('id', $id);
    //     }, function ($query) use ($search) {
    //         return $query->where('nama', 'like', "%{$search}%");
    //     });
    //     $mitra = $mitra->paginate(20, ['*'], 'page', $page);
    //     return response()->json($mitra);
    // }

    public function getKegiatanMitra(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'id' => 'nullable|array|min:1', // Changed to array validation
            'id.*' => 'integer', // Validate each ID in the array
        ]);

        // Retrieve search, page, and ids inputs
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);

        // Convert single ID to array if needed
        if (!is_array($ids) && $ids !== null) {
            $ids = [$ids];
        }

        // Build query to include both name search and ids check
        $mitra = Partner::when(!empty($ids), function ($query) use ($ids) {
            return $query->whereIn('id', $ids);
        }, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%");
        });

        $mitra = $mitra->paginate(20, ['*'], 'page', $page);
        return response()->json($mitra);
    }


    public function getKegiatanPenulis(Request $request)
    {
        $query = $request->input('q');
        $User = User::where('nama', 'LIKE', "%{$query}%")->paginate(20); // Adjust the number of results per page
        $results = $User->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nama
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $User->hasMorePages()
            ]
        ]);
    }
    public function getKegiatanJabatan(Request $request)
    {
        $query = $request->input('q');
        $Jabatan = Jabatan::where('nama', 'LIKE', "%{$query}%")->paginate(20); // Adjust the number of results per page
        $results = $Jabatan->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nama
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $Jabatan->hasMorePages()
            ]
        ]);
    }


    public function createDusun(StoreDusunRequest $request){

        // call method store in DusunController
        $dusunController = new DusunController();
        return $dusunController->store($request);
        // try {
        //     $data = $request->validated();
        //     Dusun::create($data);
        //     return response()->json([
        //         'success'   => true,
        //         'message'   =>  __('cruds.data.data') .' '.__('cruds.dusun.title') .' '. $request->nama .' '. __('cruds.data.added'),
        //         'status'    => Response::HTTP_CREATED,
        //         'data'      => $data,
        //     ]);
        // } catch (ValidationException $e) {
        //     // Handle validation errors
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Validation failed.',
        //         'errors'  => $e->errors(),
        //         'data'  => $request,
        //     ], 422);

        // } catch (ModelNotFoundException $e) {
        //     // Handle model not found errors
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Resource not found.',
        //         'data'  => $request,
        //     ], 404);

        // } catch (HttpException $e) {
        //     // Handle HTTP-specific exceptions
        //     return response()->json([
        //         'success'   => false,
        //         'message'   => $e->getMessage(),
        //         'data'      => $request,
        //     ], $e->getStatusCode());

        // } catch (Exception $e) {
        //     // Handle all other exceptions
        //     return response()->json([
        //         'success'   => false,
        //         'data'      => $request,
        //         'message'   => 'An error occurred.',
        //         'error'     => $e->getMessage(),
        //     ], 500);
        // }catch (QueryException $e){
        //     return response()->json([
        //         'success'   => false,
        //         'data'      => $request,
        //         'message'   => 'An error occurred.',
        //         'error'     => $e->getMessage(),
        //     ], 500);
        // }
    }


    // public function getKegiatanMitra(Request $request)
    // {
    //     // Validate request inputs
    //     $request->validate([
    //         'search' => 'nullable|string|max:255',
    //         'page' => 'nullable|integer|min:1',
    //     ]);

    //     // Retrieve search and page inputs
    //     $search = $request->input('search', '');
    //     $page = $request->input('page', 1);

    //     // Query Dusun model with pagination
    //     $partner = Partner::where('nama', 'like', "%{$search}%")
    //         ->paginate(50, ['*'], 'page', $page);

    //     // Return paginated response
    //     return response()->json($partner);
    // }
}