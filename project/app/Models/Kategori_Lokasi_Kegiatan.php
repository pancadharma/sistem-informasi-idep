<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori_Lokasi_Kegiatan extends Model
{
    use HasFactory, Auditable;
    protected $table = 'mkategorilokasikegiatan';

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
}
