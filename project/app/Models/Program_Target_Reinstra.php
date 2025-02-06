<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program_Target_Reinstra extends Model
{
    use Auditable, HasFactory, LogsActivity;
    protected $table = 'trprogramtargetreinstra';
    
    protected $fillable = [
        'program_id',
        'targetreinstra_id',
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

    public function programtargetreinstra()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function targetreinstra()
    {
        return $this->belongsTo(TargetReinstra::class, 'targetreinstra_id');
    }
}
