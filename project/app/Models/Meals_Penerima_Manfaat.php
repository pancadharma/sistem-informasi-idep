<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use DateTimeInterface;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Meals_Penerima_Manfaat extends Model
{
    use HasFactory, Auditable, LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    protected $table = "tr_meals_penerima_manfaat";

    protected $fillable = [
        'meals_id',
        'name',
        'gender',
        'disability',
        'marginal_group',
        'address',
        'rt',
        'rw_banjar',
        'dusun',
        'desa',
        'phone_no',
        'group_type',
        'age',
        'age_group',
        'child_age',
        'youth_age',
        'adult_age',
        'elderly_age'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function meal()
    {
        return $this->belongsTo(Meals::class);
    }
    public function programoutcomeoutputactivity()
    {
        return $this->belongsTo(Program_Outcome_Output_Activity::class);
    }
}
