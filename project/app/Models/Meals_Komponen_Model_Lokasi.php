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
        'id_mealskomponenmodel',
        'id_dusun',
        'id_desa',
        'id_kecamatan',
        'id_kabupaten',
        'id_provinsi',
        'long',
        'lat',
        'id_satuan',
        'jumlah',
    ];

    /**
     * Relationships (if applicable)
     */
    public function mealsKomponenModel()
    {
        return $this->belongsTo(Meals_Komponen_Model::class, 'id_mealskomponenmodel', 'id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan', 'id');
    }

    public function dusun()
    {
        return $this->belongsTo(Dusun::class, 'id_dusun', 'id');
    }

    public function desa()
    {
        return $this->belongsTo(Kelurahan::class, 'id_desa', 'id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id');
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'id_kabupaten', 'id');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi', 'id');
    }
}
