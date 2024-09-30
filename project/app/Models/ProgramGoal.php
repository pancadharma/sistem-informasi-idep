<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramGoal extends Model
{

    protected $table = 'trprogramgoal';  // The table name

    protected $fillable = [
        'program_id',
        'deskripsi',
        'indikator',
        'target',
    ];

    // Define the relationship to the Program model (if applicable)
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}
