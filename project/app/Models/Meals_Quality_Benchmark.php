<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meals_Quality_Benchmark extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'benchmark';

    protected $fillable = [
        'program_id',
        'jeniskegiatan_id',
        'kegiatan_id',
        'desa_id',
        'kecamatan_id',
        'kabupaten_id',
        'provinsi_id',
        'tanggalimplementasi',
        'handler',
        'usercompiler_id',
        'score',
        'catatanevaluasi',
        'area',
    ];

    protected $casts = [
        'tanggalimplementasi' => 'date',
        'score' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    // Relasi
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function jenisKegiatan()
    {
        return $this->belongsTo(Jenis_Kegiatan::class, 'jeniskegiatan_id','id');
    }

    public function outcomeActivity()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id', 'id');
    }

    public function desa()
    {
        return $this->belongsTo(Kelurahan::class, 'desa_id', 'id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id', 'id');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id', 'id');
    }

    public function compiler()
    {
        return $this->belongsTo(User::class, 'usercompiler_id', 'id');
    }
}
