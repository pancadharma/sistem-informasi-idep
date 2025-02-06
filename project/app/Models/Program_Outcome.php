<?php

namespace App\Models;

use DateTimeInterface;
use App\Models\Program;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use App\Models\Program_Outcome_Output;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program_Outcome extends Model
{
    use HasFactory, Auditable, HasRoles, LogsActivity;

    protected $table = 'trprogramoutcome';

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

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
    public function output()
    {
        return $this->hasMany(Program_Outcome_Output::class, 'programoutcome_id');
    }
}
