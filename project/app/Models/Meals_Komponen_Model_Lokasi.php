<?php

namespace App\Models;

use App\Models\Dusun;
use App\Models\Satuan;
use DateTimeInterface;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use App\Models\Meals_Komponen_Model;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meals_Komponen_Model_Lokasi extends Model
{
    use HasFactory, Auditable, LogsActivity;

    // Define the table name
    protected $table = 'trmeals_komponen_model_lokasi';

    // Allow mass-assignment for these fields
    protected $fillable = [
        'mealskomponenmodel_id',
        'dusun_id',
        'desa_id',
        'kecamatan_id',
        'kabupaten_id',
        'provinsi_id',
        'long',
        'lat',
        'satuan_id',
        'jumlah',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    /**
     * Relationships (if applicable)
     */
    public function mealsKomponenModel()
    {
        return $this->belongsTo(Meals_Komponen_Model::class, 'mealskomponenmodel_id', 'id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id', 'id');
    }

    public function dusun()
    {
        return $this->belongsTo(Dusun::class, 'dusun_id', 'id');
    }

    public function desa()
    {
        return $this->belongsTo(Kelurahan::class, 'desa_id', 'id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id', 'id');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id', 'id');
    }
}