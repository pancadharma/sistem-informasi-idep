<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year',
        'month',
        'total_minutes',
        'status',
        'approved_by',
        'approved_at',
        'approval_note',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'year'        => 'integer',
        'month'       => 'integer',
        'total_minutes' => 'integer',
    ];

    /* =========================
     * RELATIONS
     * ========================= */

    // Owner timesheet
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Detail aktivitas
    public function entries()
    {
        return $this->hasMany(TimesheetEntry::class);
    }


    /* =========================
     * ACCESSORS (OPSIONAL TAPI BERGUNA)
     * ========================= */

    public function getTotalHoursAttribute()
    {
        return round($this->total_minutes / 60, 2);
    }

    public function getTotalDaysAttribute()
    {
        return round($this->total_minutes / 480, 2);
    }

    public function getPeriodLabelAttribute()
    {
        return sprintf('%02d-%d', $this->month, $this->year);
    }

    /* =========================
     * HELPERS STATUS
     * ========================= */

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isSubmitted()
    {
        return $this->status === 'submitted';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
    public function isEditable()
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

public function approver()
{
    return $this->belongsTo(User::class, 'approved_by');
}

public function isAutoApproved(): bool
{
    return $this->approval_note
        && str_contains($this->approval_note, 'Auto approved');
}

public function approvalLabel(): string
{
    if ($this->status === 'approved') {
        return $this->isAutoApproved()
            ? 'Auto Approved (Manager)'
            : 'Approved';
    }

    if ($this->status === 'rejected') {
        return 'Rejected';
    }

    return ucfirst($this->status);
}

}