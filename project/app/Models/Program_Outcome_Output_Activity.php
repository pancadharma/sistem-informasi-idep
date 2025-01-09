<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program_Outcome_Output_Activity extends Model
{
    use HasFactory, Auditable, HasRoles;

    protected $table = 'trprogramoutcomeoutputactivity';

    protected $fillable = [
        'programoutcomeoutput_id',
        'deskripsi',
        'indikator',
        'target',
        'kode',
        'nama',
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

    public function program_outcome_output()
    {
        return $this->belongsTo(Program_Outcome_Output::class, 'programoutcomeoutput_id', 'id');
    }
}
