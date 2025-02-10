<?php

namespace App\Models;

use DateTimeInterface;
use App\Models\Kegiatan;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan_Kunjungan extends Model
{
    use HasFactory, Auditable, LogsActivity;

    protected $table = 'trkegiatankunjungan';

    protected $fillable = [
        'kegiatan_id',
        'kunjunganlembaga',
        'kunjunganpeserta',
        'kunjunganyangdilakukan',
        'kunjunganhasil',
        'kunjunganpotensipendapatan',
        'kunjunganrencana',
        'kunjungankendala',
        'kunjunganisu',
        'kunjunganpembelajaran',
        'created_at',
        'updated_at',
    ];

    protected $date = [
        'created_at',
        'updated_at',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
}
