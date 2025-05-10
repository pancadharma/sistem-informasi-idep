<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelurahan extends Model
{
    use Auditable, HasFactory, LogsActivity;
    public $table = "kelurahan";
    protected $fillable = [
        'id','kode', 'nama', 'aktif', 'kecamatan_id', 'created_at','updated_at'
    ];
    protected $casts = [
        'updated_at',
        'created_at'
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
    public function kecamatan() {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function dusun()
    {
        return $this->hasMany(Dusun::class, 'desa_id');
    }
    public function kec()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function kec_desa()
    {
        return $this->hasMany(Kelurahan::class, 'kecamatan_id');
    }
    public function dusun_beneficiary()
    {
        return $this->hasMany(Dusun::class, 'desa_id');
    }
    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'trkegiatan_lokasi', 'desa_id', 'kegiatan_id')
        ->withPivot('program_id');
    }
}
