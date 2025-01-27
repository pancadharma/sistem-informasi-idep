<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use DateTimeInterface;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Meals extends Model
{
    use HasFactory, Auditable, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    protected $table = "tr_meals";

    protected $fillable = [
        'program_id',
        'meals_title',
        'meals_code',
        'meals_status',
        'meals_description',
        'progress',
        'to_complete',
        'challenges',
        'risk',
        'mitigation',
        'action_plan',
        'action_plan_status',
        'action_plan_date'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function beneficiaries()
    {
        return $this->hasMany(Meals_Penerima_Manfaat::class);
    }
}
