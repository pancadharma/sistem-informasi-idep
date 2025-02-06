<?php

namespace App\Models;

use DateTimeInterface;
use App\Models\Program;
use App\Traits\Auditable;
use App\Models\KomponenModel;
use App\Models\TargetReinstra;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meals_Komponen_Model extends Model
{
    use HasFactory, Auditable, LogsActivity;

    protected $table = 'trmealskomponenmodel'; // Table name

    protected $fillable = [
        'program_id',
        'komponenmodel_id',
        'targetreinstra_id',
        'totaljumlah',
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

    // Relationships
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function komponenModel()
    {
        return $this->belongsTo(KomponenModel::class, 'komponenmodel_id', 'id');
    }

    public function targetReinstra()
    {
        return $this->belongsTo(TargetReinstra::class, 'targetreinstra_id', 'id');
    }
}
