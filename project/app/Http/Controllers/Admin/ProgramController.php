<?php

namespace App\Http\Controllers\Admin;

use App\Models\Program;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class ProgramController extends Controller
{
    public function index()
    {
        // Get all trprogram records
        $programs = Program::all();
        return view('tr.program.index', compact('programs')); // Assuming a view exists at resources/views/trprogram/index.blade.php
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
}
