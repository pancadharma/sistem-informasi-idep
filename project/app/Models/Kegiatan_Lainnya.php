<?php

namespace App\Models;

use DateTimeInterface;
use App\Models\Kegiatan;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan_Lainnya extends Model
{
    use HasFactory, Auditable;

    protected $table = 'trkegiatanlainnya';

    protected $fillable = [
        'kegiatan_id',
        'lainnyamengapadilakukan',
        'lainnyadampak',
        'lainnyasumberpendanaan',
        'lainnyasumberpendanaan_ket',
        'lainnyayangterlibat',
        'lainnyarencana',
        'lainnyakendala',
        'lainnyaisu',
        'lainnyapembelajaran',
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
