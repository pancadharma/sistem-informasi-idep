<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDusunRequest;
use App\Models\Dusun;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kegiatan;
use App\Models\Kelompok_Marjinal;
use App\Models\Kelurahan;
use App\Models\Master_Jenis_Kelompok;
use App\Models\Meals_Penerima_Manfaat;
use App\Models\Program;
use App\Models\Provinsi;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BeneficiaryController extends Controller
{
    public function getPenerimaManfaat(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = Program::select(['id', 'kode', 'nama'])
                    ->has('penerimaManfaat')
                    ->withCount([
                        'penerimaManfaat as total_beneficiaries' // Alias the count
                    ]);
                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('kode', function ($row) {
                        return $row->kode ?? '-';
                    })
                    ->addColumn('program_name', function ($row) {
                        return $row->nama ?? '-';
                    })
                    ->addColumn('total_beneficiaries', function ($row) {
                        return $row->total_beneficiaries ?? 0; // Use the count from withCount
                    })
                    ->addColumn('action', function ($row) {
                        $buttons = [];
                        if (auth()->user()->id === 1 || auth()->user()->can('beneficiary_edit')) {
                            $buttons[] = $this->generateButton('edit', 'info', 'pencil-square', __('global.edit') . ' ' . __('cruds.beneficiary.label') . ' ' . $row->nama, $row->id);
                        }
                    if (auth()->user()->id === 1 || auth()->user()->can('beneficiary_view') || auth()->user()->can('beneficiary_access')) {
                        $buttons[] = $this->generateButton('view', 'primary', 'folder2-open', __('global.view') . ' ' . __('cruds.beneficiary.label') . ' ' . $row->nama, $row->id);
                        }
                    if (auth()->user()->id === 1 || auth()->user()->can('beneficiary_details_edit') || auth()->user()->can('beneficiary_edit')) {
                        $buttons[] = $this->generateButton('details', 'danger', 'list-ul', __('global.details') . ' ' . __('cruds.beneficiary.label') . ' ' . $row->nama, $row->id);
                        }
                        return "<div class='button-container'>" . implode(' ', $buttons) . "</div>";
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (Exception $e) {
            \Log::error('Error in BeneficiaryController: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing the request.'], 500);
        }
    }

    private function generateButton($action, $class, $icon, $title, $mealsId)
    {
        return '<button type="button" title="' . $title . ' " class="btn btn-sm btn-' . $class . ' ' . $action . '-beneficiary-program-btn" data-action="' . $action . '"
            data-beneficiary-program-id="' . $mealsId . '" data-toggle="tooltip" data-placement="top">
            <i class="bi bi-' . $icon . '"></i>
            <span class="d-none d-sm-inline"></span>
            </button>';
    }

    public function getPrograms(Request $request)
    {
        if ($request->ajax()) {
            $query = Program::with(['outcome.output.activities' => function ($query) {
                $query->select('id', 'kode', 'nama', 'deskripsi', 'indikator', 'target', 'programoutcomeoutput_id', 'created_at');
            }])->get();

            return DataTables::of($query)
                ->addColumn('activities', function ($row) {
                    $activities = [];
                    foreach ($row->outcome as $out) {
                        foreach ($out->output as $come_output) {
                            foreach ($come_output->activities as $activity) {
                                $activities[] = $activity->kode;
                            }
                        }
                    }
                    return implode(', ', $activities);
                })
                ->addColumn('action', function ($row) {
                    $hasActivities = false;
                    foreach ($row->outcome as $out) {
                        foreach ($out->output as $come_output) {
                            if ($come_output->activities->isNotEmpty()) {
                                $hasActivities = true;
                                break 2; // Break out of both foreach loops
                            }
                        }
                    }

                    $button = '<button type="button" class="btn btn-sm btn-danger select-program" data-id="' . $row->id . '" data-kode="' . $row->kode . '" data-nama="' . $row->nama . '"';
                    $button .= $hasActivities ? '' : ' disabled';
                    $button .= '><i class="bi bi-plus"></i></button>';

                    return $button;
                })
                ->rawColumns(['action', 'activities'])
                ->make(true);
        }
    }

    public function storeDusun(StoreDusunRequest $request)
    {
        try {
            $data = $request->validated();
            $dusun = Dusun::create($data);

            // Clear cache with more specific key
            Cache::forget("dusuns_desa_{$request->desa_id}_search__page_1_ids_");

            return response()->json([
                'success'   => true,
                'message'   => __('cruds.data.data') . ' ' . __('cruds.dusun.title') . ' ' . $request->nama . ' ' . __('cruds.data.added'),
                'status'    => Response::HTTP_CREATED,
                'data'      => $dusun, // Return the created model instead of validated data
            ], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
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
        } catch (HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getKelompokRentan(Request $request)
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

        $data = Kelompok_Marjinal::when(!empty($ids), function ($query) use ($ids) {
            return $query->whereIn('id', $ids);
        }, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%")->orderBy('nama', 'asc');
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
    public function getJenisKelompok(Request $request)
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

        // sort by nama
        $data = Master_Jenis_Kelompok::when(!empty($ids), function ($query) use ($ids) {
            return $query->whereIn('id', $ids);
        }, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%")->orderBy('nama', 'asc');
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


    public function getActivityProgram($programId)
    {
        $program = Program::with([
            'outcome.output.activities' => function ($query) {
                $query->select('id', 'kode', 'nama', 'deskripsi', 'indikator', 'target', 'programoutcomeoutput_id', 'created_at');
            }
        ])->where('id', $programId)->first();

        if (!$program) {
            return response()->json(['message' => 'Program not found'], 404);
        }

        $activities = [];
        if ($program) {
            foreach ($program->outcome as $out) {
                foreach ($out->output as $come_output) {
                    foreach ($come_output->activities as $activity) {
                        $activities[] = $activity;
                    }
                }
            }
        }

        return response()->json($activities);
    }


    // DRY PRINCIPLE
    private function fetchSelect2Data(Request $request, $model, $parentKey = null, $parentId = null)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = (array) $request->input('id', []);
        $id = $request->input('id', []);
        $perPage = 20;

        $query = $model::query();

        // Apply parent filter if provided
        if ($parentKey && $parentId) {
            $query->where($parentKey, $parentId);
        }

        // Filter by IDs or search term
        $query->when(!empty($ids), function ($q) use ($ids) {
            return $q->whereIn('id', $ids);
        }, function ($q) use ($search) {
            return $search ? $q->where('nama', 'like', "%{$search}%") : $q;
        });

        $results = $query->paginate($perPage, ['id', 'nama'], 'page', $page);

        return response()->json([
            'results' => $results->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->nama, // Simplified; adjust format as needed
                ];
            })->all(),
            'pagination' => [
                'more' => $results->hasMorePages(),
            ],
        ]);
    }

    public function getProvinsi(Request $request)
    {
        return $this->fetchSelect2Data($request, Provinsi::class);
        // return $this->fetchSelect2Data($request, Provinsi::class, null, null);
    }

    public function getKabupaten(Request $request, $id)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'id' => 'nullable|array|min:1',
            'id.*' => 'integer',
            'provinsi_id' => 'required|exists:provinsi,id',
        ]);

        $request->validate(['provinsi_id' => 'required|exists:provinsi,id']);
        return $this->fetchSelect2Data($request, Kabupaten::class, 'provinsi_id', $id);
    }

    public function getKecamatan(Request $request, $id)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'id' => 'nullable|array|min:1',
            'id.*' => 'integer',
            'kabupaten_id' => 'required|exists:kabupaten,id',
        ]);

        // $request->validate(['kabupaten_id' => 'required|exists:kabupaten,id']);
        return $this->fetchSelect2Data($request, Kecamatan::class, 'kabupaten_id', $id);
    }

    public function getDesa(Request $request, $id)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'id' => 'nullable|array|min:1',
            'id.*' => 'integer',
            'kecamatan_id' => 'required|exists:kecamatan,id',
        ]);

        // $request->validate(['kecamatan_id' => 'required|exists:kecamatan,id']);
        return $this->fetchSelect2Data($request, Kelurahan::class, 'kecamatan_id', $id);
    }

    public function getDusuns(Request $request, $id)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
            'desa_id'   => 'required|exists:kelurahan,id',
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = (array) $request->input('id', []);
        $desaId = $request->input('desa_id');
        $perPage = 20;

        $cacheKey = "dusuns_desa_{$desaId}_search_{$search}_page_{$page}_ids_" . implode(',', $ids);

        // return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($search, $page, $ids, $desaId, $perPage) {
        $query = Dusun::where('desa_id', $desaId)
            ->when(!empty($ids), fn($q) => $q->whereIn('id', $ids))
            ->when($search, fn($q) => $q->where('nama', 'like', "%{$search}%"));

        $results = $query->paginate($perPage, ['id', 'nama'], 'page', $page);

        return response()->json([
            'results' => $results->map(fn($item) => [
                'id' => $item->id,
                'text' => $item->nama,
            ])->all(),
            'pagination' => [
                'more' => $results->hasMorePages(),
            ],
        ]);
        // });
    }

    public function apiStoreJenisKelompok(Request $request)
    {
        $request->validate([
            'nama_jenis_kelompok' => 'required|string|max:255',
        ]);

        $jeniskelompok = Master_Jenis_Kelompok::create([
            'nama' => $request->input('nama_jenis_kelompok'),
            'aktif' => $request->has('aktif') ? 1 : 0
        ]);
        return response()->json(
            [
                'success'   => true,
                'status'    => 'success',
                'message'   => 'Jenis Kelompok created successfully',
                'data'      => $jeniskelompok
            ],
            201
        );
    }
}