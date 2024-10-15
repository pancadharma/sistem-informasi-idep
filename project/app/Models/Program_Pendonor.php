<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program_Pendonor extends Model
{
    use HasFactory, HasRoles, Auditable;

    protected $table = 'trprogrampendonor';

    protected $fillable = [
        'program_id',
        'pendonor_id',
        'nilaidonasi',
        'created_at',
        'updated_at',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    public function pendonor()
    {
        return $this->belongsTo(MPendonor::class);
    }
}
