<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use Auditable, HasFactory;
    protected $table = 'trprogram';
    protected $fillable = [
        'nama',
        'kode',
        'tanggalmulai',
        'tanggalselesai',
        'totalnilai',
        'ekspektasipenerimamanfaat',
        'ekspektasipenerimamanfaatwoman',
        'ekspektasipenerimamanfaatman',
        'ekspektasipenerimamanfaatgirl',
        'ekspektasipenerimamanfaatboy',
        'ekspektasipenerimamanfaattidaklangsung',
        'deskripsiprojek',
        'analisamasalah',
        'user_id',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $date = [
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function targetReinstra()
    {
        return $this->belongsToMany(TargetReinstra::class, 'trprogramtargetreinstra', 'program_id', 'targetreinstra_id');
    }
    public function kelompokMarjinal()
    {
        return $this->belongsToMany(Kelompok_Marjinal::class, 'trprogramkelompokmarjinal', 'program_id', 'kelompokmarjinal_id');
    }
}
