<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Program;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TimesheetRecapExport;
use App\Services\TimesheetRecapService;
use Illuminate\Routing\Controller;

class TimesheetExportController extends Controller
{
    public function index(
        Request $request,
        TimesheetRecapService $service
    ) {
        $previewData = [];

        if ($request->has('preview')) {

            $userIds = $request->input('user_ids', []);

            $users = empty($userIds)
                ? User::orderBy('nama')->get()
                : User::whereIn('id', $userIds)->orderBy('nama')->get();

            foreach ($users as $user) {
                $previewData[] = $service->buildForUser($user, $request);
            }
        }

        return view('timesheet.export.index', [
            'users'       => User::orderBy('nama')->get(),
            'programs' => Program::where('status', '!=', 'draft')
                ->orderBy('nama')
                ->get(),
            'previewData' => $previewData,
        ]);
    }

    public function excel(
        Request $request,
        TimesheetRecapService $service
    ) {
        $timesheet = Timesheet::where('user_id', $request->user_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->where('status', 'approved')
            ->firstOrFail();

        // 🔥 LOAD RELASI BIAR ADA DIVISI & JABATAN
        $user = User::with('jabatan.divisi')
            ->findOrFail($request->user_id);

        $data = $service->buildForUser($user, $request);

        $bulan = Carbon::create($request->year, $request->month)
                    ->translatedFormat('F');

        $nama = str_replace(' ', '_', $data['user']->nama);

        $filename = "{$request->year}_{$bulan}_{$nama}.xlsx";

        return Excel::download(
            new TimesheetRecapExport($data),
            $filename
        );
    }
}