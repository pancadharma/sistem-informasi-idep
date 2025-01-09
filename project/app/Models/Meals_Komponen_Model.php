<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use App\Models\KomponenModel;
use App\Models\TargetReinstra;


class Meals_Komponen_Model extends Model
{
    use HasFactory;

    protected $table = 'trmealskomponenmodel'; // Table name

    protected $fillable = [
        'program_id',
        'komponenmodel_id',
        'targetreinstra_id',
        'totaljumlah',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    // Relationships
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function komponenModel()
    {
        return $this->belongsTo(KomponenModel::class, 'komponenmodel_id', 'id');
    }

    public function targetReinstra()
    {
        return $this->belongsTo(TargetReinstra::class, 'targetreinstra_id', 'id');
    }
}
