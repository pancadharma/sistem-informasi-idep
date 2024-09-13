<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelurahan extends Model
{
    use Auditable, HasFactory;
    public $table = "kelurahan";
    protected $fillable = [
        'id','kode', 'nama', 'aktif', 'kecamatan_id', 'created_at','updated_at'
    ];  
    protected $casts = [
        'updated_at',
        'created_at'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function kec()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function kecamatan() {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function kec_desa()
    {
        return $this->hasMany(Kelurahan::class, 'kecamatan_id');
    }

    public function dusun()
    {
        return $this->hasMany(Dusun::class, 'desa_id');
    }
}
