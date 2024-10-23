<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program_Outcome extends Model
{
    use HasFactory, Auditable, HasRoles;

    protected $table = 'trprogramoutcome';

    protected $fillable = [
        'program_id',
        'deskripsi',
        'indikator',
        'target',
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
}
