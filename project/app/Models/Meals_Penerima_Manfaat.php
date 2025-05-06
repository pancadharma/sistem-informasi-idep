<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meals_Penerima_Manfaat extends Model
{
    use HasFactory, Auditable, LogsActivity, SoftDeletes;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    protected $table = "trmeals_penerima_manfaat";

    protected $fillable = [
        'program_id',
        'user_id',
        'dusun_id',
        'nama',
        'no_telp',
        'jenis_kelamin',
        'rt',
        'rw',
        'umur',
        'keterangan',
        'is_head_family',
        'head_family_name',
        'is_non_activity',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'is_non_activity' => 'boolean',
        'is_head_family' => 'boolean',
    ];



    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dusun()
    {
        return $this->belongsTo(Dusun::class, 'dusun_id');
    }
    public function jenisKelompok()
    {
        return $this->belongsToMany(
            Master_Jenis_Kelompok::class,
            'trmeals_penerima_manfaat_jenis_kelompok',
            'trmeals_penerima_manfaat_id',
            'jenis_kelompok_id'
        );
    }

    // public function kelompokMarjinal()
    // {
    //     return $this->belongsToMany(
    //         Kelompok_Marjinal::class,
    //         'trmeals_penerima_manfaat_kelompok_marjinal',
    //         'trmeals_penerima_manfaat_id',
    //         'kelompok_marjinal_id'
    //     )->withTimestamps()->withTrashed(); // jika ingin ikut soft-deleted
    // }


    public function kelompokMarjinal()
    {
        return $this->belongsToMany(
            Kelompok_Marjinal::class, // Model yang berelasi
            'trmeals_penerima_manfaat_kelompok_marjinal', // nama table untuk menampung relasi many-to-many (pivot) trmeasls_penerima_manfaat dan (master) kelompok_marjinal
            'trmeals_penerima_manfaat_id', // Foreign key di tabel pivot untuk model ini
            'kelompok_marjinal_id' // Foreign key di tabel pivot untuk model yang berelasi
        );
    }

    public function kelompokMarjinalPivot()
    {
        return $this->hasMany(Meals_Penerima_Manfaat_Kelompok_Marjinal::class, 'trmeals_penerima_manfaat_id');
    }


    public function penerimaActivity()
    {
        return $this->belongsToMany(
            Program_Outcome_Output_Activity::class, // Model yang berelasi
            'trmeals_penerima_manfaat_activity', // nama table untuk menampung relasi many-to-many (pivot) trmeasls_penerima_manfaat dan (master) kelompok_marjinal
            'trmeals_penerima_manfaat_id', // Foreign key di tabel pivot untuk model ini
            'programoutcomeoutputactivity_id' // Foreign key di tabel pivot untuk model yang berelasi
        );
    }
}
