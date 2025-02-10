<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramObjektif extends Model
{
    use HasFactory, Auditable, LogsActivity;
    protected $table = 'trprogramobjektif';

    protected $fillable = [
        'program_id',
        'deskripsi',
        'indikator',
        'target',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    // Define the relationship to the 'Program' model (assuming the related table is 'programs')
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}