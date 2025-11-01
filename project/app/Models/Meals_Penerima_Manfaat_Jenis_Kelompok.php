<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meals_Penerima_Manfaat_Jenis_Kelompok extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'trmeals_penerima_manfaat_jenis_kelompok';
    protected $fillable = [
        'trmeals_penerima_manfaat_id',
        'jenis_kelompok_id',
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
    public function penerima_manfaat()
    {
        // Foreign key di tabel INI, Owner key (PK) di tabel Meals_Penerima_Manfaat
        return $this->belongsTo(Meals_Penerima_Manfaat::class, 'trmeals_penerima_manfaat_id');
    }
    public function jenis_kelompok()
    {
        return $this->belongsTo(Master_Jenis_Kelompok::class, 'master_jenis_kelompok_id');
    }
}
