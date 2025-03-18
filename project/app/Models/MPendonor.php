<?php

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
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Mutator for nama to ensure proper formatting
     *
     * @param string $value
     * @return string
     */
    public function getNamaAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function mpendonnorkategori()
    {
        return $this->belongsTo(Kategori_Pendonor::class, 'mpendonorkategori_id');
    }

    public function program()
    {
        return $this->belongsToMany(Program::class, 'trprogrampendonor', 'pendonor_id', 'program_id')->withPivot('nilaidonasi');
    }
}