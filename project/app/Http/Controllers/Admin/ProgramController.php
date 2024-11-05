<?php

namespace App\Http\Controllers\Admin;

use App\Models\Program;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Requests\StoreProgramRequest;
use App\Models\TargetReinstra;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\UpdateProgramRequest;
use App\Models\KaitanSdg;
use App\Models\Kelompok_Marjinal;
use App\Models\MPendonor;
use App\Models\Program_Outcome;
use App\Models\User;
use App\Models\Peran;
use App\Models\Partner;
use App\Models\Program_Outcome_Output;
use App\Models\Program_Outcome_Output_Activity;
use App\Models\ProgramGoal;
use App\Models\ProgramObjektif;
use App\Models\Program_Report_Schedule;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;






class ProgramController extends Controller
{
    public function index()
    {
        // Get all trprogram records
        // $programs = Program::all();
        // return view('tr.program.index', compact('programs')); // Assuming a view exists at resources/views/trprogram/index.blade.php
        return view('tr.program.index'); // Assuming a view exists at resources/views/trprogram/index.blade.php
    }

    public function details(Program $program)
    {
        if (auth()->user()->id == 1 || auth()->user()->can('program_details_edit')) {
            $program->load(['targetReinstra', 'kelompokMarjinal', 'kaitanSDG', 'lokasi', 'pendonor', 'outcome', 'objektif', 'goal']);
            $outcomes = Program_Outcome::where('program_id', $program->id)->get();
            $objektif = ProgramObjektif::where('program_id', $program->id)->get();
            $goal = ProgramGoal::where('program_id', $program->id)->get();
            return view('tr.program.details', compact('program', 'outcomes', 'objektif', 'goal'));
        }
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => 'Unauthorized Permission. Please ask your administrator to assign permissions to access details of this program',
        ], 403);
        // abort(Response::HTTP_FORBIDDEN, 'Unauthorized Permission. Please ask your administrator to assign permissions to access details of this program');
    }




    public function getData()
    {
        $programs = Program::all();
        $data = DataTables::of($programs)
            ->addIndexColumn()
            ->addColumn('action', function ($program) {
                $editButton = '';
                $viewButton = '';
                $detailsButton = '';

                if (auth()->user()->id == 1 || auth()->user()->can('program_edit')) {
                    $editButton = '<button type="button" title="' . __('global.edit') . ' Program ' . $program->nama . '" class="btn btn-sm btn-info edit-program-btn" data-action="edit" data-program-id="' . $program->id . '" data-toggle="tooltip" data-placement="top"><i class="bi bi-pencil-square"></i><span class="d-none d-sm-inline"></span></button>';
                }
                if (auth()->user()->id == 1 || auth()->user()->can('program_details_edit') || auth()->user()->can('program_edit')) {
                    $detailsButton = '<button type="button" title="' . __('global.details') . ' Program ' . $program->nama . '" class="btn btn-sm btn-danger details-program-btn" data-action="details" data-program-id="' . $program->id . '" data-toggle="tooltip" data-placement="top"><i class="bi bi-list-ul"></i><span class="d-none d-sm-inline"></span></button>';
                }
                if (auth()->user()->id == 1 || auth()->user()->can('program_view') || auth()->user()->can('program_access')) {
                    $viewButton = '<button type="button" title="' . __('global.view') . ' Program ' . $program->nama . '" class="btn btn-sm btn-primary view-program-btn" data-action="view" data-program-id="' . $program->id . '" data-toggle="tooltip" data-placement="top"><i class="fas fa-folder-open"></i> <span class="d-none d-sm-inline"></span></button>';
                }
                return "<div class='button-container'>$editButton $viewButton $detailsButton</div>";
            })
            ->rawColumns(['action'])
            ->make(true);
        return $data;
    }

    public function show(Program $program)
    {
        return response()->json($program);
    }

    public function create()
    {

        if (auth()->user()->id == 1 || auth()->user()->can('program_create')) {
            $program = new Program();
            return view('tr.program.create', compact('program'));
        }
        abort(Response::HTTP_FORBIDDEN, 'Unauthorized Permission. Please ask your administrator to assign permissions to access and create a program');
    }

    public function store(StoreProgramRequest $request, Program $program)
    {
        DB::beginTransaction();
        try {
            // Gunakan StoreProgramRequest utk validasi users punya akses membuat program
            $data = $request->validated();
            $program = Program::create($data);
            $program->targetReinstra()->sync($request->input('targetreinstra', []));
            $program->kelompokMarjinal()->sync($request->input('kelompokmarjinal', []));
            $program->kaitanSDG()->sync($request->input('kaitansdg', []));


            // Unggah dan simpan berkas menggunakan Spatie Media Library
            if ($request->hasFile('file_pendukung')) {
                $timestamp = now()->format('Ymd_His');
                $fileCount = 1;

                foreach ($request->file('file_pendukung') as $index => $file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $programName = str_replace(' ', '_', $program->nama);
                    $fileName = "{$programName}_{$timestamp}_{$fileCount}.{$extension}";
                    $keterangan = $request->input('captions')[$index] ?? "{$fileName}";

                    \Log::info('Uploading file: ' . $fileName . ' Orignal Name: ' . $originalName . ' User ID: ' . auth()->user()->nama);
                    $program->addMedia($file)
                        ->withCustomProperties(['keterangan' => $keterangan, 'user_id'  =>  auth()->user()->id, 'original_name' => $originalName, 'extension' => $extension])
                        ->usingName("{$programName}_{$originalName}_{$fileCount}")
                        ->usingFileName($fileName)
                        // ->toMediaCollection('file_pendukung_program', 'program_uploads');
                        ->toMediaCollection('program_' . $program->id, 'program_uploads');

                    $fileCount++;
                }
            } else {
                if (!$request->has('file_pendukung')) {
                    \Log::info('No file_pendukung key found in the request.');
                } elseif (empty($request->file('file_pendukung'))) {
                    \Log::info('file_pendukung key is present but no files were uploaded.');
                } else {
                    \Log::info('No files found in the request.');
                }
            }

            // save program lokasi
            $program->lokasi()->sync($request->input('lokasi', []));

            // save report schedule
            // Ambil input array
            $tanggalArray = $request->input('tanggallaporan', []);
            $keteranganArray = $request->input('keteranganlaporan', []);

            $tanggalketerangan = [];
            foreach ($tanggalArray as $index => $tanggal) {
                if (isset($keteranganArray[$index])) {
                    $tanggalketerangan[] = [
                        'tanggal'       => $tanggal,
                        'keterangan'    => $keteranganArray[$index]
                    ];
                } else {
                    throw new Exception("Missing value for $keteranganArray at index $index");
                }
            }
            foreach ($tanggalketerangan as $newtglket) {
                $program->jadwalreport()->create([
                    'tanggal' => $newtglket['tanggal'],
                    'keterangan' => $newtglket['keterangan']
                ]);
            }

            //save pendonor & donation value
            // $newPendonor = $request->input('pendonor_id', []);
            // $nilaiD = $request->input('nilaidonasi', []);
            // if (count($newPendonor) !== count($nilaiD)) {
            //     throw new Exception('Mismatched pendonor_id and nilaidonasi arrays length');
            // }
            // $newDonasi = [];
            // foreach ($newPendonor as $index => $pendonor_id) {
            //     if (isset($nilaiD[$index])) {
            //         $newDonasi[] = [
            //             'pendonor_id' => $pendonor_id,
            //             'nilaidonasi' => $nilaiD[$index]
            //         ];
            //     } else {
            //         throw new Exception("Missing donation value for donor ID $pendonor_id at index $index");
            //     }
            // }
            // foreach ($newDonasi as $donation) {
            //     $program->pendonor()->attach($donation['pendonor_id'], ['nilaidonasi' => $donation['nilaidonasi']]);
            // }

            $newPendonor = $request->input('pendonor_id', []);
            $nilaiD = $request->input('nilaidonasi', []);

            if (count($newPendonor) !== count($nilaiD)) {
                throw new Exception('Mismatched pendonor_id and nilaidonasi arrays length');
            }
            $newDonasi = array_map(function ($pendonor_id, $donation_value) {
                if (empty($donation_value)) {
                    throw new Exception("Missing donation value for donor ID $pendonor_id");
                }
                return [
                    'pendonor_id' => $pendonor_id,
                    'nilaidonasi' => $donation_value,
                ];
            }, $newPendonor, $nilaiD);
            foreach ($newDonasi as $donation) {
                $program->pendonor()->attach($donation['pendonor_id'], ['nilaidonasi' => $donation['nilaidonasi']]);
            }

            // create program outcome

            foreach ($request->input('deskripsi', []) as $index => $deskripsi) {
                if (!is_null($deskripsi) && $deskripsi !== '') {
                    Program_Outcome::create([
                        'program_id' => $program->id, // Use the dynamically created program ID
                        'deskripsi' => $deskripsi,
                        'indikator' => $request->input("indikator.$index"),
                        'target' => $request->input("target.$index"),
                    ]);
                }
            }
            //COMMIT THE QUERY IF NO ERROR
            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $program,
                "message" => __('cruds.data.data') . ' ' . __('cruds.program.title') . ' ' . $request->nama . ' ' . __('cruds.data.added'),
            ], Response::HTTP_CREATED);

            //ERROR CATCH

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
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Program $program)
    {
        if (auth()->user()->id !== 1 && !auth()->user()->can('program_edit')) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized Permission. Please ask your administrator to assign permissions to access and edit a program');
        }

        $targetReinstra = TargetReinstra::pluck('nama', 'id');
        $kelompokMarjinal = Kelompok_Marjinal::pluck('nama', 'id');
        $KaitanSDGs = KaitanSdg::pluck('nama', 'id');

        // Eager load related models
        //$program->load(['targetReinstra', 'kelompokMarjinal', 'kaitanSDG', 'lokasi', 'pendonor', 'outcome']);
        $program->load(['targetReinstra', 'kelompokMarjinal', 'kaitanSDG', 'lokasi', 'pendonor', 'outcome', 'jadwalreport']);

        // Dynamically fetch media files based on program ID
        $mediaFiles = $program->getMedia('program_' . $program->id);

        // Prepare preview data for all media files
        $initialPreview = [];
        $initialPreviewConfig = [];

        foreach ($mediaFiles as $media) {
            $initialPreview[] = $media->getUrl();

            $caption = $media->getCustomProperty('keterangan') ?: $media->name;
            $mimeType = $media->mime_type;

            $initialPreviewConfig[] = [
                'caption' => "<span class='text-red strong'>{$caption}</span>",
                'description' => $caption,
                'key' => $media->id,
                'size' => $media->size,
                'type' => $this->getMediaType($mimeType),
                'downloadUrl' => $media->getUrl(),
                'filename' => $caption,
            ];
        }

        return view('tr.program.edit', compact('program', 'initialPreview', 'initialPreviewConfig'));
    }

    // Helper method to categorize media types
    private function getMediaType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }

        if ($mimeType === 'application/pdf') {
            return 'pdf';
        }

        if (in_array($mimeType, [
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])) {
            return 'office';
        }

        return 'other';
    }

    public function update(UpdateProgramRequest $request, Program $program)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $program->update($data);
            $program->targetReinstra()->sync($request->input('targetreinstra', []));
            $program->kelompokMarjinal()->sync($request->input('kelompokmarjinal', []));
            $program->kaitanSDG()->sync($request->input('kaitansdg', []));

            $newFiles = $request->file('file_pendukung', []);
            $newFileNames = array_map(function ($file) {
                return $file->getClientOriginalName();
            }, $newFiles);
            \Log::info($newFileNames);

            if (is_countable($program->media) && count($program->media) > 0) {
                foreach ($program->media as $media) {
                    if (in_array($media->name, $newFileNames)) {
                        \Log::info('Deleting Media: ' . $media->name);
                        $media->delete();
                    }
                }
            }

            if ($request->hasFile('file_pendukung')) {
                foreach ($newFiles as $index => $file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $caption = $request->input('keterangan')[$index] ?? "{$originalName}.{$extension}";

                    $program->addMedia($file)
                        ->usingName("{$originalName}.{$extension}")
                        ->withCustomProperties([
                            'keterangan' => $caption,
                            'user_id' => auth()->user()->id,
                            'original_name' => $originalName,
                            'extension' => $extension,
                            'updated_by' => auth()->user()->id
                        ])
                        ->toMediaCollection('program_' . $program->id, 'program_uploads');
                    // ->toMediaCollection('file_pendukung_program', 'program_uploads');
                }
            }
            // update program lokasi
            $program->lokasi()->sync($request->input('lokasi', []));

            // update program donatur
            $this->updateProgramDonatur($program, $request);
            $this->updateProgramOutcomes($program, $request);
            $this->updateJadwalReport($program, $request);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $program,
                "message" => __('cruds.data.data') . ' ' . __('cruds.program.title') . ' ' . $request->nama . ' ' . __('cruds.data.updated'),
            ], Response::HTTP_CREATED);
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
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function ProgramMediaDestroy(Media $media)
    {
        $media->delete();
        return response()->json(['success' => true]);
    }
    // Get Files with collection of file_pendukung_program using JSON
    public function getProgramFilesPendukung($id)
    {
        $program = Program::findOrFail($id); // Use findOrFail to handle not found
        $mediaFiles = $program->getMedia('file_pendukung_program');

        $previewPendukung = [];
        $configPendukung = [];
        $imageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $officeTypes = [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.ms-powerpoint',
        ];

        foreach ($mediaFiles as $media) {
            // Add media URL to preview
            $previewPendukung[] = $media->getUrl();
            $caption = $media->getCustomProperty('keterangan') ?: $media->name;
            if (in_array($media->mime_type, $imageTypes)) {
                $type = 'image';
            } elseif ($media->mime_type === 'application/pdf') {
                $type = 'pdf';
            } elseif (in_array($media->mime_type, $officeTypes)) {
                $type = 'office';
            } else {
                $type = 'unknown'; // Default type for other mime types
            }
            $configPendukung[] = [
                'caption' => "<span class='text-red strong'>{$caption}</span>",
                'description' => $media->getCustomProperty('keterangan') ?: $media->file_name,
                'key' => $media->id ?? '',
                'size' => $media->size,
                'type' => $type,
                'downloadUrl' => $media->getUrl() ?: '',
                'filename' => $caption,
            ];
        }

        return response()->json([
            'initialPreview' => $previewPendukung,
            'initialPreviewConfig' => $configPendukung,
        ]);
    }

    // Method digunakan untuk pencaraian data di select2 program tab donor
    public function getProgramDonor(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $pendonor = MPendonor::where('nama', 'like', "%{$search}%")->get();
        return response()->json($pendonor);
    }

    //method digunakan untuk menampilkan data setelah select2 pada program dipilih, baik pada program create/edit
    public function searchPendonor(Request $request)
    {
        if ($request->ajax()) {
            // Fetch the donor from MPendonor
            $pendonor = MPendonor::findOrFail($request->id);
            $programId = $request->query('program_id');

            // Fetch the donor data with the pivot nilaidonasi value for the given program_id
            $programPendonor = Program::with(['pendonor' => function ($query) use ($request, $programId) {
                $query->where('mpendonor.id', $request->id)
                    ->where('trprogrampendonor.program_id', $programId); // check if exists in pivot table
            }])->where('id', $programId)->first();

            // Check if the donor exists in the pivot table for this program
            if ($programPendonor && $programPendonor->pendonor->isNotEmpty()) {
                // Donor exists in the program, retrieve pivot `nilaidonasi`
                $pendonorData = [];
                foreach ($programPendonor->pendonor as $p) {
                    $pendonorData[] = [
                        'id'           => $p->id,
                        'program_id'   => $p->pivot->program_id,
                        'nama'         => $p->nama,
                        'email'        => $p->email,
                        'phone'        => $p->phone,
                        'nilaidonasi'  => $p->pivot->nilaidonasi ?? 0 // return the actual value or 0
                    ];
                }
            } else {
                // Donor doesn't exist in the program_pendonor pivot table, return default `nilaidonasi` as 0
                $pendonorData = [
                    [
                        'id'           => $pendonor->id,
                        'program_id'   => $programId,
                        'nama'         => $pendonor->nama,
                        'email'        => $pendonor->email,
                        'phone'        => $pendonor->phone,
                        'nilaidonasi'  => 0 // Return 0 since no record exists in pivot
                    ]
                ];
            }

            return response()->json($pendonorData);
        }
    }


    function getPendonorDataEdit(Request $request)
    {
        $program = Program::with(['pendonor' => function ($query) {
            $query->select('mpendonor.id', 'nama', 'email', 'phone', 'trprogrampendonor.nilaidonasi');
        }])->where('id', $request->id)->first();

        $pendonorData = $program->pendonor->map(function ($pendonor) {
            return [
                'id'           => $pendonor->id,
                'program_id'   => $pendonor->pivot->program_id,
                'nama'         => $pendonor->nama,
                'email'        => $pendonor->email,
                'phone'        => $pendonor->phone,
                'nilaidonasi'  => $pendonor->pivot->nilaidonasi, // Getting nilaidonasi from the pivot table
                'text'         => $pendonor->nama,
            ];
        });
        return response()->json($pendonorData);
    }

    public function getProgramStaff(Request $request)
    {
        // Used to Search staff in DATABASE for select2 selection
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $staff = User::where('nama', 'like', "%{$search}%")->get();
        return response()->json($staff);
    }

    public function getProgramPeran(Request $request)
    {
        // Used to Search Peran in DATABASE for select2 selection
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $peran = Peran::where('nama', 'like', "%{$search}%")->get();
        return response()->json($peran);
    }

    public function getProgramPartner(Request $request)
    {
        // Used to Search partner in DATABASE for select2 selection
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $partner = Partner::where('nama', 'like', "%{$search}%")->get();
        return response()->json($partner);
    }

    // not used yet
    public function TargetReinstra(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $targetreinstra = TargetReinstra::where('nama', 'like', "%{$search}%")->get();
        return response()->json($targetreinstra);
    }

    protected function updateJadwalReport($program, Request $request)
    {
        $existingJadwal = $program->jadwalreport()->get();
        $existingJadwalReportId = $existingJadwal->pluck('id')->toArray();
        $newJadwalId = [];

        foreach ($request->input('tanggallaporan', []) as $index => $tanggallaporan) {
            $JadwalReportId = $request->input("jadwalreport_id.$index"); // Use indexed input for existing report schedule

            if (!empty($JadwalReportId) && in_array($JadwalReportId, $existingJadwalReportId)) {
                // Update existing report schedule
                $jadwalreport = Program_Report_Schedule::find($JadwalReportId);
                $jadwalreport->update([
                    'tanggal' => $tanggallaporan,
                    'keterangan' => $request->input("keteranganlaporan.$index"),
                ]);
                $newJadwalId[] = $jadwalreport->id; // Store updated repport schedule ID
            } elseif (!empty($tanggallaporan)) {
                // Create new report schedule if no ID is provided
                $jadwalreport = Program_Report_Schedule::create([
                    'program_id' => $program->id,
                    'tanggal' => $tanggallaporan,
                    'keterangan' => $request->input("keteranganlaporan.$index"),
                ]);
                $newJadwalId[] = $jadwalreport->id; // Store new outcome ID
            }
        }

        // Remove report schedule that are not in the new input
        foreach ($existingJadwal as $existingJadwals) {
            if (!in_array($existingJadwals->id, $newJadwalId)) {
                $existingJadwals->delete();
            }
        }
    }

    protected function updateProgramOutcomes($program, Request $request)
    {
        $existingOutcomes = $program->outcome()->get();
        $existingOutcomeIds = $existingOutcomes->pluck('id')->toArray();
        $newOutcomeIds = [];

        foreach ($request->input('deskripsi', []) as $index => $deskripsi) {
            $outcomeId = $request->input("outcome_id.$index"); // Use indexed input for existing outcomes

            if (!empty($outcomeId) && in_array($outcomeId, $existingOutcomeIds)) {
                // Update existing outcome
                $outcome = Program_Outcome::find($outcomeId);
                $outcome->update([
                    'deskripsi' => $deskripsi,
                    'indikator' => $request->input("indikator.$index"),
                    'target' => $request->input("target.$index"),
                ]);
                $newOutcomeIds[] = $outcome->id; // Store updated outcome ID
            } elseif (!empty($deskripsi)) {
                // Create new outcome if no ID is provided
                $outcome = Program_Outcome::create([
                    'program_id' => $program->id,
                    'deskripsi' => $deskripsi,
                    'indikator' => $request->input("indikator.$index"),
                    'target' => $request->input("target.$index"),
                ]);
                $newOutcomeIds[] = $outcome->id; // Store new outcome ID
            }
        }

        // Remove outcomes that are not in the new input
        foreach ($existingOutcomes as $existingOutcome) {
            if (!in_array($existingOutcome->id, $newOutcomeIds)) {
                $existingOutcome->delete();
            }
        }
    }

    public function updateProgramDonatur($program, Request $request)
    {
        try {
            $newPendonor = $request->input('pendonor_id', []);
            $nilaiD = $request->input('nilaidonasi', []);
            if (count($newPendonor) !== count($nilaiD)) {
                throw new Exception('Mismatched pendonor_id and nilaidonasi arrays length');
            }
            $newDonasi = [];
            foreach ($newPendonor as $index => $pendonor_id) {
                if (!isset($nilaiD[$index])) {
                    throw new Exception("Missing donation value for donor ID $pendonor_id at index $index");
                }
                $newDonasi[$pendonor_id] = ['nilaidonasi' => $nilaiD[$index]];
            }
            $program->pendonor()->sync($newDonasi);
            return response()->json(['success' => true, 'message' => 'Program donor updated successfully.']);
        } catch (Exception $e) {
            \Log::error('Error updating program donor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the program donor. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // return Outcome data in details program outcome
    public function apiOutcome(Program_Outcome $outcome)
    {
        $outcome->load('program');
        if ($outcome) {
            return response()->json([
                'success' => true,
                'status' => 'success',
                'data' => $outcome
            ]);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Data Outcome not found'
            ], 404);
        }
    }

    public function apiObjektif(ProgramObjektif $objektif)
    {
        $output = ProgramObjektif::where('program_id', $objektif)->get();
        $objektif->load('program');
        if ($objektif) {
            return response()->json([
                'success' => true,
                'status' => 'success',
                'data' => $objektif,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Data Objective not found',
            ], 404);
        }
    }

    public function apiOutput(Program_Outcome_Output $output, $outcome)
    {
        $output = Program_Outcome_Output::where('programoutcome_id', $outcome)->get();
        $output->load('program_outcome');
        if ($output) {
            return response()->json([
                'success' => true,
                'status' => 'success',
                'data' => $output,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Data Outcome not found'
            ], 404);
        }
    }

    public function detailsModal()
    {
        if (auth()->user()->id == 1 || auth()->user()->can('program_output_create')) {
            $program = Program::with(['targetReinstra', 'kelompokMarjinal', 'kaitanSDG', 'lokasi', 'pendonor', 'outcome', 'objektif', 'goal'])->where('id', auth()->user()->id)->first();
            return view('tr.program.detail.outcome-detail', compact('program'));
        }
        abort(Response::HTTP_FORBIDDEN, 'Unauthorized Permission. Please ask your administrator to assign permissions to access details of this program');
    }

    public function outputActivityStore(Request $request)
    {

        if (auth()->user()->id == 1 || auth()->user()->can('program_output_create')) {
            $validated = $request->validate([
                'programoutcome_id' => 'required|exists:trprogramoutcome,id',
                'deskripsi' => 'required|string|max:1000',
                'indikator' => 'required|string|max:1000',
                'target' => 'required|string|max:1000',
                'activities' => 'nullable|array',
                'activities.*.deskripsi' => 'nullable|string|max:1000',
                'activities.*.indikator' => 'nullable|string|max:1000',
                'activities.*.target' => 'nullable|string|max:1000',
            ]);

            DB::beginTransaction();
            try {
                // // Create the outcome output
                $outcomeOutput = Program_Outcome_Output::create([
                    'programoutcome_id' => $validated['programoutcome_id'],
                    'deskripsi' => $validated['deskripsi'],
                    'indikator' => $validated['indikator'],
                    'target' => $validated['target'],
                ]);

                //// Add the activities
                foreach ($validated['activities'] as $activity) {
                    Program_Outcome_Output_Activity::create([
                        'programoutcomeoutput_id' => $outcomeOutput->id,
                        'deskripsi' => $activity['deskripsi'],
                        'indikator' => $activity['indikator'],
                        'target' => $activity['target'],
                    ]);
                }
                DB::commit();
                return response()->json([
                    'message' => 'Program Outcome Output and Activities created successfully!',
                    'data' => $outcomeOutput->load('activities')
                ], 201);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'Failed to create Program Outcome Output and Activities!', 'error' => $e->getMessage()], 500);
            } catch (ValidationException $e) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Validation failed.', 'errors' => $e->errors(), 'data'  => $request,], 422);
            } catch (ModelNotFoundException $e) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Resource not found.', 'data' => $request,], 404);
            } catch (HttpException $e) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => $e->getMessage(), 'data' => $request,], $e->getStatusCode());
            } catch (QueryException $e) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'An error occurred.', 'data' => $request, 'error' => $e->getMessage(),], Response::HTTP_SERVICE_UNAVAILABLE);
            }
        }
        return response()->json(['message' => 'Unauthorized Permission. Please ask your administrator to assign permissions to access details of this program'], 403);
    }
}
