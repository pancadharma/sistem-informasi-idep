<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peran extends Model
{
    use HasFactory, Auditable, LogsActivity;
    protected $table = 'mperan';

    protected $fillable = [
        'nama',
        'aktif',
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

    public function program()
    {
        return $this->belongsToMany(Program::class, 'trprogramuser', 'peran_id', 'program_id');
    }
    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan_Penulis::class, 'trkegiatanpenulis', 'peran_id', 'kegiatan_id');
    }

    //confuse wich model I should user above or this bellow
    public function kegiatan_peran()
    {
        return $this->belongsToMany(Kegiatan::class, 'trkegiatanpenulis', 'peran_id', 'kegiatan_id');
    }
}