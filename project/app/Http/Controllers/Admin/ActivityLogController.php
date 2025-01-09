<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Ambil semua log aktivitas
        $logs = Activity::latest()->paginate(10);

        return view('logs.index', compact('logs'));
    }

    public function show($id)
    {
        // Ambil detail log berdasarkan ID
        $log = Activity::findOrFail($id);

        return view('logs.show', compact('log'));
    }
}
