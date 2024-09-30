<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramObjektif extends Model
{
    protected $table = 'trprogramobjektif';

    protected $fillable = [
        'program_id',
        'deskripsi',
        'indikator',
        'target',
    ];

    // Define the relationship to the 'Program' model (assuming the related table is 'programs')
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
    
}
