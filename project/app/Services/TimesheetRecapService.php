<?php

namespace App\Services;

use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TimesheetRecapService
{
    private function formatMinutesToHours(int $minutes): string
    {
        if ($minutes <= 0) {
            return '0';
        }

        $h = floor($minutes / 60);
        $m = $minutes % 60;

        $parts = [];
        if ($h > 0) {
            $parts[] = $h . 'J';
        }
        if ($m > 0) {
            $parts[] = $m . 'M';
        }

        return empty($parts) ? '0' : implode(' ', $parts);
    }

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
    $totalMinutes = 0;

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

            $minutes = (int) $e->minutes;

            $matrix[$program][$day] =
                ($matrix[$program][$day] ?? 0) + $minutes;

            $matrix[$program]['total'] =
                ($matrix[$program]['total'] ?? 0) + $minutes;

            $grandDaily[$day] += $minutes;
            $totalMinutes       += $minutes;
        }
    }

    return [
        'user'              => $user,
        'month'             => $month,
        'year'              => $year,
        'status'            => $timesheet->status ?? 'draft',
        'daysInMonth'       => $daysInMonth,
        'matrix'            => $this->formatMatrixValues($matrix),
        'grandDaily'        => array_map([$this, 'formatMinutesToHours'], $grandDaily),
        'grandTotal'        => $this->formatMinutesToDays($totalMinutes),
        'equivalent'        => $this->formatMinutesToDays($totalMinutes),
        'nonWorkingMarker'  => $nonWorkingMarker, // ✅ PENTING
    ];
}

    private function formatMinutesToDays(int $minutes): string
    {
        if ($minutes <= 0) {
            return '0';
        }

        $d = floor($minutes / 480);
        $sisa = $minutes % 480;
        $h = floor($sisa / 60);
        $m = $sisa % 60;

        $res = [];
        if ($d > 0) $res[] = $d . ' Hari';
        if ($h > 0) $res[] = $h . ' Jam';
        if ($m > 0) $res[] = $m . ' Mnt';

        return empty($res) ? '0' : implode(' ', $res);
    }

    private function formatMatrixValues(array $matrix): array
    {
        $formattedMatrix = [];
        foreach ($matrix as $program => $days) {
            foreach ($days as $day => $minutes) {
                if ($day === 'total') {
                    $formattedMatrix[$program][$day] = $this->formatMinutesToDays($minutes);
                } else {
                    $formattedMatrix[$program][$day] = $this->formatMinutesToHours($minutes);
                }
            }
        }

        uksort($formattedMatrix, function ($a, $b) {
            return strcasecmp($a, $b);
        });

        return $formattedMatrix;
    }
}