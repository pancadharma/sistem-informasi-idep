<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TargetReinstra extends Model
{
    use HasFactory, SoftDeletes, Auditable, LogsActivity;

    protected $table = 'mtargetreinstra';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'nama',
        'aktif',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    public function program() {
        return $this->belongsToMany(Program::class, 'trprogramtargetreinstra', 'targetreinstra_id', 'program_id');
    }

    public function komponen_models()
    {
        return $this->belongsToMany(Meals_Komponen_Model::class, 'trmeals_komponen_model_targetreinstra', 'targetreinstra_id', 'trmeals_komponen_model_id');
    }

}
