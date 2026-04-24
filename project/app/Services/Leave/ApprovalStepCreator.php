<?php

namespace App\Services\Leave;

use App\Models\LeaveRequest;
use App\Models\LeaveApprovalStep;

class ApprovalStepCreator
{
    public static function create(LeaveRequest $leave): void
    {
        $flows = ApprovalFlowFactory::build($leave);

        $level = 1;

        foreach ($flows as $flow) {
            foreach ($flow['users'] as $userId) {

                LeaveApprovalStep::create([
                    'leave_request_id' => $leave->id,
                    'level'            => $level,
                    'approver_id'      => $userId,
                    'approver_role'    => $flow['role'],
                    'status'           => $flow['fyi'] ?? false
                        ? 'skipped'
                        : 'pending',
                ]);
            }
            $level++;
        }

        // set status awal kalau tidak auto approve
        if (! $leave->auto_approved) {
            $leave->update([
                'status' => 'waiting_' . $flows->first()['role'],
            ]);
        }
    }
}