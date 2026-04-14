<?php

namespace App\Services\Leave;

use App\Models\LeaveRequest;
use App\Models\LeaveApprovalStep;
use Illuminate\Support\Collection;

class ApprovalFlowFactory
{
    public static function build(LeaveRequest $leave): Collection
    {
        return match ($leave->requester_role) {
            'staff'       => self::staffFlow($leave),
            'line_manager'=> self::managerFlow($leave),
            'director'    => self::directorFlow($leave),
            default       => collect(),
        };
    }

    /* ================= STAFF ================= */

    protected static function staffFlow(LeaveRequest $leave): Collection
    {
        return collect([
            [
                'role' => 'line_manager',
                'users'=> self::resolveLineManager($leave),
            ],
            [
                'role' => 'hr',
                'users'=> self::resolveHR(),
            ],
        ]);
    }

    /* ================= LINE MANAGER ================= */

    protected static function managerFlow(LeaveRequest $leave): Collection
    {
        return collect([
            [
                'role' => 'director',
                'users'=> self::resolveDirector(),
            ],
            [
                'role' => 'hr',
                'users'=> self::resolveHR(),
            ],
        ]);
    }

    /* ================= DIRECTOR ================= */

    protected static function directorFlow(LeaveRequest $leave): Collection
    {
        // auto approve
        $leave->update([
            'status'         => 'approved',
            'auto_approved'  => true,
            'submitted_at'   => now(),
        ]);

        return collect([
            [
                'role' => 'line_manager',
                'users'=> self::resolveLineManager($leave),
                'fyi'  => true,
            ],
            [
                'role' => 'hr',
                'users'=> self::resolveHR(),
                'fyi'  => true,
            ],
        ]);
    }

    /* ================= RESOLVER ================= */

    protected static function resolveLineManager(LeaveRequest $leave): array
    {
        // sementara hardcode
        return [2]; // user_id manager
    }

    protected static function resolveDirector(): array
    {
        return [3];
    }

    protected static function resolveHR(): array
    {
        return [4];
    }
}