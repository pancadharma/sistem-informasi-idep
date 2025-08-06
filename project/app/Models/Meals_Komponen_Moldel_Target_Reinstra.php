<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meals_Komponen_Moldel_Target_Reinstra extends Model
{
    use SoftDeletes, HasFactory, Auditable, LogsActivity;

    protected $table = 'trmeals_komponen_model_targetreinstra';

    protected $fillable = [
        'komponenmodel_id',
        'targetreinstra_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
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

    public function komponenModel()
    {
        return $this->belongsTo(Meals_Komponen_Model::class, 'komponenmodel_id');
    }

    public function targetReinstra()
    {
        return $this->belongsTo(TargetReinstra::class, 'targetreinstra_id');
    }
}
