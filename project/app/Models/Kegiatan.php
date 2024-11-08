<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan extends Model
{
    use HasFactory, Auditable, HasRoles;

    protected $table = 'trkegiatan';

    protected $dates = [
        'created_at',
        'updated_at',
        'tanggalmulai',
        'tanggalselesai'
    ];

    protected $fillable = [
        'programoutcomeoutputactivity_id',
        'kode',
        'nama',
        'tanggalmulai',
        'tanggalselesai',
        'dusun_id',
        'long',
        'lat',
        'kategorilokasikegiatan_id',
        'tempat',
        'deskripsi',
        'tujuan',
        'yangterlibat',
        'pelatih',
        'informasilain',
        'luaslahan',
        'jenisbantuan_id',
        'satuan_id',
        'tindaklanjut',
        'tantangan',
        'rekomendasi',
        'user_id',
        'status',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
}
