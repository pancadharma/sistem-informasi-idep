<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelompok_Marjinal extends Model
{
    use Auditable, HasFactory;
    protected $table = 'mkelompokmarjinal';

    protected $fillable = [
        'nama',
        'aktif',
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

    // ----------------------------------------------------------------
    // Define the relationship of master kelompok marjinal to prgram
    // instead of defining the relationship manually in each of one to many model / pivot models
    // only defining pivot table since it's manually defined in migration (non standard laravel with underscore like program_kelompok_marjinal
    // ----------------------------------------------------------------
    public function program() {
        //this relation will be called using eager loading in controller
        return $this->belongsToMany(Program::class, 'trprogramkelompokmarjinal', 'targetreinstra_id', 'program_id');

    }
}
