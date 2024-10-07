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

    public function create(){

        if (auth()->user()->id == 1 || auth()->user()->can('program_create')) {
            // $targetreinstra = TargetReinstra::pluck('id', 'nama');
            return view('tr.program.create');
        }
        abort(Response::HTTP_FORBIDDEN, 'Unauthorized Permission. Please ask your administrator to assign permissions to access and create a program');
    }

    public function store(StoreProgramRequest $request, Program $program){
        try {
            // Gunakan StoreProgramRequest utk validasi users punya akses membuat program
            $data = $request->validated();
            $program = Program::create($data);
            $program->targetReinstra()->sync($request->input('targetreinstra', []));
            $program->kelompokMarjinal()->sync($request->input('kelompokmarjinal', []));
            $program->kaitanSDG()->sync($request->input('kaitansdg', []));

            // Unggah dan simpan berkas menggunakan Spatie Media Library
            if ($request->hasFile('file_pendukung') && $request->validated()) {
                $timestamp = now()->format('Ymd_His');
                $fileCount = 1;

                foreach ($request->file('file_pendukung') as $file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $programName = str_replace(' ', '_', $program->nama);
                    $fileName = "{$programName}_{$timestamp}_{$fileCount}.{$extension}";

                    \Log::info('Uploading file: ' . $fileName .' Orignal Name: '. $originalName);
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

    public function edit(Program $program)
    {
        if (auth()->user()->id == 1 || auth()->user()->can('program_edit')) {
            $mediaFiles = $program->getMedia('file_pendukung_program');
            $initialPreview = [];
            $initialPreviewConfig = [];

            foreach ($mediaFiles as $media) {
                if (in_array($media->mime_type, ['image/jpeg', 'image/png', 'image/gif'])) {
                    $initialPreview[] = $media->getUrl();
                } else {
                    $initialPreview[] = asset('path/to/pdf-icon.png'); // Placeholder for non-image files
                }

                $initialPreviewConfig[] = [
                    'caption' => $media->file_name,
                    'url' => route('program.media.destroy', ['media' => $media->id]), // Route to handle file deletion
                    'key' => $media->id,
                    'type' => $media->mime_type == 'application/pdf' ? 'pdf' : 'image',
                ];
            }

            // return $media->human_readable_size;
            // return [$initialPreviewConfig, $initialPreviewConfig, $media, $program];

            return view('tr.program.edit', compact('program', 'initialPreview', 'initialPreviewConfig'));

        }
        abort(Response::HTTP_FORBIDDEN, 'Unauthorized Permission. Please ask your administrator to assign permissions to access and edit a program');
    }
    public function update(UpdateProgramRequest $request, Program $program)
    {
        $program->update($request->all());

        if (count($program->file_pendukung) > 0) {
            foreach ($program->file_pendukung as $media) {
                if (! in_array($media->file_name, $request->input('file_pendukung', []))) {
                    $media->delete();
                }
            }
        }
        $media = $program->file_pendukung->pluck('file_name')->toArray();
        foreach ($request->input('file_pendukung', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $program->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_pendukung');
            }
        }
        return redirect()->route('tr.programs.index');
    }

    public function ProgramMediaDestroy(Media $media)
    {
        $media->delete();
        return response()->json(['success' => true]);
    }
}
