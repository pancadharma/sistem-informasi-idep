<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Master_Jenis_Kelompok extends Model
{
    use Auditable, HasFactory, LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    protected $table ='master_jenis_kelompok';

    protected $fillable = [
        'nama',
        'aktif',
        'created_at',
        'updated_at',
    ];


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    // - make migration pivot tabel for trmealspenerimamanfaat_kelompok_marjinal

    // public function penerima_manfaat() {
    //     // return $this->hasMany(Meals_Penerima_Manfaat::class, 'penerima_manfaat', );
    // }

}