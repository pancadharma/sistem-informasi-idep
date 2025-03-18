<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Model
{
    use HasFactory, Auditable, SoftDeletes, HasRoles, LogsActivity;
    protected $table = 'mpartner';

    protected $fillable = [
        'nama',
        'keterangan',
        'aktif',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'trkegiatan_mitra', 'mitra_id', 'kegiatan_id');
    }
    public function trkegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'trkegiatan_mitra', 'mitra_id', 'kegiatan_id');


    }
}