<?php

namespace App\Http\Controllers\Admin;

use App\Models\Program;
use App\Models\KaitanSdg;
use Illuminate\Http\Request;
use App\Models\TargetReinstra;
use Illuminate\Validation\ValidationException;
use PhpParser\Builder\Function_;
use App\Models\Kelompok_Marjinal;
use App\Http\Controllers\Controller;
use App\Models\Program_Target_Reinstra;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreProgramRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TrProgramController extends Controller
{
    use MediaUploadingTrait;
    public function index(){

        return view('tr.program.index');

    }
    public function create(){

        if (auth()->user()->id == 1 || auth()->user()->can('program_create')) {
            $targetreinstra = TargetReinstra::pluck('id', 'nama');
            return view('tr.program.create', compact('targetreinstra'));
        }
        abort(Response::HTTP_FORBIDDEN, 'Unauthorized Permission. Please ask your administrator to assign permissions to access and create a program');
    }
    public function show(){

        if (auth()->user()->id == 1 || auth()->user()->can('program_edit')) {

            return view('tr.program.edit')
            ;
        }
        abort(Response::HTTP_FORBIDDEN, 'Unauthorized Permission. Please ask your administrator to assign permissions to access and edit Program');
    }

    // STORE DATA
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
                    $programName = str_replace(' ', '_', $program->name);
                    $fileName = "{$programName}_{$fileCount}_{$timestamp}.{$extension}";

                    \Log::info('Uploading file: ' . $fileName);
                    $program->addMedia($file)
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

    public function KaitanSDG(Request $request){
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $sdg = KaitanSdg::where('nama', 'like', "%{$search}%")->get();
        return response()->json($sdg);
    }

    public function filePendukung(Request $request){

    }
}
