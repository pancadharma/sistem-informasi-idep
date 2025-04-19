<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Meals_Quality_Benchmark;
use Illuminate\Support\Facades\Gate;
use App\Models\Program;
use App\Models\Jenis_Kegiatan;
use App\Models\Program_Outcome_Output_Activity;
use App\Models\Dusun;
use App\Models\User;

class BenchmarkController extends Controller
{
    public function index()
    {
     
        return view('tr.benchmark.index');
    }

    public function create()
    {
        return view('tr.benchmark.create', [
            'programs' => Program::all(),
            'kegiatan' => Jenis_Kegiatan::all(),
            'outcomes' => Program_Outcome_Output_Activity::all(),
            'dusun' => Dusun::all(),
            'users' => User::all(),
        ]);
    }

    public function getBenchmark(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'id' => 'nullable|array|min:1',
            'id.*' => 'integer',
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);

        if (!is_array($ids) && $ids !== null) {
            $ids = [$ids];
        }

        $query = Meals_Quality_Benchmark::when(!empty($ids), function ($q) use ($ids) {
            return $q->whereIn('id', $ids);
        }, function ($q) use ($search) {
            return $q->where('program_name', 'like', "%{$search}%");
        });

        $data = $query->select('id', 'program_name')->paginate(20, ['*'], 'page', $page);

        return response()->json([
            'results' => $data->items(),
            'pagination' => [
                'more' => $data->hasMorePages(),
            ],
        ]);
    }
}
