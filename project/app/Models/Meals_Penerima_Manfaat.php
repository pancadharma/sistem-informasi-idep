<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use DateTimeInterface;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Meals_Penerima_Manfaat extends Model
{
    use HasFactory, Auditable, LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    protected $table = "tr_meals_penerima_manfaat";

    protected $fillable = [
        'program_id',
        'user_id',
        'dusun_id',
        // 'desa_id',
        'jenis_kelompok_id',
        'nama',
        'notelp',
        'jeniskelamin',
        'umur',
        'disabilitas',
        // id_kelompokmarjinal : int <<fk>> null
        'rw',
        'rt',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function program(){
        return $this->belongsTo(Program::class);
    }
    public function dusun(){
        return $this->belongsTo(Dusun::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meals::class);
    }
    public function programoutcomeoutputactivity()
    {
        return $this->belongsTo(Program_Outcome_Output_Activity::class);
    }
}