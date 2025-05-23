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

    protected $table = 'trmeals_komponen_model'; // Table name

    protected $fillable = [
        'program_id',
        'user_id',
        'komponenmodel_id',
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
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function komponenmodel()
    {
        return $this->belongsTo(KomponenModel::class, 'komponenmodel_id');
    }

    public function sektors()
    {
        return $this->belongsToMany(TargetReinstra::class, 'trmeals_komponen_model_targetreinstra', 'mealskomponenmodel_id', 'targetreinstra_id');
    }

    public function lokasi()
    {
        return $this->hasMany(Meals_Komponen_Model_Lokasi::class, 'mealskomponenmodel_id');
    }
}
