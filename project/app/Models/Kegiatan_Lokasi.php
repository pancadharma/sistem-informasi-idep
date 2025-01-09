<?php

namespace App\Models;

use DateTimeInterface;
use App\Models\Kegiatan;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan_Lokasi extends Model
{
    use Auditable, HasFactory;

    protected $table = 'trkegiatan_lokasi';

    protected $fillable = [
        'kegiatan_id',
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

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
    public function kegiatans()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }




    // $kegiatan = kegiatan::find(1);

    // if ($kegiatan) {
    //     foreach ($kegiatan->lokasi as $lokasi) {
    //         echo $lokasi->lokasi . ' (Long: ' . $lokasi->long . ', Lat: ' . $lokasi->lat . ')<br>';
    //     }
    // } else {
    //     echo 'Kegiatan not found';
    // }
}
