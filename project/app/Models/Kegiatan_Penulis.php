<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
use DateTimeInterface;
use App\Models\Kegiatan;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan_Penulis extends Model
{
    use HasFactory, Auditable, LogsActivity;

    protected $table = 'trkegiatanpenulis';

    protected $fillable = [
        'kegiatan_id',
        'penulis_id',
        'peran_id',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }

    public function peran()
    {
        return $this->belongsTo(Peran::class, 'peran_id');
    }
}
