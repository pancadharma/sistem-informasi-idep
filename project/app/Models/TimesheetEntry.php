<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimesheetEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'timesheet_id',
        'work_date',
        'day_status',

        'location_detail',
        'work_location',

        'minutes',

        'donor_id',
        'donor_static',

        'program_id',
        'program_static',

        'activity',
    ];

    protected $casts = [
        'work_date' => 'date',
    ];

    /* =========================
     * RELATIONS
     * ========================= */

    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class);
    }

    public function donor()
    {
        return $this->belongsTo(MPendonor::class, 'donor_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    /* =========================
     * ACCESSORS
     * ========================= */

    public function getHoursAttribute()
    {
        return round($this->minutes / 60, 2);
    }

    public function getDonorLabelAttribute()
    {
        if ($this->donor_id) {
            return $this->donor ? $this->donor->name : null;
        }
        return $this->donor_static;
    }

    public function getProgramLabelAttribute()
    {
        if ($this->program_id) {
            return $this->program ? $this->program->name : null;
        }
        return $this->program_static;
    }

    /* =========================
     * HELPERS
     * ========================= */

    public function isWorkingDay()
    {
        return $this->day_status === 'bekerja';
    }
}