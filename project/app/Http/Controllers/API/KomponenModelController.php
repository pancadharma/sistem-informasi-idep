<?php

namespace App\Http\Controllers\API;

use Log;
use Exception;
use App\Models\Dusun;
use App\Models\Satuan;
use App\Models\Country;
use App\Models\Kegiatan;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use App\Models\KomponenModel;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Meals_Komponen_Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreKomponenRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KomponenModelController extends Controller
{
    public function getKomodelDatatable(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = Meals_Komponen_Model::with([
                    'program',
                    'komponenmodel',
                    'lokasi.provinsi',
                    'lokasi.kabupaten',
                    'lokasi.kecamatan',
                    'lokasi.desa',
                    'lokasi.dusun',
                    'lokasi.satuan',
                    'sektors'
                ]);

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('program_name', fn($item) => $item->program->nama ?? '-')
                    ->addColumn('sektor', fn($item) => $item->sektors->pluck('nama')->join(', ') ?: '-')
                    ->addColumn('komponen_model', fn($item) => $item->komponenmodel->nama ?? '-')
                    ->addColumn('totaljumlah', fn($item) => $item->totaljumlah ?? '0')
                    ->addColumn('satuan', function ($item) {
                        return $item->lokasi->pluck('satuan.nama')->unique()->join(', ') ?: '-';
                    })
                    ->addColumn('provinsi', fn($item) => $item->lokasi->pluck('provinsi.nama')->unique()->count() . ' provinsi')
                    ->addColumn('kabupaten', fn($item) => $item->lokasi->pluck('kabupaten.nama')->unique()->count() . ' kabupaten')
                    ->addColumn('kecamatan', fn($item) => $item->lokasi->pluck('kecamatan.nama')->unique()->count() . ' kecamatan')
                    ->addColumn('desa', fn($item) => $item->lokasi->pluck('desa.nama')->unique()->count() . ' desa')
                    ->addColumn('dusun', fn($item) => $item->lokasi->pluck('dusun.nama')->unique()->count() . ' dusun')
                    ->addColumn('action', function ($item) {
                        $buttons = [];

                        if (auth()->user()->id === 1 || auth()->user()->can('komodel_edit')) {
                            $buttons[] = $this->generateButton('edit', 'info', 'pencil-square', __('global.edit') . ' ' . __('cruds.komodel.label') . ' ' . $item->id, $item->id);
                        }
                        if (auth()->user()->id === 1 || auth()->user()->can('komodel_view') || auth()->user()->can('komodel_access')) {
                            $buttons[] = $this->generateButton('view', 'primary', 'folder2-open', __('global.view') . ' ' . __('cruds.komodel.label') . ' ' . $item->id, $item->id);
                        }
                        if (auth()->user()->id === 1 || auth()->user()->can('komodel_details_edit') || auth()->user()->can('komodel_edit')) {
                            $buttons[] = $this->generateButton('details', 'danger', 'list-ul', __('global.details') . ' ' . __('cruds.komodel.label') . ' ' . $item->id, $item->id);
                        }

                        return "<div class='button-container'>" . implode(' ', $buttons) . "</div>";
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            Log::error('Error in getKomodelDatatable: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while loading data.'], 500);
        }
    }

    private function generateButton($action, $class, $icon, $title, $komodelId)
    {
        return '<button type="button" title="' . $title . '" class="btn btn-sm btn-' . $class . ' ' . $action . '-komponen-model-btn" 
            data-action="' . $action . '" 
            data-komponen-model-id="' . $komodelId . '" 
            data-toggle="tooltip" data-placement="top">
            <i class="bi bi-' . $icon . '"></i>
            <span class="d-none d-sm-inline"></span>
        </button>';
    }


    public function storeKomponen(StoreKomponenRequest $request)
    {
        try {
            $data = $request->validated();
            $komponen = KomponenModel::create($data); // Pastikan hanya field yg diisi

            // Hapus cache jika ada caching
            Cache::forget("komponen_list");

            return response()->json([
                'success' => true,
                'message' => __('Komponen') . ' ' . $request->nama . ' ' . __('berhasil ditambahkan'),
                'status'  => Response::HTTP_CREATED,
                'data'    => $komponen,
            ], Response::HTTP_CREATED);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
            ], Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred.',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProv(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);

        if (!is_array($ids) && $ids !== null) {
            $ids = [$ids];
        }

        $data = Provinsi::when(!empty($ids), function ($query) use ($ids) {
            return $query->whereIn('id', $ids);
        }, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%");
        });

        $perPage = 20; // or whatever pagination size you want
        $results = $data->paginate($perPage, ['id', 'nama'], 'page', $page);

        return response()->json([
            'results' => $results->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->nama,
                ];
            })->all(),
            'pagination' => [
                'more' => $results->hasMorePages(),
            ],
        ]);
    }

    public function getKabupatens(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
            'provinsi_id'   => 'required|exists:provinsi,id'
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);
        $provId = $request->input('provinsi_id');
        $perPage = 20;

        $cacheKey = "kabupatens_provinsi_{$provId}_search_{$search}_page_{$page}_ids_" . implode(',', $ids);

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($search, $page, $ids, $provId, $perPage) {
            $query = Kabupaten::where('provinsi_id', $provId);

            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            } elseif ($search !== '') {
                $query->where('nama', 'like', "%{$search}%");
            }

            $results = $query->paginate($perPage, ['id', 'nama'], 'page', $page);

            return response()->json([
                'results' => $results->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'text' => $item->nama,
                    ];
                })->all(),
                'pagination' => [
                    'more' => $results->hasMorePages(),
                ],
            ]);
        });
    }

    public function getKecamatans(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
            'kabupaten_id'   => 'required|exists:kabupaten,id'
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);
        $kabId = $request->input('kabupaten_id');
        $perPage = 20;

        $cacheKey = "kecamatans_kabupaten_{$kabId}_search_{$search}_page_{$page}_ids_" . implode(',', $ids);

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($search, $page, $ids, $kabId, $perPage) {
            $query = Kecamatan::where('kabupaten_id', $kabId);

            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            } elseif ($search !== '') {
                $query->where('nama', 'like', "%{$search}%");
            }

            $results = $query->paginate($perPage, ['id', 'nama'], 'page', $page);

            return response()->json([
                'results' => $results->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'text' => $item->nama,
                    ];
                })->all(),
                'pagination' => [
                    'more' => $results->hasMorePages(),
                ],
            ]);
        });
    }

    public function getDesas(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
            'kecamatan_id'   => 'required|exists:kecamatan,id'
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);
        $kecamatanId = $request->input('kecamatan_id');
        $perPage = 20;

        $cacheKey = "desas_desa_{$kecamatanId}_search_{$search}_page_{$page}_ids_" . implode(',', $ids);

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($search, $page, $ids, $kecamatanId, $perPage) {
            $query = Kelurahan::where('kecamatan_id', $kecamatanId);

            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            } elseif ($search !== '') {
                $query->where('nama', 'like', "%{$search}%");
            }

            $results = $query->paginate($perPage, ['id', 'nama'], 'page', $page);

            return response()->json([
                'results' => $results->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'text' => $item->nama,
                    ];
                })->all(),
                'pagination' => [
                    'more' => $results->hasMorePages(),
                ],
            ]);
        });
    }

    public function getDusuns(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
            'desa_id'   => 'required|exists:kelurahan,id'
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);
        $desaId = $request->input('desa_id');
        $perPage = 20;

        $cacheKey = "dusuns_desa_{$desaId}_search_{$search}_page_{$page}_ids_" . implode(',', $ids);

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($search, $page, $ids, $desaId, $perPage) {
            $query = Dusun::where('desa_id', $desaId);

            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            } elseif ($search !== '') {
                $query->where('nama', 'like', "%{$search}%");
            }

            $results = $query->paginate($perPage, ['id', 'nama'], 'page', $page);

            return response()->json([
                'results' => $results->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'text' => $item->nama,
                    ];
                })->all(),
                'pagination' => [
                    'more' => $results->hasMorePages(),
                ],
            ]);
        });
    }

    public function getSatuan(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);

        if (!is_array($ids) && $ids !== null) {
            $ids = [$ids];
        }

        $data = Satuan::when(!empty($ids), function ($query) use ($ids) {
            return $query->whereIn('id', $ids);
        }, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%");
        });

        $perPage = 20; // or whatever pagination size you want
        $results = $data->paginate($perPage, ['id', 'nama'], 'page', $page);

        return response()->json([
            'results' => $results->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->nama,
                ];
            })->all(),
            'pagination' => [
                'more' => $results->hasMorePages(),
            ],
        ]);
    }


}
