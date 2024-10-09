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
                'files.*' => 'required|file|mimes:jpg,png,gif,pdf|max:14048',
                'captions.*' => 'nullable|string|max:255',
            ]);
            $user = Program::find(10); // Adjust to your logic
            if ($request->hasFile('files')) {
                $timestamp = now()->format('Ymd_His');
                $fileCount = 1;
                foreach ($request->file('files') as $index => $file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $caption = $request->input('captions')[$index] ?? '';
                    $user->addMedia($file)
                        // ->withProperties(['caption' => $caption]) // perlu kolom di table media untuk simpan keterangan file ('caption')
                        ->withCustomProperties(['keterangan_file' => $caption, 'user_id'  => $request->user_id, 'original_name' => $originalName, 'extension' => $extension])
                        ->toMediaCollection('test-upload');
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
        $initialPreview = [];
        $initialPreviewConfig = [];

        foreach ($mediaFiles as $media) {
            if (in_array($media->mime_type, ['image/jpeg', 'image/png', 'image/gif'])) {
                $initialPreview[] = $media->getUrl();
            } elseif ($media->mime_type == 'application/pdf') {
                $initialPreview[] = $media->getUrl(); // Use the actual URL for PDFs
            } else {
                $initialPreview[] = asset('path/to/pdf-icon.png'); // Placeholder for other non-image files
            }

            $initialPreviewConfig[] = [
                // 'caption' => $media->file_name,
                'caption' => $media->getCustomProperty('keterangan'),
                'description' => $media->getCustomProperty('keterangan'),
                'url' => route('trprogram.delete.doc', ['media' => $media->id]), // Route to handle file deletion
                'key' => $media->id,
                'type' => $media->mime_type == 'application/pdf' ? 'pdf' : 'image',
                'downloadUrl' => $media->getUrl(),
                'filename' => $media->getCustomProperty('keterangan'),
            ];
        }

        return view('tr.program.tab.edit', compact('program', 'initialPreview', 'initialPreviewConfig'));
    }

    public function updateDoc(Request $request, Program $program)
    {
        try {
            $request->validate([
                'files.*' => 'required|file|mimes:jpg,png,gif,pdf|max:14048',
                'captions.*' => 'nullable|string|max:255',
            ]);

            $program->update($request->all());

            if (is_countable($program->media) && count($program->media) > 0) {
                foreach ($program->media as $media) {
                    if (!in_array($media->file_name, $request->input('files', []))) {
                        $media->delete();
                    }
                }
            }

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $index => $file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $caption = $request->input('captions')[$index] ?? '';

                    $program->addMedia($file)
                        ->usingName($originalName)
                        ->withCustomProperties([
                            'keterangan' => $caption,
                            'user_id' => auth()->user()->id,
                            'original_name' => $originalName,
                            'extension' => $extension
                        ])
                        ->toMediaCollection('file_pendukung_program');
                }
            }

            return response()->json(['success' => 'Files uploaded successfully', 'program' => $program]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    // public function updateDoc(Request $request, Program $program)
    // {
    //     try {
    //         $request->validate([
    //             'files.*' => 'required|file|mimes:jpg,png,gif,pdf|max:14048',
    //             'captions.*' => 'nullable|string|max:255',
    //         ]);

    //         // Eagerly load files relationship
    //         $program = Program::with('files')->findOrFail($program->id);
    //         $program->update($request->all());

    //         // Ensure $program->files is countable and not null
    //         if (is_countable($program->files) && count($program->files) > 0) {
    //             foreach ($program->files as $media) {
    //                 if (!in_array($media->file_name, $request->input('files', []))) {
    //                     $media->delete();
    //                 }
    //             }
    //         }

    //         $media = $program->files ? $program->files->pluck('file_name')->toArray() : [];
    //         $media = array_diff($media, $request->input('files', []));

    //         if ($request->hasFile('files')) {
    //             foreach ($request->file('files') as $index => $file) {
    //                 $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    //                 $extension = $file->getClientOriginalExtension();
    //                 $caption = $request->input('captions')[$index] ?? '';

    //                 if (count($media) === 0 || !in_array($file->getClientOriginalName(), $media)) {
    //                     $program->addMedia($file)
    //                         ->withCustomProperties([
    //                             'keterangan' => $caption,
    //                             'user_id' => auth()->user()->id,
    //                             'original_name' => $originalName,
    //                             'extension' => $extension
    //                         ])
    //                         ->toMediaCollection('test-upload');
    //                 }
    //             }
    //         }

    //         return response()->json(['success' => 'Files uploaded successfully']);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred.',
    //             'error'   => $e->getMessage(),
    //         ], 500);
    //     }
    // }



    // public function deleteDoc($id)
    public function deleteDoc(Media $media)
    {
        // Debugging statement
        \Log::info("Delete request received for media ID : " . $media->id);

        $media->delete();
        return response()->json(['success' => true]);

        // $media = Media::findOrFail($id);
        // $media->delete();

        // return response()->json(['success' => true]);
    }
}
