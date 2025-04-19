<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meals_Quality_Benchmark extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'meals_quality_benchmark';

    protected $fillable = [
        'program_id',
        'jeniskegiatan_id',
        'programoutcomeoutputactivity_id',
        'desa_id',
        'tanggalimplementasi',
        'userhandler_id',
        'usercompiler_id',
        'score',
        'catatanevaluasi',
        'area',
    ];

    protected $casts = [
        'tanggalimplementasi' => 'date',
        'score' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    // Relasi
    public function program()
    {
        return $this->belongsTo(Program::class, 'id_program');
    }

    public function jenisKegiatan()
    {
        return $this->belongsTo(Jenis_Kegiatan::class, 'id_jeniskegiatan');
    }

    public function outcomeActivity()
    {
        return $this->belongsTo(Program_Outcome_Output_Activity::class, 'id_programoutcomeoutputactivity');
    }

    public function desa()
    {
        return $this->belongsTo(Dusun::class, 'id_desa');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'id_userhandler');
    }

    public function compiler()
    {
        return $this->belongsTo(User::class, 'id_usercompiler');
    }
}
