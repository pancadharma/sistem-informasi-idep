<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program_Kelompok_Marjinal extends Model
{
    use Auditable, HasFactory;
    public $table = "trprogramkelompokmarjinal";
    protected $fillable = [
        'program_id', 
        'kelompokmarjinal_id', 
        'created_at',
        'updated_at'
    ];  
    protected $casts = [
        'updated_at',
        'created_at'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function program() {
        return $this->belongsTo(Porgram::class, 'program_id');
    }

    public function kelompokmarjinal() {
        return $this->belongsTo(Kelompok_Marjinal::class, 'kelompokmarjinal_id');
    }
}
