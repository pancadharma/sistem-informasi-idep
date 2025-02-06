<?php

namespace App\Models;

use DateTimeInterface;
use App\Models\Program;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program_Lokasi extends Model
{
    use HasFactory, HasRoles, Auditable, LogsActivity;

    protected $table = 'trprogramlokasi';

    protected $fillable = [
        'program_id',
        'provinsi_id',
        'created_at',
        'updated_at',
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

    public function programlokasi()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

}
