<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use App\Models\Program_Outcome;
use Spatie\Activitylog\LogOptions;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Program_Outcome_Output extends Model
{
    use HasFactory, Auditable, HasRoles, LogsActivity;

    protected $table = 'trprogramoutcomeoutput';

    protected $fillable = [
        'programoutcome_id',
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

    public function program_outcome()
    {
        return $this->belongsTo(Program_Outcome::class, 'programoutcome_id', 'id');
    }

    public function activities()
    {
        return $this->hasMany(Program_Outcome_Output_Activity::class, 'programoutcomeoutput_id');
    }

}
