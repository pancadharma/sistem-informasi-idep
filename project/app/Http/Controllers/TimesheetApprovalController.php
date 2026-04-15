<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TimesheetApproved;
use App\Notifications\TimesheetRejected;

class TimesheetApprovalController extends Controller
{
    /**
     * LIST TIMESHEET UNTUK APPROVAL (STATUS SUBMITTED)
     */
    public function index()
    {
        $user = auth()->user();

        $timesheets = Timesheet::query()
            ->where('status', 'submitted')
            ->where('user_id', '!=', $user->id)
            ->whereHas('user.jabatan', function ($q) use ($user) {
                $q->where('divisi_id', $user->jabatan->divisi_id);
            })
            ->with(['user.jabatan'])
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('timesheet.approval.index', compact('timesheets'));
    }

    /**
     * SECURITY VIEW HISTORY
     */
    protected function authorizeViewHistory(Timesheet $timesheet)
    {
        $user = auth()->user();

        abort_if(
            !in_array($timesheet->status, ['approved', 'rejected']),
            403,
            'Bukan data history'
        );

        // =============================
        // ADMIN → BEBAS
        // =============================
        if ($user->can('admin_timesheet')) {
            return;
        }

        // =============================
        // MANAGER → CEK DIVISI
        // =============================
        if (optional($user->jabatan)->is_manager) {

            abort_if(
                $timesheet->user->jabatan->divisi_id !== $user->jabatan->divisi_id,
                403,
                'Bukan divisi Anda'
            );

            return;
        }

        // =============================
        // STAFF → HANYA DATA SENDIRI
        // =============================
        abort_if(
            $timesheet->user_id !== $user->id,
            403,
            'Bukan data Anda'
        );
    }

    /**
     * DETAIL TIMESHEET
     */
    public function show(Timesheet $timesheet)
    {
        if ($timesheet->status === 'submitted') {
            $this->authorizeApproval($timesheet);
        } else {
            $this->authorizeViewHistory($timesheet);
        }

        $timesheet->load([
            'user.jabatan.divisi',
            'entries' => fn ($q) => $q->orderBy('work_date')
        ]);

        return view('timesheet.approval.show', compact('timesheet'));
    }

    // /**
    //  * APPROVE
    //  */
    // public function approve(Request $request, Timesheet $timesheet)
    // {
    //     $this->authorizeApproval($timesheet);

    //     $timesheet->update([
    //         'status'        => 'approved',
    //         'approved_by'   => auth()->id(),
    //         'approved_at'   => now(),
    //         'approval_note' => $request->note,
    //     ]);

    //     // 🔥 PAKSA LOAD USER
    //     $timesheet->load('user');

    //     try {
    //         $timesheet->user->notify(new TimesheetApproved($timesheet));

    //     } catch (\Throwable $e) {
    //         Log::error('EMAIL APPROVED GAGAL', [
    //             'msg' => $e->getMessage()
    //         ]);
    //     }

    //     return redirect()
    //         ->route('approval.index')
    //         ->with('success', 'Timesheet berhasil di-approve');
    // }

    // /**
    //  * REJECT
    //  */
    // public function reject(Request $request, Timesheet $timesheet)
    // {
    //     $request->validate([
    //         'note' => 'required|string|min:5'
    //     ]);

    //     $this->authorizeApproval($timesheet);

    //     $timesheet->update([
    //         'status'        => 'rejected',
    //         'approved_by'   => auth()->id(),
    //         'approved_at'   => now(),
    //         'approval_note' => $request->note,
    //     ]);

    //     // 🔥 WAJIB LOAD USER
    //     $timesheet->load('user');

    //     try {
    //         $timesheet->user->notify(new TimesheetRejected($timesheet));

    //     } catch (\Throwable $e) {
    //         \Log::error('EMAIL REJECTED GAGAL', [
    //             'msg' => $e->getMessage()
    //         ]);
    //     }

    //     return redirect()
    //         ->route('approval.index')
    //         ->with('error', 'Timesheet ditolak');
    // }
    
    
    /**
     * APPROVE (AJAX)
     */
    public function approve(Request $request, Timesheet $timesheet)
    {
        $this->authorizeApproval($timesheet);

        $timesheet->update([
            'status'        => 'approved',
            'approved_by'   => auth()->id(),
            'approved_at'   => now(),
            'approval_note' => null, // Jika approve biasanya tanpa note
        ]);

        $timesheet->load('user');
        $emailStatus = true;

        try {
            $timesheet->user->notify(new TimesheetApproved($timesheet));
        } catch (\Throwable $e) {
            $emailStatus = false;
            \Log::error('EMAIL APPROVED GAGAL', ['msg' => $e->getMessage()]);
        }

        return response()->json([
            'success'    => true,
            'email_sent' => $emailStatus,
            'message'    => $emailStatus 
                            ? 'Timesheet berhasil di-approve.' 
                            : 'Timesheet di-approve, tapi gagal kirim email ke staff.'
        ]);
    }

    /**
     * REJECT (AJAX)
     */
    public function reject(Request $request, Timesheet $timesheet)
    {
        $request->validate([
            'note' => 'required|string|min:5'
        ]);

        $this->authorizeApproval($timesheet);

        $timesheet->update([
            'status'        => 'rejected',
            'approved_by'   => auth()->id(),
            'approved_at'   => now(),
            'approval_note' => $request->note,
        ]);

        $timesheet->load('user');
        $emailStatus = true;

        try {
            $timesheet->user->notify(new TimesheetRejected($timesheet));
        } catch (\Throwable $e) {
            $emailStatus = false;
            \Log::error('EMAIL REJECTED GAGAL', ['msg' => $e->getMessage()]);
        }

        return response()->json([
            'success'    => true,
            'email_sent' => $emailStatus,
            'message'    => $emailStatus 
                            ? 'Timesheet berhasil ditolak.' 
                            : 'Timesheet ditolak, tapi gagal kirim email ke staff.'
        ]);
    }

    /**
     * CEK OTORISASI APPROVAL
     */
    protected function authorizeApproval(Timesheet $timesheet)
    {
        $user = auth()->user();

        abort_if($timesheet->status !== 'submitted', 403);
        abort_if($timesheet->user_id === $user->id, 403);

        abort_if(
            !$user->jabatan || !$user->jabatan->is_manager,
            403,
            'Anda bukan manager'
        );

        abort_if(
            $timesheet->user->jabatan->divisi_id !== $user->jabatan->divisi_id,
            403,
            'Bukan bawahan Anda'
        );
    }

    /**
     * ===============================
     * 🔥 HISTORY DENGAN FILTER ROLE
     * ===============================
     */
    public function history(Request $request)
    {
        $user = auth()->user();

        $month = $request->month;
        $year  = $request->year;

        $query = Timesheet::query()
            ->whereIn('status', ['approved', 'rejected'])
            ->with(['user.jabatan']);

        // ===========================
        // 1. ADMIN SISTEM → LIHAT SEMUA
        // ===========================
        if ($user->can('admin_timesheet')) {

            // tanpa filter divisi

        }

        // ===========================
        // 2. MANAGER → HANYA DIVISI DIA
        // ===========================
        elseif (optional($user->jabatan)->is_manager) {

            $query->whereHas('user.jabatan', function ($q) use ($user) {
                $q->where('divisi_id', $user->jabatan->divisi_id);
            });

        }

        // ===========================
        // 3. STAFF → HANYA MILIK DIA
        // ===========================
        else {

            $query->where('user_id', $user->id);

        }

        // FILTER BULAN TAHUN
        $query->when($month, fn ($q) => $q->where('month', $month))
              ->when($year, fn ($q) => $q->where('year', $year));

        $timesheets = $query
            ->orderBy('approved_at', 'desc')
            ->get();

        return view('timesheet.approval.history', compact(
            'timesheets',
            'month',
            'year'
        ));
    }
}