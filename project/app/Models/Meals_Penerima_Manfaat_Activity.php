<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meals_Penerima_Manfaat_Activity extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'trmeals_penerima_manfaat_activity';
    protected $fillable = [
        'trmeals_penerima_manfaat_id',
        'programoutcomeoutputactivity_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function penerimaManfaat()
    {
        return $this->belongsTo(Meals_Penerima_Manfaat::class, 'trmeals_penerima_manfaat_id');
    }

    public function activity()
    {
        return $this->belongsTo(Program_Outcome_Output_Activity::class, 'programoutcomeoutputactivity_id');
    }
}
