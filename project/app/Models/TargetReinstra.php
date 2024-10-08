<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TargetReinstra extends Model
{
    use HasFactory, SoftDeletes, Auditable;

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

    public function program() {
        return $this->belongsToMany(Program::class, 'trprogramtargetreinstra', 'targetreinstra_id', 'program_id');
    }

}
