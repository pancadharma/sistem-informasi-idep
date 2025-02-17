<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Program;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use App\Models\Program_Outcome;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Jenis_Kegiatan;
use App\Models\Kegiatan_Assessment;
use App\Models\Kegiatan_Kampanye;
use App\Models\Kegiatan_Kunjungan;
use App\Models\Kegiatan_Lainnya;
use App\Models\Kegiatan_Monitoring;
use App\Models\Kegiatan_Pelatihan;
use App\Models\Kegiatan_Pembelanjaan;
use App\Models\Kegiatan_Pemetaan;
use App\Models\Kegiatan_Pengembangan;
use App\Models\Kegiatan_Penulis;
use App\Models\Kegiatan_Sosialisasi;
use App\Models\Kelurahan;
use App\Models\mSektor;
use App\Models\Partner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Program_Outcome_Output;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Program_Outcome_Output_Activity;
use App\Models\Satuan;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;
class KegiatanController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('kegiatan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.kegiatan.index');
    }

    public function list_kegiatan(Request $request)
    {
        if (!$request->ajax() && !$request->isJson()) {
            return "Not an Ajax Request & JSON REQUEST";
        }

        $kegiatan = Kegiatan::with('dusun', 'users', 'kategori_lokasi', 'activity.program_outcome_output.program_outcome.program', 'satuan', 'jenis_bantuan')
            ->select('trkegiatan.*')
            ->get()
            ->map(function ($item) {
                // Calculate duration before formatting
                $item->duration_in_days = $item->getDurationInDays();

                // Format dates after calculating duration
                $item->tanggalmulai = Carbon::parse($item->tanggalmulai)->format('d-m-Y');
                $item->tanggalselesai = Carbon::parse($item->tanggalselesai)->format('d-m-Y');

                // Add calculated values
                $program = $item->activity->program_outcome_output->program_outcome->program;
                $item->total_beneficiaries = $program->getTotalBeneficiaries();

                return $item;
            });

        $data = DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('program_name', function ($kegiatan) {
                return $kegiatan->activity->program_outcome_output->program_outcome->program->nama ?? 'N/A';
            })
            ->addColumn('duration_in_days', function ($kegiatan) {
                return $kegiatan->duration_in_days . ' ' . __('cruds.kegiatan.days')  ?? 'N/A';
            })
            ->addColumn('action', function ($kegiatan) {
                $buttons = [];

                if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_edit')) {
                    $buttons[] = $this->generateButton('edit', 'info', 'pencil-square', __('global.edit') . __('cruds.kegiatan.label') . $kegiatan->nama, $kegiatan->id);
                }
                if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_view') || auth()->user()->can('kegiatan_access')) {
                    $buttons[] = $this->generateButton('view', 'primary', 'folder2-open', __('global.view') . __('cruds.kegiatan.label') . $kegiatan->nama, $kegiatan->id);
                }
                if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_details_edit') || auth()->user()->can('kegiatan_edit')) {
                    $buttons[] = $this->generateButton('details', 'danger', 'list-ul', __('global.details') . __('cruds.kegiatan.label') . $kegiatan->nama, $kegiatan->id);
                }
                return "<div class='button-container'>" . implode(' ', $buttons) . "</div>";
            })
            ->rawColumns(['action'])
            ->make(true);

        return $data;
    }

    private function generateButton($action, $class, $icon, $title, $kegiatanId)
    {
        return '<button type="button" title="' . $title . '" class="btn btn-sm btn-' . $class . ' ' . $action . '-kegiatan-btn" data-action="' . $action . '"
                data-kegiatan-id="' . $kegiatanId . '" data-toggle="tooltip" data-placement="top">
                    <i class="bi bi-' . $icon . '"></i>
                    <span class="d-none d-sm-inline"></span>
                </button>';
    }

    public function create()
    {
        if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_edit') || auth()->user()->can('kegiatan_create')) {
            $program = Program::all();
            $statusOptions = Kegiatan::STATUS_SELECT;
            $kegiatan = new Kegiatan(); // Empty instance
            $kegiatan->setRelation('penulis', collect([])); // Ensure an empty collection

            $programoutcomeoutputactivities = Program_Outcome_Output_Activity::all();

            return view('tr.kegiatan.create', compact('program', 'statusOptions', 'programoutcomeoutputactivities', 'kegiatan'));
        }
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => 'Unauthorized Permission. Please ask your administrator to assign permissions to access details of this Page',
        ], Response::HTTP_FORBIDDEN);
    }



    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            // 1. Create the main Trkegiatan record
            $kegiatan = Kegiatan::create($request->except([
                // Fields from trkegiatanassessment
                'assessmentyangterlibat',
                'assessmenttemuan',
                'assessmenttambahan',
                'assessmenttambahan_ket',
                'assessmentkendala',
                'assessmentisu',
                'assessmentpembelajaran',

                // Fields from trkegiatansosialisasi
                'sosialisasiyangterlibat',
                'sosialisasitemuan',
                'sosialisasitambahan',
                'sosialisasitambahan_ket',
                'sosialisasikendala',
                'sosialisasiisu',
                'sosialisasipembelajaran',

                // Fields from trkegiatanpelatihan
                'pelatihanpelatih',
                'pelatihanhasil',
                'pelatihandistribusi',
                'pelatihandistribusi_ket',
                'pelatihanrencana',
                'pelatihanunggahan',
                'pelatihanisu',
                'pelatihanpembelajaran',

                // Fields from trkegiatanpembelanjaan
                'pembelanjaandetailbarang',
                'pembelanjaanmulai',
                'pembelanjaanselesai',
                'pembelanjaandistribusimulai',
                'pembelanjaandistribusiselesai',
                'pembelanjaanterdistribusi',
                'pembelanjaanakandistribusi',
                'pembelanjaanakandistribusi_ket',
                'pembelanjaankendala',
                'pembelanjaanisu',
                'pembelanjaanpembelajaran',

                // Fields from trkegiatanpengembangan
                'pengembanganjeniskomponen',
                'pengembanganberapakomponen',
                'pengembanganlokasikomponen',
                'pengembanganyangterlibat',
                'pengembanganrencana',
                'pengembangankendala',
                'pengembanganisu',
                'pengembanganpembelajaran',

                // Fields from trkegiatankampanye
                'kampanyeyangdikampanyekan',
                'kampanyejenis',
                'kampanyebentukkegiatan',
                'kampanyeyangterlibat',
                'kampanyeyangdisasar',
                'kampanyejangkauan',
                'kampanyerencana',
                'kampanyekendala',
                'kampanyeisu',
                'kampanyepembelajaran',

                // Fields from trkegiatanpemetaan
                'pemetaanyangdihasilkan',
                'pemetaanluasan',
                'pemetaanunit',
                'pemetaanyangterlibat',
                'pemetaanrencana',
                'pemetaanisu',
                'pemetaanpembelajaran',

                // Fields from trkegiatanmonitoring
                'monitoringyangdipantau',
                'monitoringdata',
                'monitoringyangterlibat',
                'monitoringmetode',
                'monitoringhasil',
                'monitoringkegiatanselanjutnya',
                'monitoringkegiatanselanjutnya_ket',
                'monitoringkendala',
                'monitoringisu',
                'monitoringpembelajaran',

                // Fields from trkegiatankunjungan
                'kunjunganlembaga',
                'kunjunganpeserta',
                'kunjunganyangdilakukan',
                'kunjunganhasil',
                'kunjunganpotensipendapatan',
                'kunjunganrencana',
                'kunjungankendala',
                'kunjunganisu',
                'kunjunganpembelajaran',

                // Fields from trkegiatankonsultasi
                'konsultasilembaga',
                'konsultasikomponen',
                'konsultasiyangdilakukan',
                'konsultasihasil',
                'konsultasipotensipendapatan',
                'konsultasirencana',
                'konsultasikendala',
                'konsultasiisu',
                'konsultasipembelajaran',

                // Fields from trkegiatanlainnya
                'lainnyamengapadilakukan',
                'lainnyadampak',
                'lainnyasumberpendanaan',
                'lainnyasumberpendanaan_ket',
                'lainnyayangterlibat',
                'lainnyarencana',
                'lainnyakendala',
                'lainnyaisu',
                'lainnyapembelajaran',

                //If you have file inputs
                'dokumen'
            ]));
            $this->storeKegiatanHasil($request, $kegiatan);





            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kegiatan created successfully',
                "message" => __('cruds.data.data') . ' ' . __('cruds.kegiatan.title') . ' ' . $request->nama . ' ' . __('cruds.data.added'),
                'data' => $kegiatan,
            ], 201);

            //end of try
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create kegiatan: ' . $e->getMessage(),
                'errors'  => $e->errors(),
                'request_data' => $request->all(),
            ], 500);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
            ], 404);
        } catch (HttpException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    public function show($id)
    {
        return view('tr.kegiatan.show');
    }

    public function edit(Kegiatan $kegiatan)
    {
        if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_edit')) {
            $program = Program::all();
            $statusOptions = Kegiatan::STATUS_SELECT;
            $programoutcomeoutputactivities = Program_Outcome_Output_Activity::all();
            $kegiatanPenulis = $kegiatan->penulis()->get(); // Load related penulis

            return view('tr.kegiatan.edit', compact('program', 'statusOptions', 'programoutcomeoutputactivities', 'kegiatan', 'kegiatanPenulis'));
        }
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => 'Unauthorized Permission. Please ask your administrator to assign permissions to access details of this Page',
        ], Response::HTTP_FORBIDDEN);
    }

    public function update(Request $request, $id)
    {
        return view('tr.kegiatan.edit');
    }

    public function destroy($id)
    {
        return false;
    }

    public function getActivityProgram($programId)
    {
        $program = Program::with([
            'outcome.output.activities' => function ($query) {
                $query->select('id', 'kode', 'nama', 'deskripsi', 'indikator', 'target', 'programoutcomeoutput_id');
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


    public function getSatuan(Request $request)
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
        $satuan = Satuan::when($id, function ($query, $id) {
            return $query->where('id', $id);
        }, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%");
        });
        $satuan = $satuan->paginate(20, ['*'], 'page', $page);
        return response()->json($satuan);
    }

    public function getJenisKegiatan(Request $request)
    {
        // $jenisKegiatan = Kegiatan::getJenisKegiatan();

        $jenisKegiatan = Jenis_Kegiatan::select('id', 'nama')->get()->mapWithKeys(function ($item) {
            return [$item->id => $item->nama];
        })->toArray();

        if ($request->has('id')) {
            // If requesting specific ID(s)
            $id = $request->input('id');
            $data = collect($jenisKegiatan)
                ->filter(function ($value, $key) use ($id) {
                    return $key == $id;
                })
                ->map(function ($text, $id) {
                    return [
                        'id' => $id,
                        'nama' => $text
                    ];
                })
                ->values()
                ->all();

            return response()->json(['data' => $data]);
        }

        // For dropdown listing
        $first = __('cruds.kegiatan.basic.bentuk');
        // $second =  __('cruds.kegiatan.basic.sektor');

        $groupedData = [
            $first => array_slice($jenisKegiatan, 0, 11, true),
            // $second => array_slice($jenisKegiatan, 11, null, true)
        ];
        return response()->json($groupedData);
    }

    public function getKegiatanMitra(Request $request)
    {
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

    public function getKegiatanDesa(Request $request)
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
        $desa = Kelurahan::when(!empty($ids), function ($query) use ($ids) {
            return $query->whereIn('id', $ids);
        }, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%");
        });

        $desa = $desa->paginate(20, ['*'], 'page', $page);
        return response()->json($desa);
    }

    public function getSektorKegiatan(Request $request)
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

        $data = mSektor::when(!empty($ids), function ($query) use ($ids) {
            return $query->whereIn('id', $ids);
        }, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%");
        });

        $data = $data->paginate(20, ['*'], 'page', $page);
        return response()->json($data);
    }

    // auto select next fase pelaporan on create kegiatan form
    public function fetchNextFasePelaporan(Request $request, $programOutcomeOutputActivityId)
    {
        if (!$programOutcomeOutputActivityId) {
            return response()->json(['next_fase_pelaporan' => 1, 'disabled_fase' => []]);
        }
        $nextFasePelaporan = Kegiatan::where('programoutcomeoutputactivity_id', $programOutcomeOutputActivityId)
            ->max('fasepelaporan');

        $existingFasePelaporan = Kegiatan::where('programoutcomeoutputactivity_id', $programOutcomeOutputActivityId)
            ->pluck('fasepelaporan')->toArray();

        if ($nextFasePelaporan === null) {
            $nextFasePelaporan = 1;
        } else {
            $nextFasePelaporan++;
            if ($nextFasePelaporan > 99) {
                $nextFasePelaporan = 1;
            }
        }
        return response()->json(['next_fase_pelaporan' => $nextFasePelaporan, 'disabled_fase' => $existingFasePelaporan]);
    }

    // method to save kegiatan ->hasil based on selected jenis kegiatan

    public function storeKegiatanHasil(Request $request, Kegiatan $kegiatan)
    {
        $jenisKegiatan = $request->input('jeniskegiatan_id');
        $idKegiatan = $kegiatan->id;

        switch ($jenisKegiatan) {
            case 1: // Assessment
                Kegiatan_Assessment::create(array_merge($request->only([
                    'assessmentyangterlibat',
                    'assessmenttemuan',
                    'assessmenttambahan',
                    'assessmenttambahan_ket',
                    'assessmentkendala',
                    'assessmentisu',
                    'assessmentpembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 2: // Sosialisasi
                Kegiatan_Sosialisasi::create(array_merge($request->only([
                    'sosialisasiyangterlibat',
                    'sosialisasitemuan',
                    'sosialisasitambahan',
                    'sosialisasitambahan_ket',
                    'sosialisasikendala',
                    'sosialisasiisu',
                    'sosialisasipembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 3: // Pelatihan
                Kegiatan_Pelatihan::create(array_merge($request->only([
                    'pelatihanpelatih',
                    'pelatihanhasil',
                    'pelatihandistribusi',
                    'pelatihandistribusi_ket',
                    'pelatihanrencana',
                    'pelatihanunggahan',
                    'pelatihanisu',
                    'pelatihanpembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 4: // Pembelanjaan
                Kegiatan_Pembelanjaan::create(array_merge($request->only([
                    'pembelanjaandetailbarang',
                    'pembelanjaanmulai',
                    'pembelanjaanselesai',
                    'pembelanjaandistribusimulai',
                    'pembelanjaandistribusiselesai',
                    'pembelanjaanterdistribusi',
                    'pembelanjaanakandistribusi',
                    'pembelanjaanakandistribusi_ket',
                    'pembelanjaankendala',
                    'pembelanjaanisu',
                    'pembelanjaanpembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 5: // Pengembangan
                Kegiatan_Pengembangan::create(array_merge($request->only([
                    'pengembanganjeniskomponen',
                    'pengembanganberapakomponen',
                    'pengembanganlokasikomponen',
                    'pengembanganyangterlibat',
                    'pengembanganrencana',
                    'pengembangankendala',
                    'pengembanganisu',
                    'pengembanganpembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 6: // Kampanye
                Kegiatan_Kampanye::create(array_merge($request->only([
                    'kampanyeyangdikampanyekan',
                    'kampanyejenis',
                    'kampanyebentukkegiatan',
                    'kampanyeyangterlibat',
                    'kampanyeyangdisasar',
                    'kampanyejangkauan',
                    'kampanyerencana',
                    'kampanyekendala',
                    'kampanyeisu',
                    'kampanyepembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 7: // Pemetaan
                Kegiatan_Pemetaan::create(array_merge($request->only([
                    'pemetaanyangdihasilkan',
                    'pemetaanluasan',
                    'pemetaanunit',
                    'pemetaanyangterlibat',
                    'pemetaanrencana',
                    'pemetaanisu',
                    'pemetaanpembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 8: // Monitoring
                Kegiatan_Monitoring::create(array_merge($request->only([
                    'monitoringyangdipantau',
                    'monitoringdata',
                    'monitoringyangterlibat',
                    'monitoringmetode',
                    'monitoringhasil',
                    'monitoringkegiatanselanjutnya',
                    'monitoringkegiatanselanjutnya_ket',
                    'monitoringkendala',
                    'monitoringisu',
                    'monitoringpembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 9: // Kunjungan
                Kegiatan_Kunjungan::create(array_merge($request->only([
                    'kunjunganlembaga',
                    'kunjunganpeserta',
                    'kunjunganyangdilakukan',
                    'kunjunganhasil',
                    'kunjunganpotensipendapatan',
                    'kunjunganrencana',
                    'kunjungankendala',
                    'kunjunganisu',
                    'kunjunganpembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 10: // Konsultasi
                Kegiatana_Konsultasi::create(array_merge($request->only([
                    'konsultasilembaga',
                    'konsultasikomponen',
                    'konsultasiyangdilakukan',
                    'konsultasihasil',
                    'konsultasipotensipendapatan',
                    'konsultasirencana',
                    'konsultasikendala',
                    'konsultasiisu',
                    'konsultasipembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            case 11: // Lainnya
                Kegiatan_Lainnya::create(array_merge($request->only([
                    'lainnyamengapadilakukan',
                    'lainnyadampak',
                    'lainnyasumberpendanaan',
                    'lainnyasumberpendanaan_ket',
                    'lainnyayangterlibat',
                    'lainnyarencana',
                    'lainnyakendala',
                    'lainnyaisu',
                    'lainnyapembelajaran'
                ]), ['kegiatan_id' => $idKegiatan]));
                break;
            default:
                // Handle invalid jenisKegiatan (e.g., throw an exception)
                throw new \Exception("Invalid jenisKegiatan: " . $jenisKegiatan);
        }
    }

    //method to store kegiatan basic tab data
}