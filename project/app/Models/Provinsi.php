<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Provinsi extends Model
{
    use Auditable, HasFactory, LogsActivity;
    protected $table = 'provinsi';


    protected $fillable = [
        'kode',
        'nama',
        'kota',
        'aktif',
        'id_negara',
        'latitude',
        'longitude',
        'coordinates',
        'path',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'negara_id');
    }
    public function negara()
    {
        return $this->belongsTo(Country::class, 'negara_id');
    }

    public function kabupaten_kota()
    {
        return $this->hasMany(Kabupaten::class, 'provinsi_id');
    }
    public function kab()
    {
        return $this->hasMany(Kabupaten::class, 'provinsi_id');
    }
    public function kabupaten()
    {
        return $this->hasMany(Kabupaten::class, 'provinsi_id');
    }

    public function dataAktif()
    {
        $province = Provinsi::where('aktif', 1)->get();
    }

    public function scopeWithActive(Builder $query)
    {
        return $query->where('aktif', 1);
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    // digunakan untuk relasi many to many dengan model program
    public function program()
    {
        return $this->belongsToMany(Program::class, 'trprogramlokasi', 'provinsi_id', 'program_id');
    }

    // digunakan untuk relasi many to many dengan model program
    public function penerimaManfaat2()
    {
        return $this->hasManyThrough(
            Meals_Penerima_Manfaat::class,
            Dusun::class,
            'desa_id', // Dusun.desa_id = Kelurahan.id
            'dusun_id', // Meals_Penerima_Manfaat.dusun_id = Dusun.id
            'id', // Provinsi.id
            'id' // Dusun.id
        )->join('kelurahan', 'dusun.desa_id', '=', 'kelurahan.id')
         ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
         ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
         ->whereColumn('kabupaten.provinsi_id', 'provinsi.id');
    }

    public function desa()
    {
        return $this->hasManyThrough(Kelurahan::class, Kabupaten::class, 'provinsi_id', 'kecamatan_id', 'id', 'id')
                    ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id');
    }

    public function penerimaManfaat()
    {
        return $this->hasManyThrough(Meals_Penerima_Manfaat::class, Kelurahan::class, 'kecamatan_id', 'dusun_id', 'id', 'id')
                    ->join('dusun', 'trmeals_penerima_manfaat.dusun_id', '=', 'dusun.id');
    }

}