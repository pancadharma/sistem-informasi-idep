<?php

namespace App\Models;

use DateTimeInterface;
use App\Models\Program;
use App\Traits\Auditable;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program_Lokasi extends Model
{
    use HasFactory, HasRoles, Auditable;

    protected $table = 'trprogramlokasi';

    protected $fillable = [
        'program_id',
        'provinsi_id',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function programlokasi()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

}
