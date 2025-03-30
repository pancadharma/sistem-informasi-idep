<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Master_Jenis_Kelompok extends Model
{
    use Auditable, HasFactory, LogsActivity, SoftDeletes;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    protected $table = 'master_jenis_kelompok';

    protected $fillable = [
        'nama',
        'aktif',
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function penerimaManfaat()
    {
        //this relation will be called using eager loading in controller
        return $this->belongsToMany(Meals_Penerima_Manfaat::class, 'trmeals_penerima_manfaat_jenis_kelompok', 'jenis_kelompok_id', 'trmeals_penerima_manfaat_id');
    }
}
