<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program_Target_Reinstra extends Model
{
    use Auditable, HasFactory;
    protected $table = 'trprogramtargetreinstra';
    
    protected $fillable = [
        'program_id',
        'targetreinstra_id',
        'created_at',
        'updated_at',
    ];

    protected $date = [
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function targetreinstra()
    {
        return $this->belongsTo(TargetReinstra::class, 'targetreinstra_id');
    }
}
