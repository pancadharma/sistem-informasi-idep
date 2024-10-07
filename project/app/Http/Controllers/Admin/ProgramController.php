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
                return '<button type="button" class="btn btn-sm btn-info edit-program-btn" data-action="edit" data-program-id="' . $program->id . '"><i class="fas fa-pencil-alt"></i><span class="d-none d-sm-inline">' . trans('global.edit') . '</span></button>
                        <button type="button" class="btn btn-sm btn-primary view-program-btn" data-action="view" data-program-id="' . $program->id . '"><i class="fas fa-folder-open"></i> <span class="d-none d-sm-inline">' . trans('global.view') . '</span></button>';
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
}
