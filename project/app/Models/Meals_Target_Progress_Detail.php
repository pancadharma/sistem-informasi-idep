<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Meals_Target_Progress as TargetProgress;
use App\Enums\TargetProgressStatus as StatusOptions;
use App\Enums\TargetProgressRisk as RiskOptions;

class Meals_Target_Progress_Detail extends Model
{
    use HasFactory;

    protected $table = "trmeals_target_progress_detail";
    protected $fillable = [
        "id_meals_target_progress",
        "level",
        "tipe",
        "targetable_id",
        "targetable_type",
        "achievements",
        "progress",
        "persentase_complete",
        "status",
        "challenges",
        "mitigation",
        "risk",
        "notes",
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'status'    => StatusOptions::class,
        'risk'      => RiskOptions::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($detail) {
            if (is_null($detail->status)) {
                $detail->status = 'unset';
            }
        });
    }

    // Associations
    public function targetable()
    {
        return $this->morphTo();
    }
    public function targetProgress(){
        return $this->belongsTo(TargetProgress::class, 'id_meals_target_progress');
    }
}
