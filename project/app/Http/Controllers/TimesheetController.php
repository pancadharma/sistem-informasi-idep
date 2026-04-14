<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MPendonor;
use App\Models\Program;
use App\Models\Timesheet;
use App\Models\TimesheetEntry;
use App\Models\User;
use App\Notifications\TimesheetSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TimesheetController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $month = (int) ($request->month ?? now()->month);
        $year  = (int) ($request->year  ?? now()->year);

        $timesheet = Timesheet::where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->with(['entries', 'approver'])
            ->first();

        // ===============================
        // PROGRAM → hanya running & submit
        // ===============================
        $programs = Program::active()
            ->orderBy('nama')
            ->get();

        // Donor default (untuk jaga2 kalau pilih static)
        $donors = MPendonor::active()
            ->orderBy('nama')
            ->get();

        // PROGRAM STATIC WAJIB TETAP ADA
        $programStatic = [
            'admin'     => 'Administratif',
            'bu'        => 'Bisnis Unit',
            'internal'  => 'Program Internal',
            'other'     => 'Others',
        ];

        // DONOR STATIC WAJIB TETAP ADA
        $donorStatic = [
            'general' => 'General',
            'other'   => 'Others',
        ];

        // ===============================
        // GENERATE HARIAN
        // ===============================
        $daysInMonth  = Carbon::create($year, $month)->daysInMonth;
        $days         = [];
        $totalMinutes = 0;

        $summary = [
            'kantor'   => 0,
            'lapangan' => 0,
            'rumah'    => 0,
            'other'    => 0,
        ];

        for ($d = 1; $d <= $daysInMonth; $d++) {

            $dateObj = Carbon::create($year, $month, $d);
            $date    = $dateObj->toDateString();

            $entries = $timesheet
                ? $timesheet->entries->filter(fn($e) =>
                    $e->work_date->toDateString() === $date
                )
                : collect();

            $byLocation = [
                'kantor'   => 0,
                'lapangan' => 0,
                'rumah'    => 0,
                'other'    => 0,
            ];

            foreach ($entries as $e) {

                $loc = $e->work_location ?? 'other';
                $jam = $e->minutes / 60;

                if (!isset($byLocation[$loc])) {
                    $loc = 'other';
                }

                $byLocation[$loc] += $jam;
                $summary[$loc]    += $jam;
            }

            $minutes = $entries->sum('minutes');
            $totalMinutes += $minutes;

            if ($entries->where('day_status', 'libur')->count() > 0) {
                $status = 'libur';

            } elseif ($entries->where('day_status', 'cuti')->count() > 0) {
                $status = 'cuti';

            } elseif ($entries->where('day_status', 'doc')->count() > 0) {
                $status = 'doc';

            } elseif ($entries->where('day_status', 'sakit')->count() > 0) {
                $status = 'sakit';

            } elseif ($minutes > 0) {
                $status = 'bekerja';

            } else {
                $status = $dateObj->isWeekend()
                    ? 'libur'
                    : 'kosong';
            }

            $days[] = [
                'date'       => $dateObj,
                'minutes'    => $minutes,
                'byLocation' => $byLocation,
                'status'     => $status,
            ];
        }

        return view('timesheet.index', compact(
            'month',
            'year',
            'days',
            'timesheet',
            'totalMinutes',
            'programs',
            'donors',
            'programStatic',
            'donorStatic',
            'summary'
        ));
    }

    /* =====================================================
     * GET DONOR BY PROGRAM (INTI LOGIC)
     * ===================================================*/
    public function getDonorByProgram($program)
    {
        // ==============================
        // PROGRAM STATIC
        // ==============================
        if (!is_numeric($program)) {

            return response()->json([
                'type'   => 'static',
                'donors' => [
                    ['id' => 'general', 'nama' => 'General'],
                    ['id' => 'other',   'nama' => 'Others'],
                ]
            ]);
        }

        // ==============================
        // PROGRAM DATABASE
        // ==============================
        $programModel = Program::active()->find($program);

        if (!$programModel) {
            return response()->json([
                'type'   => 'program',
                'donors' => []
            ]);
        }

        // 🔥 Donor berdasarkan relasi program
        $donors = $programModel->pendonor()
            ->where('mpendonor.aktif', 1)
            ->orderBy('nama')
            ->get()
            ->map(fn($d) => [
                'id'   => $d->id,
                'nama' => $d->nama,
            ]);

        return response()->json([
            'type'   => 'program',
            'donors' => $donors
        ]);
    }

    /* =========================
     * STORE DAY ENTRY METHOD
     * ========================= */
    public function storeDay(Request $request)
    {
        $request->validate([
            'work_date'  => 'required|date',
            'day_status' => 'required|in:kerja,libur,doc,cuti,sakit',
            'activities' => 'array',
        ]);

        $user = auth()->user();
        $date = Carbon::parse($request->work_date);

        DB::beginTransaction();

        try {

            // ===============================
            // TIMESHEET BULANAN
            // ===============================
            $timesheet = Timesheet::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'year'    => $date->year,
                    'month'   => $date->month,
                ],
                [
                    'status' => 'draft',
                ]
            );

            // ✅ CEK DULU BOLEH EDIT
            if (!in_array($timesheet->status, ['draft', 'rejected'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet sudah dikunci dan tidak dapat diubah',
                ], 403);
            }

            // ✅ BARU GENERATE WEEKEND
            $this->autoCreateWeekend($timesheet);

            // HAPUS DATA HARI INI
            $timesheet->entries()
                ->where('work_date', $date->toDateString())
                ->delete();

            // JIKA NON KERJA
            if (in_array($request->day_status, ['libur', 'doc', 'cuti', 'sakit'])) {

                TimesheetEntry::create([
                    'timesheet_id' => $timesheet->id,
                    'work_date'    => $date->toDateString(),
                    'day_status'   => $request->day_status,
                    'minutes'      => 0,
                    'activity'     => $request->note ?? null,
                ]);

            } else {

                // ===============================
                // BEKERJA → SIMPAN DETAIL
                // ===============================
                foreach ($request->activities ?? [] as $row) {

                    if (
                        empty($row['activity']) ||
                        empty($row['minutes']) ||
                        $row['minutes'] <= 0
                    ) {
                        continue;
                    }

                    TimesheetEntry::create([
                        'timesheet_id' => $timesheet->id,
                        'work_date'    => $date->toDateString(),
                        'day_status'   => 'bekerja',

                        'location_detail' => $row['location_detail'] ?? null,
                        'work_location'   => $row['work_location'] ?? null,

                        'minutes' => (int) ($row['minutes'] * 60),

                        // DONOR
                        'donor_id' => is_numeric($row['donor_id'] ?? null)
                            ? $row['donor_id']
                            : null,

                        'donor_static' => !is_numeric($row['donor_id'] ?? null)
                            ? $row['donor_id']
                            : null,

                        // PROGRAM
                        'program_id' => is_numeric($row['program_id'] ?? null)
                            ? $row['program_id']
                            : null,

                        'program_static' => !is_numeric($row['program_id'] ?? null)
                            ? $row['program_id']
                            : null,

                        'activity' => $row['activity'],
                    ]);
                }
            }

            // ===============================
            // UPDATE TOTAL BULANAN
            // ===============================
            $timesheet->update([
                'total_minutes' => $timesheet->entries()->sum('minutes'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data harian berhasil disimpan',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /* =========================
     * GET DAY ENTRY METHOD
     * ========================= */
    public function getDay(string $date)
    {
        $user = auth()->user();
        $dateObj = Carbon::parse($date);

        $timesheet = Timesheet::where('user_id', $user->id)
            ->where('year', $dateObj->year)
            ->where('month', $dateObj->month)
            ->with(['entries' => function ($q) use ($dateObj) {
                $q->where('work_date', $dateObj->toDateString());
            }])
            ->first();

        // 🔥 1. Kalau belum ada timesheet sama sekali
        if (!$timesheet) {

            // 👉 WEEKEND = LIBUR OTOMATIS
            if ($dateObj->isWeekend()) {
                return response()->json([
                    'day_status' => 'libur',
                    'entries'    => [],
                ]);
            }

            return response()->json([
                'day_status' => 'kosong',
                'entries'    => [],
            ]);
        }

        $entries = $timesheet->entries;

        // 🔥 2. Tentukan status berdasarkan day_status
        if ($entries->where('day_status', 'libur')->count()) {
            $dayStatus = 'libur';

        } elseif ($entries->where('day_status', 'cuti')->count()) {
            $dayStatus = 'cuti';

        } elseif ($entries->where('day_status', 'doc')->count()) {
            $dayStatus = 'doc';

        } elseif ($entries->where('day_status', 'sakit')->count()) {
            $dayStatus = 'sakit';

        } elseif ($entries->count()) {
            $dayStatus = 'kerja';

        } else {
            // 👉 kalau tidak ada entry tapi weekend
            $dayStatus = $dateObj->isWeekend() ? 'libur' : 'kosong';
        }

        $note = optional(
            $entries->whereIn('day_status', ['cuti','doc','sakit'])->first()
        )->activity;

        return response()->json([
            'day_status' => $dayStatus,
            'note'       => $note,
            'entries'    => $entries->map(function ($e) {
                return [
                    'location_detail' => $e->location_detail,
                    'work_location'   => $e->work_location,
                    'minutes'         => $e->minutes / 60,
                    'donor_id'        => $e->donor_id ?? $e->donor_static,
                    'program_id'      => $e->program_id ?? $e->program_static,
                    'activity'        => $e->activity,
                ];
            }),
        ]);
    }

    /* =========================
     * SUBMIT BULANAN TIMESHEET METHOD
     * ========================= */
    public function submit(Timesheet $timesheet)
    {
        abort_if($timesheet->user_id !== auth()->id(), 403);

        $isManager = optional(auth()->user()->jabatan)->is_manager;

        abort_if(
            !in_array($timesheet->status, ['draft', 'rejected']),
            403,
            'Timesheet tidak bisa disubmit pada status ini'
        );

        $totalMinutes = $timesheet->entries()->sum('minutes');

        if ($totalMinutes <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa submit. Timesheet masih kosong.'
            ], 422);
        }

        // ===========================
        // MANAGER → AUTO APPROVE
        // ===========================
        if ($isManager) {

            $timesheet->update([
                'total_minutes' => $totalMinutes,
                'status'        => 'approved',
                'approved_by'   => auth()->id(),
                'approved_at'   => now(),
                'approval_note' => 'Auto approved (Manager)',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Timesheet auto-approved sebagai Manager'
            ]);
        }

        // ===========================
        // STAFF → NORMAL SUBMIT
        // ===========================
        $timesheet->update([
            'total_minutes' => $totalMinutes,
            'status'        => 'submitted',

            'approved_by'   => null,
            'approved_at'   => null,
            'approval_note' => null,
        ]);
        
        // ===========================
        // 🔔 KIRIM NOTIF KE MANAGER
        // ===========================
        $manager = User::whereHas('jabatan', function($q) use ($timesheet) {
            $q->where('is_manager', 1)
            ->where('divisi_id', $timesheet->user->jabatan->divisi_id);
        })->first();

        if ($manager) {
            $manager->notify(new TimesheetSubmitted($timesheet));
        }

        return response()->json([
            'success' => true,
            'message' => 'Timesheet berhasil disubmit'
        ]);
    }

    private function autoCreateWeekend(Timesheet $timesheet)
    {
        $year  = $timesheet->year;
        $month = $timesheet->month;

        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        for ($d = 1; $d <= $daysInMonth; $d++) {

            $date = Carbon::create($year, $month, $d);

            if (!$date->isWeekend()) continue;

            $exists = $timesheet->entries()
                ->where('work_date', $date->toDateString())
                ->exists();

            if (!$exists) {
                TimesheetEntry::create([
                    'timesheet_id' => $timesheet->id,
                    'work_date'    => $date->toDateString(),
                    'day_status'   => 'libur',
                    'minutes'      => 0,
                ]);
            }
        }
    }
    
    public function changeStatus(Request $request)
    {
        abort_unless(
            auth()->user()->can('timesheet_ubah_status'),
            403
        );

        $request->validate([
            'timesheet_id' => 'required',
            'status' => 'required|in:draft,rejected',
            'note' => 'required|min:5'
        ]);

        $timesheet = Timesheet::findOrFail($request->timesheet_id);

        $timesheet->update([

            'status' => $request->status,

            'approval_note' =>
                "* " .
                $request->note,

            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $timesheet->load(['user', 'approver']); 

        try {
            // Kirim notifikasi menggunakan class yang sudah Anda buat
            $timesheet->user->notify(new \App\Notifications\TimesheetRejected($timesheet));
            
        } catch (\Throwable $e) {
            // Jika email gagal, catat di log agar sistem tidak berhenti
            \Log::error('Gagal kirim email ubah status Admin: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah'
        ]);
    }

}