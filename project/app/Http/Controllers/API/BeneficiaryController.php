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
    // public function getPenerimaManfaat(Request $request)
    // {
    //     $meals = Meals_Penerima_Manfaat::with('dusun', 'users', 'penerimaActivity.program_outcome_output.program_outcome.program', 'kelompokMarjinal', 'jenisKelompok')
    //         ->select('trmeals_penerima_manfaat.*')
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     $data = DataTables::of($meals)
    //         ->addIndexColumn()
    //         ->addColumn('program_name', function ($meals) {
    //         $programNames = $meals->penerimaActivity->map(function ($activity) {
    //             // Check if $activity is not null and then traverse the relationships
    //             if ($activity && $activity->program_outcome_output && $activity->program_outcome_output->program_outcome && $activity->program_outcome_output->program_outcome->program) {
    //                 return $activity->program_outcome_output->program_outcome->program->nama ?? null;
    //             }
    //             return null; // Or some other default value if the relationship is missing
    //         })->filter()->unique()->values()->toArray();

    //         return implode(', ', $programNames) ?: 'N/A';
    //     })
    //         ->addColumn('action', function ($meals) {
    //         $buttons = [];
    //             if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_edit')) {
    //                 $buttons[] = $this->generateButton('edit', 'info', 'pencil-square', __('global.edit') . __('cruds.kegiatan.label') . $meals->nama, $meals->id);
    //             }
    //             if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_view') || auth()->user()->can('kegiatan_access')) {
    //                 $buttons[] = $this->generateButton('view', 'primary', 'folder2-open', __('global.view') . __('cruds.kegiatan.label') . $meals->nama, $meals->id);
    //             }
    //             if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_details_edit') || auth()->user()->can('kegiatan_edit')) {
    //                 $buttons[] = $this->generateButton('details', 'danger', 'list-ul', __('global.details') . __('cruds.kegiatan.label') . $meals->nama, $meals->id);
    //             }
    //             return "<div class='button-container'>" . implode(' ', $buttons) . "</div>";
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);

    //     return $data;
    // }


    public function getPenerimaManfaat(Request $request)
    {
        try {
            if ($request->ajax()) {
                // Query with eager loading all related models
                $query = Meals_Penerima_Manfaat::with([
                    'program',
                    'users',
                    'dusun',
                    'jenisKelompok',
                    'kelompokMarjinal',
                    // 'penerimaActivity',
                    // 'penerimaActivity.program_outcome_output', // Nested relationship
                ]);

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('kode', function ($row) {
                        return $row->program ? $row->program->kode : '-';
                    })
                    ->addColumn('program_name', function ($row) {
                        return $row->program ? $row->program->nama : '-';
                    })
                    ->addColumn('user_name', function ($row) {
                        return $row->user ? $row->user->name : '-';
                    })
                    ->addColumn('dusun_name', function ($row) {
                        return $row->dusun ? $row->dusun->nama : '-';
                    })
                    ->addColumn('jenis_kelompok', function ($row) {
                        try {
                            return $row->jenisKelompok->pluck('nama')->implode(', ');
                        } catch (Exception $e) {
                            \Log::error('Error fetching jenis_kelompok: ' . $e->getMessage());
                            return '-'; // Or some other default value
                        }
                    })
                    ->addColumn('kelompok_marjinal', function ($row) {
                        try {
                            return $row->kelompokMarjinal->pluck('nama')->implode(', ');
                        } catch (Exception $e) {
                            \Log::error('Error fetching kelompok_marjinal: ' . $e->getMessage());
                            return '-'; // Or some other default value
                        }
                    })
                    ->addColumn('activities', function ($row) {
                        try {
                            return $row->penerimaActivity->pluck('nama')->implode(', ');
                        } catch (Exception $e) {
                            \Log::error('Error fetching activities: ' . $e->getMessage());
                            return '-'; // Or some other default value
                        }
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="' . route('beneficiary.show', $row->id) . '" class="btn btn-sm btn-info">View</a> ';
                        $btn .= '<a href="' . route('beneficiary.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> ';
                        $btn .= '<button type="button" data-id="' . $row->id . '" class="btn btn-sm btn-danger delete-btn">Delete</button>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('meals-penerima-manfaat.index');
        } catch (Exception $e) {
            // Log the error
            \Log::error('Error in MealsPenerimaManfaatController@index: ' . $e->getMessage());

            // You might want to return an error view or a JSON response indicating the error
            // For example:
            if ($request->ajax()) {
                return response()->json(['error' => 'An error occurred while processing the request.'], 500);
            } else {
                return view('error', ['message' => 'An error occurred. Please check the logs.']);  // Create an error view
            }
        }
    }

    private function generateButton($action, $class, $icon, $title, $mealsId)
    {
        return '<button type="button" title="' . $title . '" class="btn btn-sm btn-' . $class . ' ' . $action . '-kegiatan-btn" data-action="' . $action . '"
            data-kegiatan-id="' . $mealsId . '" data-toggle="tooltip" data-placement="top">
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
