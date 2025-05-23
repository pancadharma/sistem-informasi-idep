<?php

namespace App\Models;

use App\Models\User;
use App\Models\Dusun;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Program_Outcome_Output_Activity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meals_PrePostTest extends Model
{
    use SoftDeletes, HasFactory, Auditable, LogsActivity;

    protected $table = 'trmealspreposttest';

    protected $fillable = [
        'programoutcomeoutputactivity_id',
        'user_id',
        'trainingname',
        'tanggalmulai',
        'tanggalselesai',
    ];

    protected $casts = [
        'tanggalmulai' => 'datetime',
        'tanggalselesai' => 'datetime',
        'filedbytraineepre' => 'boolean',
        'filedbytraineepost' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    // Relasi ke program activity
    public function programActivity()
    {
        return $this->belongsTo(Program_Outcome_Output_Activity::class, 'programoutcomeoutputactivity_id');
    }

    public function peserta()
    {
        return $this->hasMany(Meals_PrePostTestPeserta::class, 'preposttest_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
