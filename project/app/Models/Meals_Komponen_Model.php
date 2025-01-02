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
        'id_program',
        'id_komponenmodel',
        'id_targetreinstra',
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
        return $this->belongsTo(Program::class, 'id_program', 'id');
    }

    public function komponenModel()
    {
        return $this->belongsTo(KomponenModel::class, 'id_komponenmodel', 'id');
    }

    public function targetReinstra()
    {
        return $this->belongsTo(TargetReinstra::class, 'id_targetreinstra', 'id');
    }
}
