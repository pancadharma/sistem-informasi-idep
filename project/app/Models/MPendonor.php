<?php
// app/Models/MPendonor.php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Yajra\DataTables\DataTables;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MPendonor extends Model
{
    use Auditable, HasFactory, LogsActivity;
    protected $table = 'mpendonor';

    protected $fillable = [
        'mpendonorkategori_id',
        'nama',
        'pic',
        'email',
        'phone',
        'aktif',
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
            ->logOnly(['*']);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function scopeActive($query)
    {
        return $query->where('aktif', 1);
    }

    public function getNamaAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function mpendonnorkategori()
    {
        return $this->belongsTo(Kategori_Pendonor::class, 'mpendonorkategori_id');
    }

    // Perbaikan relationship - gunakan belongsToMany dengan pivot
    public function programs()
    {
        return $this->belongsToMany(Program::class, 'trprogrampendonor', 'pendonor_id', 'program_id')
            ->withPivot('nilaidonasi')
            ->withTimestamps();
    }

    // Relationship ke trprogrampendonor untuk mendapatkan detail donasi
    public function donations()
    {
        return $this->hasMany(Program_Pendonor::class, 'pendonor_id');
    }

    // Accessor untuk mendapatkan total donasi
    public function getDonationCountAttribute()
    {
        return $this->donations()->count();
    }

    // Accessor untuk mendapatkan total nilai donasi
    public function getTotalDonationValueAttribute()
    {
        return $this->donations()->sum('nilaidonasi');
    }

    // Scope untuk mendapatkan pendonor dengan jumlah donasi
    public function scopeWithDonationCount($query)
    {
        return $query->withCount('donations as donation_count')
            ->withSum('donations as total_donation_value', 'nilaidonasi');
    }
}