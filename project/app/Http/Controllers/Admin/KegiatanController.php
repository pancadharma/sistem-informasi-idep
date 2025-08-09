<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\API\KegiatanController as APIKegiatanController;
use Carbon\Carbon;
use App\Models\Program;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use App\Models\Program_Outcome;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKegiatanRequest;
use App\Http\Resources\KegiatanResource;
use App\Models\Dusun;
use App\Models\Jenis_Bantuan;
use App\Models\Jenis_Kegiatan;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kegiatan_Assessment;
use App\Models\Kegiatan_Kampanye;
use App\Models\Kegiatan_Konsultasi;
use App\Models\Kegiatan_Kunjungan;
use App\Models\Kegiatan_Lainnya;
use App\Models\Kegiatan_Lokasi;
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
use App\Models\Peran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Program_Outcome_Output;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Program_Outcome_Output_Activity;
use App\Models\Provinsi;
use App\Models\Satuan;
use App\Models\TargetReinstra;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;

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

        $kegiatan = Kegiatan::with([
            'users',
            'activity.program_outcome_output.program_outcome.program',
            'jenisKegiatan',
            'kategori_lokasi',
            'sektor'
        ])
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
                $item->total_beneficiaries = $item->penerimamanfaattotal;
                $item->sektor_names = $item->sektor->pluck('nama')->toArray(); // Convert collection to array

                return $item;
            });

        $data = DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('program_name', function ($kegiatan) {
                return $kegiatan->activity->program_outcome_output->program_outcome->program->nama ?? 'N/A';
            })
            ->addColumn('kegiatan_kode', function ($kegiatan) {
                return $kegiatan->activity->kode ?? 'N/A';
            })
            ->addColumn('duration_in_days', function ($kegiatan) {
                return $kegiatan->duration_in_days . ' ' . __('cruds.kegiatan.days')  ?? 'N/A';
            })
            // ->addColumn('sektor_names', function ($kegiatan) {
            //     return $kegiatan->sektor->pluck('nama')->implode(', ') ?? 'N/A';
            // })
            ->addColumn('jenis_kegiatan', function ($kegiatan) {
                return $kegiatan->jenisKegiatan->nama ?? 'N/A';
            })
            ->addColumn('action', function ($kegiatan) {
                $buttons = [];

                if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_edit')) {
                    $buttons[] = $this->generateButton('edit', 'info', 'pencil-square', __('global.edit') . __('cruds.kegiatan.label') . $kegiatan->nama, $kegiatan->id);
                }
                if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_view') || auth()->user()->can('kegiatan_access')) {
                    $buttons[] = $this->generateButton('view', 'primary', 'folder2-open', __('global.view') . __('cruds.kegiatan.label') . $kegiatan->nama, $kegiatan->id);
                }
                if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_show') || auth()->user()->can('kegiatan_edit')) {
                    $buttons[] = $this->generateButton('details', 'danger', 'list-ul', __('global.details') . __('cruds.kegiatan.label') . $kegiatan->nama, $kegiatan->id);
                }
                $buttons[] = $this->generateButton('export', 'success', 'download', 'Export ' . __('cruds.kegiatan.label') . ' ' . $kegiatan->nama, $kegiatan->id);
                return "<div class='button-container'>" . implode(' ', $buttons) . "</div>";
            })
            ->rawColumns(['action'])
            ->make(true);

        return $data;
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

    private function generateButton($type, $color, $icon, $label, $id)
    {
        if ($type === 'export') {
            return "<button type='button' data-id='" . $id . "' class='btn btn-" . $color . " btn-sm export-kegiatan-btn'><i class='bi bi-" . $icon . "' title='" . $label . "'></i></button>";
        }

        $url = '';
        switch ($type) {
            case 'edit':
                $url = route('kegiatan.edit', $id);
                break;
            case 'view':
                $url = route('kegiatan.show', $id);
                break;
            case 'details':
                $url = route('kegiatan.show', $id);
                break;
        }

        return "<a href='" . $url . "' class='btn btn-" . $color . " btn-sm'><i class='bi bi-" . $icon . "' title='" . $label . "'></i></a>";
    }

    public function export(Kegiatan $kegiatan, $format)
    {
        $format = strtolower($format);
        $data = compact('kegiatan');

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('tr.kegiatan.export', $data);
            return $pdf->download('kegiatan-' . $kegiatan->id . '.pdf');
        }

        if ($format === 'docx') {
            $phpWord = new PhpWord();
            $phpWord->setDefaultFontName('Times New Roman');
            $section = $phpWord->addSection();
            $html = view('tr.kegiatan.export', $data)->render();
            Html::addHtml($section, $html, false, false);
            $tempFile = tempnam(sys_get_temp_dir(), 'kegiatan');
            $phpWord->save($tempFile, 'Word2007', true);
            return response()->download($tempFile, 'kegiatan-' . $kegiatan->id . '.docx')->deleteFileAfterSend(true);
        }

        abort(404);
    }

    public function create()
    {
        if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_edit') || auth()->user()->can('kegiatan_create')) {
            // $program = Program::all();
            $statusOptions = Kegiatan::STATUS_SELECT;
            $kegiatan = new Kegiatan(); // Empty instance
            $kegiatan->setRelation('penulis', collect([])); // Ensure an empty collection

            // $programoutcomeoutputactivities = Program_Outcome_Output_Activity::all();

            return view('tr.kegiatan.create', compact('statusOptions', 'kegiatan'));
        }
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => 'Unauthorized Permission. Please ask your administrator to assign permissions to access details of this Page',
        ], Response::HTTP_FORBIDDEN);
    }

    public function store(StoreKegiatanRequest $request)
    {
        // $kegiatanController = new APIKegiatanController();
        // $kegiatan = new Kegiatan(); // Create a new Kegiatan instance.
        // $kegiatanController->storeApi($request, $kegiatan); // Pass both request and the new Kegiatan instance.
        // // Optionally return a response or redirect
        // return response()->json(['message' => 'Kegiatan processed by storeApi']);
        try {
            $kegiatanController = new APIKegiatanController();
            $response = $kegiatanController->storeApi($request, new Kegiatan());
            return response()->json([
                'success' => true,
                'status' => 'success',
                'data' => $response['data'],
                'message' => 'Kegiatan processed by storeApi'
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (HttpException $e) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    public function show($id)
    {
        // Fetch the Kegiatan with all its relationships
        $kegiatan = Kegiatan::with([
            'programOutcomeOutputActivity',
            'sektor',
            'mitra',
            'user',
            'lokasi.desa.kecamatan.kabupaten.provinsi',
            'jenisKegiatan',
            'lokasi_kegiatan',
            'kegiatan_penulis.peran',
        ])->findOrFail($id);

        // Get all the media collections
        $dokumenPendukung = $kegiatan->getMedia('dokumen_pendukung');
        $mediaPendukung = $kegiatan->getMedia('media_pendukung');
        $durationInDays = $kegiatan->getDurationInDays();


        // Get specific relation based on jenis kegiatan
        $jenisKegiatanId = $kegiatan->jeniskegiatan_id;
        $relationMap = Kegiatan::getJenisKegiatanRelationMap();

        $kegiatanRelation = null;
        if (isset($relationMap[$jenisKegiatanId])) {
            $relationName = $relationMap[$jenisKegiatanId];
            $kegiatanRelation = $kegiatan->$relationName;
        }

        foreach ($kegiatan->datapenulis as $penulis) {
            $penulis->kegiatanPeran = Peran::find($penulis->pivot->peran_id);
        }

        // $lokasi = $kegiatan->lokasi;
        // return $kegiatan->datapenulis;

        return view('tr.kegiatan.show', compact(
            'kegiatan',
            'dokumenPendukung',
            'mediaPendukung',
            'kegiatanRelation',
            'durationInDays'
        ));
    }

    private function getSpecificRelation($id)
    {
        $kegiatan = Kegiatan::select('jeniskegiatan_id')->find($id);
        if (!$kegiatan) return null;

        return Kegiatan::getJenisKegiatanRelationMap()[$kegiatan->jeniskegiatan_id] ?? null;
    }

    private function getKegiatanHasil($kegiatan)
    {
        $jenisKegiatan = (int) $kegiatan->jeniskegiatan_id;
        $modelMapping = Kegiatan::getJenisKegiatanModelMap();

        if (!isset($modelMapping[$jenisKegiatan])) {
            throw new \InvalidArgumentException("Invalid jenisKegiatan: " . $jenisKegiatan);
        }

        $modelClass = $modelMapping[$jenisKegiatan];
        return $modelClass::where('kegiatan_id', $kegiatan->id)->get();
    }

    // public function edit($id)
    // {
    //     // $kegiatan = Kegiatan::with('programoutcomeoutputactivity.program_outcome_output.program_outcome.program')->findOrFail($id);
    //     $kegiatan = Kegiatan::with([
    //         'programOutcomeOutputActivity',
    //         'sektor',
    //         'mitra',
    //         'user',
    //         'lokasi.desa.kecamatan.kabupaten.provinsi',
    //         'jenisKegiatan',
    //         'lokasi_kegiatan',
    //         'kegiatan_penulis.peran',

    //         'kegiatan_penulis.user',
    //         'assessment',
    //         'sosialisasi',
    //         'pelatihan',
    //         'pembelanjaan',
    //         'pengembangan',
    //         'kampanye',
    //         'pemetaan',
    //         'monitoring',
    //         'kunjungan',
    //         'konsultasi',
    //         'lainnya'
    //     ])->findOrFail($id);
    //     $jenisKegiatanList = Jenis_Kegiatan::select('id', 'nama')->get();
    //     $ProvinsiList = Provinsi::select('id', 'nama')->get();
    //     $sektorList = TargetReinstra::select('id', 'nama')->get();

    //     $kegiatan->tanggalmulai = Carbon::parse($kegiatan->tanggalmulai)->format('Y-m-d');
    //     $kegiatan->tanggalselesai = Carbon::parse($kegiatan->tanggalselesai)->format('Y-m-d');
    //     $statusOptions = Kegiatan::STATUS_SELECT;

    //     $kabupatenList = collect([]);
    //     $kecamatanList = collect([]);
    //     $desaList = collect([]);

    //     // Process location data if exists
    //     if (!$kegiatan->lokasi->isEmpty()) {
    //         // Get the first location for initial dropdown population
    //         $firstLokasi = $kegiatan->lokasi->first();

    //         if (
    //             $firstLokasi && $firstLokasi->desa && $firstLokasi->desa->kecamatan &&
    //             $firstLokasi->desa->kecamatan->kabupaten && $firstLokasi->desa->kecamatan->kabupaten->provinsi
    //         ) {

    //             $provinsiId = $firstLokasi->desa->kecamatan->kabupaten->provinsi->id;
    //             $kabupatenId = $firstLokasi->desa->kecamatan->kabupaten->id;
    //             $kecamatanId = $firstLokasi->desa->kecamatan->id;

    //             // Load location hierarchies for all locations
    //             $kabupatenList = Kabupaten::select('id', 'nama')
    //                 ->where('provinsi_id', $provinsiId)
    //                 ->get();

    //             $kecamatanList = Kecamatan::select('id', 'nama')
    //                 ->where('kabupaten_id', $kabupatenId)
    //                 ->get();

    //             //each desa_id might have different kecamatan and the desa list should not depend to kecamatanID instead in this , kecamartan list should depend on desa_id at kegiatan_lokasi while merge with master kecamatan depend on kabupaten_id
    //             $desaList = Kelurahan::select('id', 'nama')
    //                 ->where('kecamatan_id', $kecamatanId)
    //                 ->get();
    //             // Kegiatan_Lokasi::with('desa.kecamatan')->where('kegiatan_id', $kegiatan->id)->get();
    //             //');
    //         }
    //     }

    //     foreach ($kegiatan->lokasi as $lokasi) {
    //         $lokasi->desa = Kelurahan::find($lokasi->desa_id);
    //         // $lokasi->kecamatan = Kecamatan::find($lokasi->desa->kecamatan_id);
    //         // $lokasi->kabupaten = Kabupaten::find($lokasi->desa->kecamatan->kabupaten_id);
    //         $lokasi->kecamatan = Kecamatan::find($lokasi->desa ? $lokasi->desa->kecamatan_id : null);
    //         $lokasi->kabupaten = Kabupaten::find($lokasi->desa->kecamatan ? $lokasi->desa->kecamatan->kabupaten_id : null);
    //         $lokasi->provinsi = Provinsi::find($lokasi->desa->kecamatan->kabupaten->provinsi_id);
    //     }
    //     // if (!$kegiatan->lokasi->isEmpty()) {
    //     //     $provinsiId = $lokasi->desa->kecamatan->kabupaten->provinsi->id;
    //     //     // $kabupatenId = $lokasi->desa->kecamatan->kabupaten->id;
    //     //     // $kecamatanId = $lokasi->desa->kecamatan->id;
    //     //     // $desaId = $lokasi->desa->id;

    //     //     // $ProvinsiList = Provinsi::select('id', 'nama')->where('id', $provinsiId)->get();
    //     //     // $kabupatenList = Kabupaten::select('id', 'nama')->where('provinsi_id', $provinsiId)->get();
    //     //     // $kecamatanList = Kecamatan::select('id', 'nama')->where('kabupaten_id', $kabupatenId)->get();
    //     //     // $desaList = Kelurahan::select('id', 'nama')->where('kecamatan_id', $kecamatanId)->get();
    //     //     $kabupatenList = Kabupaten::select('id', 'nama')->where('provinsi_id', $provinsiId)->get();
    //     //     $kecamatanList = Kecamatan::select('id', 'nama')->where('kabupaten_id', $lokasi->kabupaten->id)->get();
    //     //     $desaList = Kelurahan::select('id', 'nama')->where('kecamatan_id', $lokasi->kecamatan->id)->get();
    //     // }

    //     // return $desaList;
    //     return view('tr.kegiatan.edit', compact(
    //         'kegiatan',
    //         'statusOptions',
    //         'jenisKegiatanList',
    //         'sektorList',
    //         'ProvinsiList',
    //         'kabupatenList',
    //         'kecamatanList',
    //         'desaList',
    //     ));
    // }

    public function edit($id)
    {
        $kegiatan = Kegiatan::with([
            'programOutcomeOutputActivity',
            'sektor',
            'mitra',
            'user',
            'lokasi.desa.kecamatan.kabupaten.provinsi',
            'jenisKegiatan',
            'lokasi_kegiatan',
            'kegiatan_penulis.peran',
            'kegiatan_penulis.user',
            'assessment',
            'sosialisasi',
            'pelatihan',
            'pembelanjaan',
            'pengembangan',
            'kampanye',
            'pemetaan',
            'monitoring',
            'kunjungan',
            'konsultasi',
            'lainnya'
        ])->findOrFail($id);

        $jenisKegiatanList = Jenis_Kegiatan::select('id', 'nama')->get();
        $provinsiList = Provinsi::select('id', 'nama')->get();
        $sektorList = TargetReinstra::select('id', 'nama')->get();

        $kegiatan->tanggalmulai = Carbon::parse($kegiatan->tanggalmulai)->format('Y-m-d');
        $kegiatan->tanggalselesai = Carbon::parse($kegiatan->tanggalselesai)->format('Y-m-d');
        $statusOptions = Kegiatan::STATUS_SELECT;

        // Determine preselected provinsi and kabupaten from the first lokasi, if available
        $preselectedProvinsiId = $kegiatan->lokasi->first()->desa->kecamatan->kabupaten->provinsi->id ?? null;
        $preselectedKabupatenId = $kegiatan->lokasi->first()->desa->kecamatan->kabupaten->id ?? null;

        // Initialize empty collections for location dropdowns (to be populated dynamically via JS)
        $kabupatenList = collect([]);
        $kecamatanList = collect([]);
        $desaList = collect([]);

        // Dokumen
        $dokumen_initialPreview = [];
        $dokumen_initialPreviewConfig = [];
        $dokumen_files = $kegiatan->getMedia('dokumen_pendukung');
        foreach ($dokumen_files as $file) {
            $dokumen_initialPreview[] = $file->getUrl();
            $dokumen_initialPreviewConfig[] = [
                'caption' => $file->name,
                'url' => route('api.kegiatan.delete_media', ['media_id' => $file->id]),
                'key' => $file->id,
                'extra' => ['_token' => csrf_token()]
            ];
        }

        // Media
        $media_initialPreview = [];
        $media_initialPreviewConfig = [];
        $media_files = $kegiatan->getMedia('media_pendukung');
        foreach ($media_files as $file) {
            $media_initialPreview[] = $file->getUrl();
            $media_initialPreviewConfig[] = [
                'caption' => $file->name,
                'url' => route('api.kegiatan.delete_media', ['media_id' => $file->id]),
                'key' => $file->id,
                'extra' => ['_token' => csrf_token()]
            ];
        }


        return view('tr.kegiatan.edit', compact(
            'kegiatan',
            'statusOptions',
            'jenisKegiatanList',
            'sektorList',
            'provinsiList',
            'kabupatenList',
            'kecamatanList',
            'desaList',
            'preselectedProvinsiId',
            'preselectedKabupatenId',
            'dokumen_initialPreview',
            'dokumen_initialPreviewConfig',
            'media_initialPreview',
            'media_initialPreviewConfig'
        ));
    }

    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $validated = $request->validate([
            'programoutcomeoutputactivity_id' => 'required|exists:trprogramoutcomeoutputactivity,id',
            'jeniskegiatan_id' => 'required|exists:mjeniskegiatan,id',
            'tanggalmulai' => 'required|date',
            'tanggalselesai' => 'required|date|after:tanggalmulai',
            'status' => 'required|in:Planned,Ongoing,Completed',
            'deskripsilatarbelakang' => 'nullable|string',
            'deskripsitujuan' => 'nullable|string',
            'deskripsikeluaran' => 'nullable|string',
            'deskripsiyangdikaji' => 'nullable|string',
            'penerimamanfaatdewasaperempuan' => 'nullable|integer|min:0',
            'penerimamanfaatdewasalakilaki' => 'nullable|integer|min:0',
            'penerimamanfaatremajaperempuan' => 'nullable|integer|min:0',
            'penerimamanfaatremajalakilaki' => 'nullable|integer|min:0',
            'penerimamanfaatanakperempuan' => 'nullable|integer|min:0',
            'penerimamanfaatanaklakilaki' => 'nullable|integer|min:0',
            'penerimamanfaatdisabilitasperempuan' => 'nullable|integer|min:0',
            'penerimamanfaatdisabilitaslakilaki' => 'nullable|integer|min:0',
            'desa_id' => 'nullable|exists:kelurahan,id',
            'lokasi' => 'nullable|string',
            'long' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'mitra' => 'nullable|array',
            'mitra.*' => 'exists:mpartner,id',
            'penulis' => 'nullable|array',
            'penulis.*' => 'exists:users,id',
        ]);

        $kegiatan->update($validated);

        // Sync mitra
        $kegiatan->mitra()->sync($request->input('mitra', []));

        // Sync penulis (assuming peran_id is handled separately or defaults to a specific role)
        $kegiatan->penulis()->sync($request->input('penulis', []));

        // Update or create location
        if ($request->filled('desa_id') || $request->filled('lokasi') || $request->filled('long') || $request->filled('lat')) {
            $kegiatan->lokasi()->updateOrCreate(
                ['kegiatan_id' => $kegiatan->id],
                [
                    'desa_id' => $request->desa_id,
                    'lokasi' => $request->lokasi,
                    'long' => $request->long,
                    'lat' => $request->lat,
                ]
            );
        }

        return redirect()->route('tr.kegiatan.index')->with('success', 'Kegiatan updated successfully.');
    }


    public function destroy($id)
    {
        return false;
    }

    public function getActivityProgram2($programId)
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

        // $data = mSektor::when(!empty($ids), function ($query) use ($ids) {
        $data = TargetReinstra::when(!empty($ids), function ($query) use ($ids) {
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

    // public function storeKegiatanHasil(Request $request, Kegiatan $kegiatan)
    // {
    //     $jenisKegiatan = $request->input('jeniskegiatan_id');
    //     $idKegiatan = $kegiatan->id;

    //     switch ($jenisKegiatan) {
    //         case 1: // Assessment
    //             Kegiatan_Assessment::create(array_merge($request->only([
    //                 'assessmentyangterlibat',
    //                 'assessmenttemuan',
    //                 'assessmenttambahan',
    //                 'assessmenttambahan_ket',
    //                 'assessmentkendala',
    //                 'assessmentisu',
    //                 'assessmentpembelajaran'
    //             ]), ['kegiatan_id' => $idKegiatan]));
    //             break;
    //         case 2: // Sosialisasi
    //             Kegiatan_Sosialisasi::create(array_merge($request->only([
    //                 'sosialisasiyangterlibat',
    //                 'sosialisasitemuan',
    //                 'sosialisasitambahan',
    //                 'sosialisasitambahan_ket',
    //                 'sosialisasikendala',
    //                 'sosialisasiisu',
    //                 'sosialisasipembelajaran'
    //             ]), ['kegiatan_id' => $idKegiatan]));
    //             break;
    //         case 3: // Pelatihan
    //             Kegiatan_Pelatihan::create(array_merge($request->only([
    //                 'pelatihanpelatih',
    //                 'pelatihanhasil',
    //                 'pelatihandistribusi',
    //                 'pelatihandistribusi_ket',
    //                 'pelatihanrencana',
    //                 'pelatihanunggahan',
    //                 'pelatihanisu',
    //                 'pelatihanpembelajaran'
    //             ]), ['kegiatan_id' => $idKegiatan]));
    //             break;
    //         case 4: // Pembelanjaan
    //             Kegiatan_Pembelanjaan::create(array_merge($request->only([
    //                 'pembelanjaandetailbarang',
    //                 'pembelanjaanmulai',
    //                 'pembelanjaanselesai',
    //                 'pembelanjaandistribusimulai',
    //                 'pembelanjaandistribusiselesai',
    //                 'pembelanjaanterdistribusi',
    //                 'pembelanjaanakandistribusi',
    //                 'pembelanjaanakandistribusi_ket',
    //                 'pembelanjaankendala',
    //                 'pembelanjaanisu',
    //                 'pembelanjaanpembelajaran'
    //             ]), ['kegiatan_id' => $idKegiatan]));
    //             break;
    //         case 5: // Pengembangan
    //             Kegiatan_Pengembangan::create(array_merge($request->only([
    //                 'pengembanganjeniskomponen',
    //                 'pengembanganberapakomponen',
    //                 'pengembanganlokasikomponen',
    //                 'pengembanganyangterlibat',
    //                 'pengembanganrencana',
    //                 'pengembangankendala',
    //                 'pengembanganisu',
    //                 'pengembanganpembelajaran'
    //             ]), ['kegiatan_id' => $idKegiatan]));
    //             break;
    //         case 6: // Kampanye
    //             Kegiatan_Kampanye::create(array_merge($request->only([
    //                 'kampanyeyangdikampanyekan',
    //                 'kampanyejenis',
    //                 'kampanyebentukkegiatan',
    //                 'kampanyeyangterlibat',
    //                 'kampanyeyangdisasar',
    //                 'kampanyejangkauan',
    //                 'kampanyerencana',
    //                 'kampanyekendala',
    //                 'kampanyeisu',
    //                 'kampanyepembelajaran'
    //             ]), ['kegiatan_id' => $idKegiatan]));
    //             break;
    //         case 7: // Pemetaan
    //             Kegiatan_Pemetaan::create(array_merge($request->only([
    //                 'pemetaanyangdihasilkan',
    //                 'pemetaanluasan',
    //                 'pemetaanunit',
    //                 'pemetaanyangterlibat',
    //                 'pemetaanrencana',
    //                 'pemetaanisu',
    //                 'pemetaanpembelajaran'
    //             ]), ['kegiatan_id' => $idKegiatan]));
    //             break;
    //         case 8: // Monitoring
    //             Kegiatan_Monitoring::create(array_merge($request->only([
    //                 'monitoringyangdipantau',
    //                 'monitoringdata',
    //                 'monitoringyangterlibat',
    //                 'monitoringmetode',
    //                 'monitoringhasil',
    //                 'monitoringkegiatanselanjutnya',
    //                 'monitoringkegiatanselanjutnya_ket',
    //                 'monitoringkendala',
    //                 'monitoringisu',
    //                 'monitoringpembelajaran'
    //             ]), ['kegiatan_id' => $idKegiatan]));
    //             break;
    //         case 9: // Kunjungan
    //             Kegiatan_Kunjungan::create(array_merge($request->only([
    //                 'kunjunganlembaga',
    //                 'kunjunganpeserta',

    //                 'kunjunganyangdilakukan',
    //                 'kunjunganhasil',
    //                 'kunjunganpotensipendapatan',
    //                 'kunjunganrencana',
    //                 'kunjungankendala',
    //                 'kunjunganisu',
    //                 'kunjunganpembelajaran'
    //             ]), ['kegiatan_id' => $idKegiatan]));
    //             break;
    //         case 10: // Konsultasi
    //             Kegiatan_Konsultasi::create(array_merge($request->only([
    //                 'konsultasilembaga',
    //                 'konsultasikomponen',
    //                 'konsultasiyangdilakukan',
    //                 'konsultasihasil',
    //                 'konsultasipotensipendapatan',
    //                 'konsultasirencana',
    //                 'konsultasikendala',
    //                 'konsultasiisu',
    //                 'konsultasipembelajaran'
    //             ]), ['kegiatan_id' => $idKegiatan]));
    //             break;
    //         case 11: // Lainnya
    //             Kegiatan_Lainnya::create(array_merge($request->only([
    //                 'lainnyamengapadilakukan',
    //                 'lainnyadampak',
    //                 'lainnyasumberpendanaan',
    //                 'lainnyasumberpendanaan_ket',
    //                 'lainnyayangterlibat',
    //                 'lainnyarencana',
    //                 'lainnyakendala',
    //                 'lainnyaisu',
    //                 'lainnyapembelajaran'
    //             ]), ['kegiatan_id' => $idKegiatan]));
    //             break;
    //         default:
    //             // Handle invalid jenisKegiatan (e.g., throw an exception)
    //             throw new \Exception("Invalid jenisKegiatan: " . $jenisKegiatan);
    //     }
    // }

    //method to store kegiatan basic tab data
}
