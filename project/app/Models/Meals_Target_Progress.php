<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Meals_Target_Progress_Detail as TargetProgressDetail;
use App\Models\Program;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Meals_Target_Progress extends Model
{
    use HasFactory;
    protected $casts = [
        'tanggal' => 'date',
    ];

    protected $table = "trmeals_target_progress";

    protected $fillable = [
        "program_id",
        "tanggal",
        "created_at",
        "updated_at",
    ];

    protected $dates = [
        "tanggal",
        'created_at',
        'updated_at',
    ];

    // Associations
    public function program(){
        return $this->belongsTo(Program::class, 'program_id');
    }
    public function details(){
        return $this->hasMany(TargetProgressDetail::class, 'id_meals_target_progress');
    }
    public function siblings()
    {
        return $this->hasMany(self::class, 'program_id', 'program_id');
    }

    // Scope
    public static function withDetails(){
        return self::with([
                'program:id,kode,nama',
                'details' => function ($query){
                    $query->with([
                        'targetable:id,deskripsi,indikator,target',
                    ])->select('*');
                },
            ])
            ->withCount('details');;
    }
    public static function scopedCollection(){
        $latestIds = self::selectRaw('MAX(id) as id')
            ->groupBy('program_id');

        return self::whereIn('id', $latestIds)
            ->withCount(['siblings as updated_count']);
        }
}
