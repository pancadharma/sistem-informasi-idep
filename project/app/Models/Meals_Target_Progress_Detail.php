<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TargetProgressStatus as StatusOptions;
use App\Enums\TargetProgressRisk as RiskOptions;

class Meals_Target_Progress_Detail extends Model
{
    use HasFactory;

    protected $table = "trmeals_target_progress_detail";
    protected $casts = [
        'status'    => StatusOptions::class,
        'risk'      => RiskOptions::class,
    ];
}
