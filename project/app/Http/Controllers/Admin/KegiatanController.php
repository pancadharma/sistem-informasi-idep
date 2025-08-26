<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Dusun;
use App\Models\Peran;
use App\Models\Satuan;
use App\Models\mSektor;
use App\Models\Partner;
use App\Models\Program;
use App\Models\Kegiatan;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use App\Models\Jenis_Bantuan;
use App\Models\Jenis_Kegiatan;
use App\Models\TargetReinstra;
use App\Models\Kegiatan_Lokasi;
use App\Models\Program_Outcome;
use App\Models\Kegiatan_Lainnya;
use App\Models\Kegiatan_Penulis;
use Yajra\DataTables\DataTables;
use App\Models\Kegiatan_Kampanye;
use App\Models\Kegiatan_Pemetaan;
use App\Models\Kegiatan_Kunjungan;
use App\Models\Kegiatan_Pelatihan;
use App\Models\Kegiatan_Assessment;
use App\Models\Kegiatan_Konsultasi;
use App\Models\Kegiatan_Monitoring;
use App\Http\Controllers\Controller;
use App\Models\Kegiatan_Sosialisasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Kegiatan_Pembelanjaan;
use App\Models\Kegiatan_Pengembangan;
use App\Models\Program_Outcome_Output;
use App\Http\Resources\KegiatanResource;
use Dotenv\Exception\ValidationException;
use App\Http\Requests\StoreKegiatanRequest;
use App\Http\Requests\UpdateKegiatanRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Program_Outcome_Output_Activity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Controllers\API\KegiatanController as APIKegiatanController;


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
        $durationInDays = $kegiatan->getDurationInDays();
        $data = compact('kegiatan', 'durationInDays');

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('tr.kegiatan.export', $data);
            return $pdf->download('kegiatan-' . $kegiatan->id . '.pdf');
        }

        if ($format === 'docx') {
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $phpWord->setDefaultFontName('Times New Roman');
            $fontStyleName = 'oneUserDefinedStyle';
            $phpWord->addFontStyle(
                $fontStyleName,
                array('name' => 'Tahoma', 'size' => 12, 'color' => '1B2232', 'bold' => true)
            );

            $fontStyle = new \PhpOffice\PhpWord\Style\Font();
            $fontStyle->setBold(true);
            $fontStyle->setName('Tahoma');
            $fontStyle->setSize(13);

            $html = view('tr.kegiatan.export', $data)->render();
            Html::addHtml($section, $html, true, false);
            
            $tempFile = tempnam(sys_get_temp_dir(), 'kegiatan');
            $tempFilePath = pathinfo($tempFile, PATHINFO_DIRNAME);
            $tempFileName = pathinfo($tempFile, PATHINFO_BASENAME);
            
            $phpWord->setDefaultFontSize(12);
            $phpWord->setDefaultFontName('Times New Roman');
            $phpWord->setDefaultParagraphStyle([
                'fontSize' => 12,
                'fontName' => 'Times New Roman',
            ]);

            $tempFile = $tempFilePath . '/' . $tempFileName . '.docx';
            $phpWord->save($tempFile, 'Word2007', true); // save the document and download it
            return response()->download($tempFile, 'kegiatan-' . $kegiatan->id . '.docx')->deleteFileAfterSend(true);
        }

        abort(404);
    }

    // public function create()
    // {
    //     if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_edit') || auth()->user()->can('kegiatan_create')) {
    //         // $program = Program::all();
    //         $statusOptions = Kegiatan::STATUS_SELECT;
    //         $kegiatan = new Kegiatan(); // Empty instance
    //         $kegiatan->setRelation('penulis', collect([])); // Ensure an empty collection

    //         // $programoutcomeoutputactivities = Program_Outcome_Output_Activity::all();

    //         return view('tr.kegiatan.create', compact('statusOptions', 'kegiatan'));
    //     }
    //     return response()->json([
    //         'success' => false,
    //         'status' => 'error',
    //         'message' => 'Unauthorized Permission. Please ask your administrator to assign permissions to access details of this Page',
    //     ], Response::HTTP_FORBIDDEN);
    // }

    public function create()
    {
        // If user is not logged in
        if (!auth()->check()) {
            return redirect()->route('login'); // or any named login route
        }
        if (!auth()->check() || !(auth()->id() === 1 || auth()->user()->can('kegiatan_edit') || auth()->user()->can('kegiatan_create'))) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Unauthorized Permission. Please ask your administrator to assign permissions to access this Page',
                ], Response::HTTP_FORBIDDEN);
            }

            abort(403, 'Unauthorized Permission');
        }

        $statusOptions = Kegiatan::STATUS_SELECT;
        $kegiatan = new Kegiatan();
        $kegiatan->setRelation('penulis', collect([]));

        return view('tr.kegiatan.create', compact('statusOptions', 'kegiatan'));
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
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $kegiatan = Kegiatan::with([
            'programoutcomeoutputactivity.program_outcome_output.program_outcome.program',
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
        $dokumen_files = $kegiatan->getMedia('dokumen_pendukung');
        $dokumen_initialPreview = [];
        $dokumen_initialPreviewConfig = [];

        $imageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $officeTypes = [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.ms-powerpoint',
        ];

        foreach ($dokumen_files as $file) {
            $dokumen_initialPreview[] = $file->getUrl();
            $caption = $file->getCustomProperty('keterangan') ?: $file->name;
            $mimeType = $file->mime_type;

            if (in_array($file->mime_type, $imageTypes)) {
                $type = 'image';
            } elseif ($file->mime_type === 'application/pdf') {
                $type = 'pdf';
            } elseif (in_array($file->mime_type, $officeTypes)) {
                $type = 'office';
            } else {
                $type = 'unknown'; // Default type for other mime types
            }

            $dokumen_initialPreviewConfig[] = [
                'caption'       => $caption,
                'description'   => $caption,
                'url'           => route('api.kegiatan.delete_media', ['media_id' => $file->id]),
                'key'           => $file->id,
                'size'          => $file->size,
                'type'          => $type,
                'downloadUrl'   => $file->getUrl(),
                'thumbnailUrl'  => $file->getUrl(),
                'filename'      => $caption,
                'extra'         => [
                    '_token'    => csrf_token(),
                    'keterangan' => $file->getCustomProperty('keterangan', '' ?? $file->name)
                ]
            ];
        }

        // Media
        $media_files = $kegiatan->getMedia('media_pendukung');
        $media_initialPreview = [];
        $media_initialPreviewConfig = [];

        foreach ($media_files as $file) {
            $media_initialPreview[] = $file->getUrl();
            $caption = $file->getCustomProperty('keterangan') ?: $file->name;
            $mimeType = $file->mime_type;

            if (in_array($file->mime_type, $imageTypes)) {
                $type = 'image';
            } elseif ($file->mime_type === 'application/pdf') {
                $type = 'pdf';
            } elseif (in_array($file->mime_type, $officeTypes)) {
                $type = 'office';
            } else {
                $type = 'unknown'; // Default type for other mime types
            }

            $media_initialPreviewConfig[] = [
                'caption'       => $caption,
                'description'   => $caption,
                'url'           => route('api.kegiatan.delete_media', ['media_id' => $file->id]),
                'key'           => $file->id,
                'size'          => $file->size,
                'type'          => $type,
                'downloadUrl'   => $file->getUrl(),
                'thumbnailUrl'  => $file->getUrl(),
                'filename'      => $caption,
                'extra'         => [
                    '_token'    => csrf_token(),
                    'keterangan' => $file->getCustomProperty('keterangan', '')
                ]
            ];
        }

        // return $dokumen_initialPreviewConfig;

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
            'media_initialPreviewConfig',

        ));
    }

    // public function edit($id)
    // {
    //     $kegiatan = Kegiatan::with([
    //         'programoutcomeoutputactivity.program_outcome_output.program_outcome.program',
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
    //     $provinsiList = Provinsi::select('id', 'nama')->get();
    //     $sektorList = TargetReinstra::select('id', 'nama')->get();

    //     $kegiatan->tanggalmulai = Carbon::parse($kegiatan->tanggalmulai)->format('Y-m-d');
    //     $kegiatan->tanggalselesai = Carbon::parse($kegiatan->tanggalselesai)->format('Y-m-d');
    //     $statusOptions = Kegiatan::STATUS_SELECT;

    //     $preselectedProvinsiId = $kegiatan->lokasi->first()->desa->kecamatan->kabupaten->provinsi->id ?? null;
    //     $preselectedKabupatenId = $kegiatan->lokasi->first()->desa->kecamatan->kabupaten->id ?? null;

    //     $kabupatenList = collect([]);
    //     $kecamatanList = collect([]);
    //     $desaList = collect([]);

    //     // Define allowed extensions
    //     $allowedDocExtensions = ['docx', 'doc', 'ppt', 'pptx', 'xls', 'xlsx', 'pdf'];
    //     $allowedMediaExtensions = ['jpg', 'png', 'jpeg'];

    //     // Dokumen
    //     $dokumen_initialPreview = [];
    //     $dokumen_initialPreviewConfig = [];
    //     $dokumen_files = $kegiatan->getMedia('dokumen_pendukung');
    //     foreach ($dokumen_files as $file) {
    //         try {
    //             $extension = strtolower($file->getExtension());
    //             if (!in_array($extension, $allowedDocExtensions)) {
    //                 \Log::warning("Invalid extension for dokumen file ID {$file->id}: {$extension}");
    //                 continue;
    //             }
    //         $dokumen_initialPreview[] = $file->getUrl();
    //             $caption = $file->getCustomProperty('keterangan') ?: $file->name;
    //         $dokumen_initialPreviewConfig[] = [
    //                 'caption' => $caption, // Avoid HTML in config, handle in JS
    //                 'url' => route('api.kegiatan.delete_media', ['media_id' => $file->id]),
    //             'key' => $file->id,
    //                 'extra' => [
    //                     '_token' => csrf_token(),
    //                     'keterangan' => $file->getCustomProperty('keterangan', '')
    //                 ]
    //         ];
    //         } catch (\Exception $e) {
    //             \Log::error("Failed to process dokumen file ID {$file->id}: {$e->getMessage()}");
    //         }
    //     }

    //     // Media
    //     $media_initialPreview = [];
    //     $media_initialPreviewConfig = [];
    //     $media_files = $kegiatan->getMedia('media_pendukung');
    //     foreach ($media_files as $file) {
    //         try {
    //             $extension = strtolower($file->getExtension());
    //             if (!in_array($extension, $allowedMediaExtensions)) {
    //                 \Log::warning("Invalid extension for media file ID {$file->id}: {$extension}");
    //                 continue;
    //             }
    //         $media_initialPreview[] = $file->getUrl();
    //             $caption = $file->getCustomProperty('keterangan') ?: $file->name;
    //         $media_initialPreviewConfig[] = [
    //                 'caption' => $caption,
    //             'url' => route('api.kegiatan.delete_media', ['media_id' => $file->id]),
    //             'key' => $file->id,
    //                 'extra' => [
    //                     '_token' => csrf_token(),
    //                     'keterangan' => $file->getCustomProperty('keterangan', '')
    //                 ]
    //         ];
    //         } catch (\Exception $e) {
    //             \Log::error("Failed to process media file ID {$file->id}: {$e->getMessage()}");
    //         }
    //     }

    //     return view('tr.kegiatan.edit', compact(
    //         'kegiatan',
    //         'statusOptions',
    //         'jenisKegiatanList',
    //         'sektorList',
    //         'provinsiList',
    //         'kabupatenList',
    //         'kecamatanList',
    //         'desaList',
    //         'preselectedProvinsiId',
    //         'preselectedKabupatenId',
    //         'dokumen_initialPreview',
    //         'dokumen_initialPreviewConfig',
    //         'media_initialPreview',
    //         'media_initialPreviewConfig'
    //     ));
    // }


    // public function update(UpdateKegiatanRequest $request, Kegiatan $kegiatan)
    // {
    //     try {
    //         $kegiatanController = new APIKegiatanController();
    //         $response = $kegiatanController->updateAPI($request, $kegiatan);
    //         return response()->json([
    //             'success' => true,
    //             'status' => 'success',
    //             'data' => $response['data'],
    //             'message' => 'Kegiatan update by UpdateAPI'
    //         ], Response::HTTP_CREATED);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ], Response::HTTP_UNPROCESSABLE_ENTITY);
    //     } catch (HttpException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ], $e->getStatusCode());
    //     }
    // }

    public function update(UpdateKegiatanRequest $request, Kegiatan $kegiatan)
    {
        try {
            $kegiatanController = new APIKegiatanController();
            $response = $kegiatanController->updateAPI($request, $kegiatan);

            if ($response->getStatusCode() === 200) {
                return response()->json([
                    'success' => true,
                    'message' => __('global.update_success'),
                    'redirect' => route('kegiatan.index')
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => $response->getData()->message ?? 'Failed to update kegiatan.'
            ], 400);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update kegiatan: ' . $th->getMessage()
            ], 500);
        }
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

    public function storeMedia(Request $request)
    {
        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');
        $name = uniqid() . '_' . trim($file->getClientOriginalName());
        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

  
    public function uploadTempFile(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:51200',
                'collection' => 'required|string|in:dokumen_pendukung,media_pendukung',
                'name' => 'required|string|max:255',
                'kegiatan_id' => 'required|integer|exists:trkegiatan,id'
            ]);
            
            $file = $request->file('file');
            $name = $request->input('name');
            $collection = $request->input('collection');
            $kegiatanId = $request->input('kegiatan_id');
            
            // Find the kegiatan
            $kegiatan = Kegiatan::findOrFail($kegiatanId);
            
            // Define allowed MIME types based on collection
            $allowedMimes = $collection === 'dokumen_pendukung'
                ? ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'pptx', 'txt']
                : ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi', 'mp3', 'wav'];
            
            // Validate file type
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, $allowedMimes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File type not allowed for this collection'
                ], 422);
            }
            
            // Generate filename with timestamp
            $timestamp = now()->format('Ymd_His');
            $kegiatanName = str_replace(' ', '_', $kegiatan->nama ?? 'kegiatan');
            $fileName = "{$kegiatanName}_{$timestamp}." . $extension;
            
            // Add media to kegiatan with custom name as caption
            $media = $kegiatan
                ->addMedia($file)
                ->withCustomProperties([
                    'keterangan' => $name,
                    'user_id' => auth()->user()->id,
                    'original_name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'extension' => $extension,
                    'updated_by' => auth()->user()->id
                ])
                ->usingName($file->getClientOriginalName())
                ->usingFileName($fileName)
                ->toMediaCollection($collection);
            
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'media_id' => $media->id,
                'file_name' => $fileName,
                'original_name' => $file->getClientOriginalName(),
                'collection' => $collection,
                'url' => $media->getUrl(),
                'size' => $media->size,
                'mime_type' => $media->mime_type
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteMedia(Media $media)
    {
        try {
            // Check if user has permission to delete this media
            if (!auth()->user()->hasRole('admin') && $media->getCustomProperty('user_id') != auth()->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to delete this file'
                ], 403);
            }

            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteTempFile(Request $request)
    {
        try {
            $request->validate([
                'file_path' => 'required|string'
            ]);

            $filePath = $request->input('file_path');

            // Security check - ensure file is in temp directory
            if (strpos($filePath, storage_path('app/temp/uploads')) !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file path'
                ], 400);
            }

            if (file_exists($filePath)) {
                unlink($filePath);
                return response()->json([
                    'success' => true,
                    'message' => 'Temporary file deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file: ' . $e->getMessage()
            ], 500);
        }
    }
}