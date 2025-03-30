<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meals_Penerima_Manfaat extends Model
{
    use HasFactory, Auditable, LogsActivity, SoftDeletes;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    protected $table = "trmeals_penerima_manfaat";

    protected $fillable = [
        'program_id',
        'user_id',
        'dusun_id',
        'nama',
        'no_telp',
        'jenis_kelamin',
        'rt',
        'rw',
        'umur',
        'keterangan',
        'is_non_activity',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
    public function dusun()
    {
        return $this->belongsTo(Dusun::class, 'dusun_id');
    }
    public function jeniskelompok()
    {
        return $this->belongsToMany(Jenis_Kelompok::class, 'trmeals_penerima_manfaat', 'jenis_kelompok_id', 'id');
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
