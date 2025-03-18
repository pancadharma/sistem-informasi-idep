<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramGoal extends Model
{
    use HasFactory, LogsActivity, Auditable;
    protected $table = 'trprogramgoal';  // The table name

    protected $fillable = [
        'program_id',
        'deskripsi',
        'indikator',
        'target',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    // Define the relationship to the Program model (if applicable)
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}
