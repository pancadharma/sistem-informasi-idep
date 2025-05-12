<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program_Outcome_Output_Activity extends Model
{
    use HasFactory, Auditable, HasRoles, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function program_outcome_output()
    {
        return $this->belongsTo(Program_Outcome_Output::class, 'programoutcomeoutput_id', 'id');
    }

    /**
     * Relasi many to many antara Program Outcome Output Activity dengan Meals Penerima Manfaat Activity.
     * Tabel pivot: trmeals_penerima_manfaat_activity.
     * Foreign key di tabel pivot untuk model ini: programoutcomeoutputactivity_id.
     * Foreign key di tabel pivot untuk model yang berelasi: trmeals_penerima_manfaat_id.
     */
    public function penerimaManfaat()
    {
        return $this->belongsToMany(
            Meals_Penerima_Manfaat::class, // Model yang berelasi
            'trmeals_penerima_manfaat_activity', // Nama tabel pivot
            'programoutcomeoutputactivity_id', // Foreign key di tabel pivot untuk model ini
            'trmeals_penerima_manfaat_id' // Foreign key di tabel pivot untuk model yang berelasi
        );
    }

    public function preposttests()
    {
        return $this->hasMany(Meals_PrePostTest::class, 'programoutcomeoutputactivity_id');
    }
}
