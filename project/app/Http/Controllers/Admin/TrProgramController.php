<?php

namespace App\Http\Controllers\Admin;

use App\Models\Program;
use App\Models\KaitanSdg;
use Illuminate\Http\Request;
use App\Models\TargetReinstra;
use PhpParser\Builder\Function_;
use App\Models\Kelompok_Marjinal;
use App\Http\Controllers\Controller;
use App\Models\Program_Target_Reinstra;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

            return view('tr.program.edit');
        }
        abort(Response::HTTP_FORBIDDEN, 'Unauthorized Permission. Please ask your administrator to assign permissions to access and edit Program');
    }

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
