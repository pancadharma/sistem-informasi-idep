<?php

namespace App\Http\Controllers\Admin;

use App\Models\Program;
use App\Models\KaitanSdg;
use Illuminate\Http\Request;
use App\Models\TargetReinstra;
use Illuminate\Validation\ValidationException;
use App\Models\Kelompok_Marjinal;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreProgramRequest;
use App\Models\Program_Outcome;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TrProgramController extends Controller
{
    use MediaUploadingTrait;
    public function index()
    {

        return view('tr.program.index');
    }
    public function create()
    {

        if (auth()->user()->id == 1 || auth()->user()->can('program_create')) {
            $targetreinstra = TargetReinstra::pluck('id', 'nama');
            return view('tr.program.create', compact('targetreinstra'));
        }
        abort(Response::HTTP_FORBIDDEN, 'Unauthorized Permission. Please ask your administrator to assign permissions to access and create a program');
    }
    public function show()
    {

        if (auth()->user()->id == 1 || auth()->user()->can('program_edit')) {

            return view('tr.program.edit');
        }
        abort(Response::HTTP_FORBIDDEN, 'Unauthorized Permission. Please ask your administrator to assign permissions to access and edit Program');
    }
    // STORE DATA
    public function store(StoreProgramRequest $request, Program $program)
    {
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

                foreach ($request->file('file_pendukung') as $file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $programName = str_replace(' ', '_', $program->nama);
                    $fileName = "{$programName}_{$timestamp}_{$fileCount}.{$extension}";

                    \Log::info('Uploading file: ' . $fileName);
                    $program->addMedia($file)
                        ->usingName("{$programName}_{$fileCount}")
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
            return response()->json([
                'success' => true,
                'data' => $program,
                'message' => 'Data Program Berhasil Ditambahkan',
            ]);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
            ], 404);
        } catch (HttpException $e) {
            // Handle HTTP-specific exceptions
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (Exception $e) {
            // Handle all other exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // GET DATA
    public function dataProgramTargetReinstra(Request $request)
    {
        if ($request->ajax()) {
            $targetreinstra = Program::with('targetReinstra');
            return response()->json($targetreinstra);
        }
    }

    public function TargetReinstra(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $targetreinstra = TargetReinstra::where('nama', 'like', "%{$search}%")->get();
        return response()->json($targetreinstra);
    }
    public function KelompokMarjinal(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $marjinal = Kelompok_Marjinal::where('nama', 'like', "%{$search}%")->get();
        return response()->json($marjinal);
    }

    public function KaitanSDG(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $sdg = KaitanSdg::where('nama', 'like', "%{$search}%")->get();
        return response()->json($sdg);
    }

    public function filePendukung(Request $request) {}
    public function uploadDoc(Request $request)
    {
        try {
            $request->validate([
                'files.*' => 'nullable|file|mimes:jpg,png,jpeg,docx,doc,ppt,pptx,xls,xlsx,gif,pdf|max:14048',
                'captions.*' => 'nullable|string|max:255',
            ]);
            // Assuming you have a user with ID 19, you can adjust this to your logic.
            // this is experimental purposes only
            $user = Program::find(18); // Adjust to your logic

            if ($request->hasFile('files')) {
                $timestamp = now()->format('Ymd_His');
                $fileCount = 1;
                foreach ($request->file('files') as $index => $file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $caption = $request->input('captions')[$index] ?? "{$originalName}.{$extension}";

                    $user->addMedia($file)
                        ->usingName("{$originalName}.{$extension}")
                        ->usingFileName("{$originalName}.{$extension}")
                        ->withCustomProperties(['keterangan' => $caption, 'user_id'  => auth()->user()->id, 'original_name' => $originalName, 'extension' => $extension])
                        ->toMediaCollection('file_pendukung_program');
                }
            }
            return response()->json(['success' => 'Files uploaded successfully']);
        } catch (Exception $e) {
            // Handle all other exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function doc()
    {
        return view('tr.program.tab.test');
    }
    public function docEdit($id)
    {
        $program = Program::find($id);
        $mediaFiles = $program->getMedia('file_pendukung_program');
        $preview_pendukung = [];
        $config_pendukung = [];

        foreach ($mediaFiles as $media) {
            if (in_array($media->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/*'])) {
                $preview_pendukung[] = $media->getUrl();
            } elseif ($media->mime_type == 'application/pdf') {
                $preview_pendukung[] = $media->getUrl(); // Use the actual URL for PDFs
            } elseif ($media->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $media->mime_type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation') {
                $preview_pendukung[] = $media->getUrl(); // Use the actual URL for PDFs
            } else {
                $preview_pendukung[] = $media->getUrl(); // Placeholder for other non-image files
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

        return view('tr.program.tab.edit', compact('program', 'preview_pendukung', 'config_pendukung'));
    }

    // Update File Pendukung Tanpa Hapus Existing Files
    public function updateDoc(Request $request, Program $program)
    {
        try {
            $request->validate([
                'file_pendukung.*' => 'nullable|file|mimes:jpg,png,jpeg,docx,doc,ppt,pptx,xls,xlsx,gif,pdf|max:14048',
                'keterangan.*' => 'nullable|string|max:255',
            ]);

            $program->update($request->all());

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
                        $data = response()->json([
                            "message" => "File Exists {$media->name} and will be replaced",
                            "success" => true,
                            "file_name" => $media->name,
                            "file_id" => $media->id,
                            'files' => $newFiles
                        ]);
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
            return response()->json(['success' => 'Files uploaded successfully', 'program' => $program, 'files' => $newFiles]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteDoc(Media $media)
    {
        \Log::info("Delete request received for media ID : " . $media->id);

        $media->delete();
        return response()->json(['success' => true]);
    }


    public function getMedia($id)
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

    // purpose to test outcome create
    public function testOutcome()
    {
        $program = Program::orderByDesc('id')->first();

        if (!$program) {
            // Handle the case where no program exists
            return response()->json(['message' => 'No program found'], 404);
        }

        $program_id = $program->id + 99999; //purpose to test
        return view('test.outcome', ['program_id' => $program_id]);
    }
    // purpose to test outcome create
    public function testSubmitOutcome(Request $request)
    {
        // $program = Program::create($request->only(['program_name']));
        $id = $request->input('program_id');
        // Store the outcomes
        foreach ($request->input('deskripsi') as $index => $deskripsi) {
            Program_Outcome::create([
                'program_id' => 8, //purspose test since it depends to program_id
                'deskripsi' => $deskripsi,
                'indikator' => $request->input("indikator.$index"),
                'target' => $request->input("target.$index"),
            ]);
        }

        // return response()->json(['program_id' => $program->id, 'message' => 'Program and outcomes saved successfully!']);
        return response()->json([
            'message' => 'Outcome successfully submitted',
            'data' => $request->all(),
            'program_id' => $request->input('program_id'),
        ]);
    }
}
