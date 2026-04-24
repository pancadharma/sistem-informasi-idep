<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mjabatan extends Model
{
    use HasFactory, Auditable, LogsActivity;
    protected $table = 'mjabatan';

    protected $fillable = [
        'nama',
        'aktif',
        'is_manager',
        'divisi_id',
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
    public function divisi()
    {
        return $this->belongsTo(MDivisi::class, 'divisi_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'jabatan_id');
    }

}