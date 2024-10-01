<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program_Partner extends Model
{
    use Auditable, HasFactory;
    public $table = "trprogramkelompokmarjinal";
    protected $fillable = [
        'program_id', 
        'partner_id', 
        'email',
        'phone',
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

    public function programpartner() {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function parter() {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
}
