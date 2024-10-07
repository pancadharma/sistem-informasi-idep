<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program_Report_Schedule extends Model
{
    use HasFactory, HasRoles, Auditable;

    protected $table = 'trprogramreportschedule';

    protected $fillable = [
        'program_id',
        'tanggal',
        'keterangan',
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
