<?php

namespace App\Models;

use DateTimeInterface;
use App\Models\Kegiatan;
use App\Models\Kelurahan;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use App\Models\Meals_Penerima_Manfaat as penerimaManfaat;
use App\Models\Dusun;
use App\Models\Dusun as Banjar;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan_Lokasi extends Model
{
    use Auditable, HasFactory, LogsActivity;

    protected $table = 'trkegiatan_lokasi';

    protected $fillable = [
        'kegiatan_id',
        'desa_id',
        'lokasi',
        'long',
        'lat',
        'created_at',
        'updated_at',
    ];

    protected $date = [
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

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
    public function trkegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
    public function kegiatans()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function desa()
    {
        return $this->belongsTo(Kelurahan::class, 'desa_id');
    }

    // Kegiatan_Lokasi.php
    public function penerimaManfaat()
    {
        return $this->hasManyThrough(
            penerimaManfaat::class,
            Dusun::class,
            'desa_id',          // Foreign key di Dusun yang merujuk ke Kelurahan (desa)
            'dusun_id',         // Foreign key di Penerima Manfaat
            'desa_id',          // Foreign key di Kegiatan_Lokasi
            'id'                // Primary key di Dusun
        );
    }

}
