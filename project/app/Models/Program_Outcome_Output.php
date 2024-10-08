<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program_Outcome_Output extends Model
{
    use HasFactory, Auditable, HasRoles;

    protected $table = 'trprogramoutcomeoutput';

    protected $fillable = [
        'programoutcome_id',
        'deskrispsi',
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
        return $this->belongsTo(Program_Outcome::class);
    }

}
