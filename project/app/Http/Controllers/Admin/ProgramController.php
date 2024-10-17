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
use App\Models\User;
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

    public function getData()
    {
        $programs = Program::all();
        $data = DataTables::of($programs)
            ->addIndexColumn()
            ->addColumn('action', function ($program) {
                return '<div class="button-container"><button type="button" class="btn btn-sm btn-info edit-program-btn" data-action="edit" data-program-id="' . $program->id . '"><i class="fas fa-pencil-alt"></i><span class="d-none d-sm-inline">' . trans('global.edit') . '</span></button>
                        <button type="button" class="btn btn-sm btn-primary view-program-btn" data-action="view" data-program-id="' . $program->id . '"><i class="fas fa-folder-open"></i> <span class="d-none d-sm-inline">' . trans('global.view') . '</span></button></div>';
            })
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
            // $targetreinstra = TargetReinstra::pluck('id', 'nama');
            return view('tr.program.create');
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
                        ->toMediaCollection('file_pendukung_program', 'program_uploads');

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

            $newPendonor = $request->input('pendonor_id', []);
            $nilaiD = $request->input('nilaidonasi', []);

            if (count($newPendonor) !== count($nilaiD)) {
                throw new Exception('Mismatched pendonor_id and nilaidonasi arrays length');
            }

            $newDonasi = [];
            foreach ($newPendonor as $index => $pendonor_id) {
                if (isset($nilaiD[$index])) {
                    $newDonasi[] = [
                        'pendonor_id' => $pendonor_id,
                        'nilaidonasi' => $nilaiD[$index]
                    ];
                } else {
                    throw new Exception("Missing donation value for donor ID $pendonor_id at index $index");
                }
            }

            foreach ($newDonasi as $donation) {
                $program->pendonor()->attach($donation['pendonor_id'], ['nilaidonasi' => $donation['nilaidonasi']]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $program,
                "message" => __('cruds.data.data') . ' ' . __('cruds.program.title') . ' ' . $request->nama . ' ' . __('cruds.data.added'),
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

    public function edit(Program $program)
    {
        if (auth()->user()->id == 1 || auth()->user()->can('program_edit')) {

            $targetReinstra = TargetReinstra::pluck('nama', 'id');
            $kelompokMarjinal = Kelompok_Marjinal::pluck('nama', 'id');
            $KaitanSDGs = KaitanSdg::pluck('nama', 'id');
            $program->load('targetReinstra', 'kelompokMarjinal', 'kaitanSDG', 'lokasi', 'pendonor');

            $file_pendukung = Program::find($program->id);
            $mediaFiles = $file_pendukung->getMedia('file_pendukung_program');
            $initialPreview = [];
            $initialPreviewConfig = [];

            foreach ($mediaFiles as $media) {
                if (in_array($media->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/*'])) {
                    $initialPreview[] = $media->getUrl();
                } elseif ($media->mime_type == 'application/pdf') {
                    $initialPreview[] = $media->getUrl();
                } elseif ($media->mime_type == 'application/vnd.ms-powerpoint' || $media->mime_type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation') {
                    $initialPreview[] = $media->getUrl();
                } else {
                    $initialPreview[] =
                        $media->getUrl();;
                }

                $caption = $media->getCustomProperty('keterangan') != '' ? $media->getCustomProperty('keterangan') : $media->name;
                $initialPreviewConfig[] = [
                    'caption' => "<span class='text-red strong'>{$caption}</span>",
                    'description' => $media->getCustomProperty('keterangan') ?? $media->file_name,
                    'key' => $media->id ?? '',
                    'size' => $media->size ?? '',
                    'type' => $media->mime_type == 'application/pdf' ? 'pdf' : ($media->mime_type == 'application/vnd.ms-powerpoint' || $media->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ? 'office' : 'image'),
                    'downloadUrl' => $media->getUrl() ?? '',
                    'filename' => $media->getCustomProperty('keterangan') ?? $media->name,

                ];
            }

            return view('tr.program.edit', compact('program', 'initialPreview', 'initialPreviewConfig'));
        }
        abort(Response::HTTP_FORBIDDEN, 'Unauthorized Permission. Please ask your administrator to assign permissions to access and edit a program');
    }
    // public function update(UpdateProgramRequest $request, Program $program)
    // {
    // $program->update($request->all());

    //  $newFiles = $request->file('files', []);
    //  $newFileNames = array_map(function ($file) {
    //      return $file->getClientOriginalName();
    //  }, $newFiles);
    //  \Log::info(  $newFileNames);
    //  if (is_countable($program->media) && count($program->media) > 0) {
    //      foreach ($program->media as $media) {
    //          if (in_array($media->name, $newFileNames)) {
    //              \Log::info('Deleting Media: ' . $media->name);
    //              $media->delete();
    //              $data = response()->json([
    //                  "message" => "File Exists {$media->name} and will be replaced",
    //                  "success" => true,
    //                  "file_name" => $media->name,
    //                  "file_id" => $media->id,
    //                  'files' => $newFiles
    //              ]);
    //          }
    //      }
    //  }

    //  if ($request->hasFile('files')) {
    //      foreach ($newFiles as $index => $file) {
    //          $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    //          $extension = $file->getClientOriginalExtension();
    //          $caption = $request->input('captions')[$index] ?? "{$originalName}.{$extension}";

    //          $program->addMedia($file)
    //              ->usingName("{$originalName}.{$extension}")
    //              ->withCustomProperties([
    //                  'keterangan' => $caption,
    //                  'user_id' => auth()->user()->id,
    //                  'original_name' => $originalName,
    //                  'extension' => $extension,
    //                  'updated_by' => auth()->user()->id
    //              ])
    //              ->toMediaCollection('file_pendukung_program');
    //      }
    //  }
    // }

    public function update(UpdateProgramRequest $request, Program $program)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $program->update($request->all());
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
                        ->toMediaCollection('file_pendukung_program');
                }
            }
            // update program lokasi
            $program->lokasi()->sync($request->input('lokasi', []));

            // update program donatur
            $newPendonor = $request->input('pendonor_id', []);
            $nilaiD = $request->input('nilaidonasi', []);

            if (count($newPendonor) !== count($nilaiD)) {
                throw new Exception('Mismatched pendonor_id and nilaidonasi arrays length');
            }

            $newDonasi = [];
            foreach ($newPendonor as $index => $pendonor_id) {
                if (isset($nilaiD[$index])) {
                    $newDonasi[] = [
                        'pendonor_id' => $pendonor_id,
                        'nilaidonasi' => $nilaiD[$index]
                    ];
                } else {
                    throw new Exception("Missing donation value for donor ID $pendonor_id at index $index");
                }
            }

            foreach ($newDonasi as $donation) {
                $program->pendonor()->attach($donation['pendonor_id'], ['nilaidonasi' => $donation['nilaidonasi']]);
            }

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
        $program = Program::find($id);
        $mediaFiles = $program->getMedia('file_pendukung_program');
        $preview_pendukung = [];
        $config_pendukung = [];

        foreach ($mediaFiles as $media) {
            if (in_array($media->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/*'])) {
                $preview_pendukung[] = $media->getUrl();
            } elseif ($media->mime_type == 'application/pdf') {
                $preview_pendukung[] = $media->getUrl();
            } elseif ($media->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $media->mime_type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation' || $media->mime_type == 'application/vnd.ms-powerpoint') {
                $preview_pendukung[] = $media->getUrl();
            } else {
                $preview_pendukung[] = $media->getUrl();
            }

            $caption = $media->getCustomProperty('keterangan') != '' ? $media->getCustomProperty('keterangan') : $media->name;
            $config_pendukung[] = [
                'caption' => "<span class='text-red strong'>{$caption}</span>",
                'description' => $media->getCustomProperty('keterangan') ?? $media->file_name,
                'key' => $media->id ?? '',
                'size' => $media->size,
                'type' => $media->mime_type == 'application/pdf' ? 'pdf' : ($media->mime_type == 'application/vnd.ms-powerpoint' || $media->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ? 'office' : 'image'),
                'downloadUrl' => $media->getUrl() ?? '',
                'filename' => $media->getCustomProperty('keterangan') ?? $media->name,
            ];
        }

        return response()->json([
            'initialPreview' => $preview_pendukung,
            'initialPreviewConfig' => $config_pendukung,
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
            $pendonor = MPendonor::findOrFail($request->id);
            $programPendonor = Program::with(['pendonor' => function ($query) use ($request) {
                $query->where('mpendonor.id', $request->id);
            }])->whereHas('pendonor', function ($query) use ($request) {
                $query->where('mpendonor.id', $request->id);
            })->first();

            $nilaidonasi = $programPendonor ? $programPendonor->pendonor->first()->pivot->nilaidonasi : 0;

            return response()->json([
                'id' => $pendonor->id,
                'nama' => $pendonor->nama,
                'email' => $pendonor->email,
                'phone' => $pendonor->phone,
                'nilaidonasi' => $nilaidonasi, // Include nilaidonasi if available
            ]);
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
        // Used to Search staff in DATABASEfor select2 selection
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $staff = User::where('nama', 'like', "%{$search}%")->get();
        return response()->json($staff);
    }

    // not used yet
    public function TargetReinstra(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $targetreinstra = TargetReinstra::where('nama', 'like', "%{$search}%")->get();
        return response()->json($targetreinstra);
    }
}