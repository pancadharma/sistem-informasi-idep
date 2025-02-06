<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program_Kelompok_Marjinal extends Model
{
    use Auditable, HasFactory, LogsActivity;
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function program() {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function kelompokmarjinal() {
        return $this->belongsTo(Kelompok_Marjinal::class, 'kelompokmarjinal_id');
    }
}
