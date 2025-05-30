<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use App\Models\TargetReinstra;
use Carbon\Carbon;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan_Sektor extends Model
{
    use HasFactory, Auditable, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    protected $table = 'trkegiatan_sektor';

    protected $fillable = [
        'kegiatan_id',
        'sektor_id',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getTglMulaiAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
    public function sektor()
    {
        return $this->belongsTo(TargetReinstra::class, 'sektor_id');
    }
}
