<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class mSektor extends Model
{
    use HasFactory, Auditable, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    protected $table = 'msektor';
    protected $fillable = [
        'nama',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function getTglMulaiAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function kegiatan_sektor()
    {
        return $this->hasMany(Kegiatan_Sektor::class, 'sektor_id', 'id');
    }
    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'trkegiatan_sektor', 'sektor_id', 'kegiatan_id');
    }
}
