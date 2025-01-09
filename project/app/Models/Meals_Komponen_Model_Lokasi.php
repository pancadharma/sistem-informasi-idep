<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Dusun;
use App\Models\Satuan;
use App\Models\Meals_Komponen_Model;

class Meals_Komponen_Model_Lokasi extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'trmealskomponenmodellokasi';

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