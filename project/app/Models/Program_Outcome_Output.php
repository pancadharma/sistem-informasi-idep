<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use App\Models\Program_Outcome;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program_Outcome_Output extends Model
{
    use HasFactory, Auditable, HasRoles;

    protected $table = 'trprogramoutcomeoutput';

    protected $fillable = [
        'programoutcome_id',
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

    public function program_outcome()
    {
        return $this->belongsTo(Program_Outcome::class, 'programoutcome_id', 'id');
    }

    public function activities()
    {
        return $this->hasMany(Program_Outcome_Output_Activity::class, 'programoutcomeoutput_id');
    }

}
