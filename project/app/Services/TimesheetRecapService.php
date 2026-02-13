<?php

namespace App\Services;

use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TimesheetRecapService
{
public function buildForUser(User $user, Request $request): array
{
    $month      = (int) $request->month;
    $year       = (int) $request->year;
    $programIds = $request->program_ids ?? [];

    $timesheet = Timesheet::with('entries')
        ->where('user_id', $user->id)
        ->where('month', $month)
        ->where('year', $year)
        ->first();

    $daysInMonth = Carbon::create($year, $month)->daysInMonth;

    $matrix     = [];
    $grandDaily = array_fill(1, $daysInMonth, 0);
    $totalHours = 0;

    // 👉 penanda hari non-kerja
    $nonWorkingMarker = [];

    if ($timesheet) {
        foreach ($timesheet->entries as $e) {

            $day = Carbon::parse($e->work_date)->day;

            // =============================
            // NON WORKING DAY
            // =============================
            if (in_array($e->day_status, ['libur', 'cuti', 'doc', 'sakit'])) {

                $marker = match ($e->day_status) {
                    'libur' => 'L',
                    'cuti'  => 'C',
                    'doc'   => 'D',
                    'sakit' => 'S',
                    default => ''
                };

                $nonWorkingMarker[$day] = $marker;
                continue;
            }

            // =============================
            // FILTER PROGRAM (STATIC + DB)
            // =============================
            if (!empty($programIds)) {

                $isAllowed = false;

                // 1. PROGRAM DATABASE
                if ($e->program_id && in_array($e->program_id, $programIds)) {
                    $isAllowed = true;
                }

                // 2. PROGRAM STATIC
                if ($e->program_static && in_array($e->program_static, $programIds)) {
                    $isAllowed = true;
                }

                if (!$isAllowed) {
                    continue;
                }
            }

            $program = $e->program?->nama
                ?? ucfirst(str_replace('_', ' ', $e->program_static))
                ?? 'Others';

            $hours = $e->minutes / 60;

            $matrix[$program][$day] =
                ($matrix[$program][$day] ?? 0) + $hours;

            $matrix[$program]['total'] =
                ($matrix[$program]['total'] ?? 0) + $hours;

            $grandDaily[$day] += $hours;
            $totalHours       += $hours;
        }
    }
    // ===== SORT PROGRAM ALFABET =====
    uksort($matrix, function ($a, $b) {
        return strcasecmp($a, $b);
    });

    return [
        'user'              => $user,
        'month'             => $month,
        'year'              => $year,
        'status'            => $timesheet->status ?? 'draft',
        'daysInMonth'       => $daysInMonth,
        'matrix'            => $matrix,
        'grandDaily'        => $grandDaily,
        'grandTotal'        => $totalHours,
        'equivalent'        => round($totalHours / 8, 2),
        'nonWorkingMarker'  => $nonWorkingMarker, // ✅ PENTING
    ];
}
}