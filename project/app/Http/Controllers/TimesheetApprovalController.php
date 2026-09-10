<?php

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TimesheetApprovalAssignment;
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

        abort_unless(
            $user->can('approve-timesheet') || $user->can('admin_timesheet'),
            403,
            'Anda tidak memiliki hak approval timesheet'
        );

        $timesheets = Timesheet::query()
            ->where('status', 'submitted')
            ->where('user_id', '!=', $user->id)
            ->when(!$user->can('admin_timesheet'), function ($query) use ($user) {
                $query->whereHas('user.timesheetApprovalAssignments', function ($itemQuery) use ($user) {
                    $itemQuery->where('approver_id', $user->id);
                });
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
        // APPROVER / ADMIN → BEBAS
        // =============================
        if ($user->can('admin_timesheet') || $user->can('approve-timesheet') || $user->can('history-timesheet')) {
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
    
    
    /**
     * APPROVE (AJAX)
     */
public function approve(Request $request, Timesheet $timesheet)
{
    $this->authorizeApproval($timesheet);

    $approver = auth()->user();
    $timesheet->load('user');

    try {
        // Email dikirim terlebih dahulu.
        $timesheet->user->notify(
            new TimesheetApproved($timesheet, $approver->nama)
        );

        // Status hanya berubah setelah email berhasil.
        $timesheet->update([
            'status'        => 'approved',
            'approved_by'   => $approver->id,
            'approved_at'   => now(),
            'approval_note' => $request->input('note'),
        ]);

        return response()->json([
            'success'    => true,
            'email_sent' => true,
            'message'    => 'Timesheet berhasil di-approve.',
        ]);
    } catch (\Throwable $e) {
        Log::error('EMAIL APPROVED GAGAL', [
            'timesheet_id' => $timesheet->id,
            'msg'          => $e->getMessage(),
        ]);

        return response()->json([
            'success'    => false,
            'email_sent' => false,
            'message'     => 'Email gagal dikirim. Status timesheet tidak diubah.',
        ], 500);
    }
}

    /**
     * REJECT (AJAX)
     */
public function reject(Request $request, Timesheet $timesheet)
{
    $request->validate([
        'note' => 'required|string|min:5',
    ]);

    $this->authorizeApproval($timesheet);

    $approver = auth()->user();
    $note = $request->input('note');

    $timesheet->load('user');

    try {
        // Email dikirim terlebih dahulu.
        $timesheet->user->notify(
            new TimesheetRejected($timesheet, 'rejected', $note)
        );

        // Status hanya berubah setelah email berhasil.
        $timesheet->update([
            'status'        => 'rejected',
            'approved_by'   => $approver->id,
            'approved_at'   => now(),
            'approval_note' => $note,
        ]);

        return response()->json([
            'success'    => true,
            'email_sent' => true,
            'message'     => 'Timesheet berhasil ditolak.',
        ]);
    } catch (\Throwable $e) {
        Log::error('EMAIL REJECTED GAGAL', [
            'timesheet_id' => $timesheet->id,
            'msg'          => $e->getMessage(),
        ]);

        return response()->json([
            'success'    => false,
            'email_sent' => false,
            'message'     => 'Email gagal dikirim. Status timesheet tidak diubah.',
        ], 500);
    }
}

    /**
     * CEK OTORISASI APPROVAL
     */
    protected function authorizeApproval(Timesheet $timesheet)
    {
        $user = auth()->user();

        abort_if($timesheet->status !== 'submitted', 403);
        abort_if($timesheet->user_id === $user->id, 403);

        abort_unless(
            $user->can('approve-timesheet') || $user->can('admin_timesheet'),
            403,
            'Anda tidak memiliki hak approval timesheet'
        );

        if (!$user->can('admin_timesheet')) {
            $isAssignedApprover = TimesheetApprovalAssignment::query()
                ->where('user_id', $timesheet->user_id)
                ->where('approver_id', $user->id)
                ->exists();

            abort_unless(
                $isAssignedApprover,
                403,
                'Anda belum ditunjuk sebagai approver untuk timesheet ini'
            );
        }
    }

    /**
     * ===============================
     * 🔥 HISTORY DENGAN FILTER ROLE
     * ===============================
     */
    public function assignments()
    {
        abort_unless(auth()->user()->can('user_management_access'), 403, 'Anda tidak memiliki hak mengatur approval timesheet');

        $users = User::query()
            ->with('jabatan')
            ->orderBy('nama')
            ->get();

        $assignedApprovers = TimesheetApprovalAssignment::query()
            ->get()
            ->groupBy('user_id')
            ->map(fn ($items) => $items->pluck('approver_id')->toArray());

        return view('timesheet.approval.assignments', compact('users', 'assignedApprovers'));
    }

    public function saveAssignments(Request $request)
    {
        abort_unless(auth()->user()->can('user_management_access'), 403, 'Anda tidak memiliki hak mengatur approval timesheet');

        $userId = $request->input('user_id');

        abort_unless($userId, 422, 'User tidak valid.');

        $user = User::findOrFail($userId);

        $user->timesheetApprovalAssignments()->delete();

        $approverIds = array_values(
            array_unique(
                array_filter(
                    $request->input('approvers', []),
                    fn ($id) => $id != null && $id != $userId && $id !== ''
                )
            )
        );

        foreach ($approverIds as $approverId) {
            $user->timesheetApprovalAssignments()->create([
                'approver_id' => $approverId,
            ]);
        }

        return back()->with('success', 'Pengaturan approver untuk ' . $user->nama . ' berhasil disimpan.');
    }

    public function history(Request $request)
    {
        $user = auth()->user();

        $month = $request->month;
        $year  = $request->year;

        $query = Timesheet::query()
            ->whereIn('status', ['approved', 'rejected'])
            ->with(['user.jabatan']);

        // ===========================
        // 1. ADMIN / APPROVER → LIHAT SEMUA
        // ===========================
        if ($user->can('admin_timesheet') || $user->can('approve-timesheet') || $user->can('history-timesheet')) {

            // tanpa filter divisi

        }

        // ===========================
        // 2. STAFF → HANYA MILIK DIA
        // ===========================
        else {

            $query->where('user_id', $user->id);

        }

        // FILTER BULAN TAHUN
        $query->when($month, fn ($q) => $q->where('month', $month))
              ->when($year, fn ($q) => $q->where('year', $year));

        $timesheets = $query
            ->orderBy('approved_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('timesheet.approval.history', compact(
            'timesheets',
            'month',
            'year'
        ));
    }
}