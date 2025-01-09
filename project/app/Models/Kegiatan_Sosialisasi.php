<?php

namespace App\Models;

use DateTimeInterface;
use App\Models\Kegiatan;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan_Sosialisasi extends Model
{
    use HasFactory, Auditable;

    protected $table = 'trkegiatansosialisasi';

    protected $fillable = [
        'kegiatan_id',
        'sosialisasiyangterlibat',
        'sosialisasitemuan',
        'sosialisasitambahan',
        'sosialisasitambahan_ket',
        'sosialisasikendala',
        'sosialisasiisu',
        'sosialisasipembelajaran',
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
}
