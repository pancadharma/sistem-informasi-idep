<?php

namespace App\Models;

use DateTimeInterface;
use App\Models\Program;
use App\Models\KaitanSdg;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program_Kaitan_SDG extends Model
{
    use Auditable, HasFactory;
    public $table = "trprogramkaitansdg";
    protected $fillable = [
        'program_id', 
        'kaitansdg_id', 
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

    public function programkaitansdg() {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function kaitansdg() {
        return $this->belongsTo(KaitanSdg::class, 'kaitansdg_id');
    }
}
