<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Program extends Model implements HasMedia
{
    use Auditable, HasFactory, InteractsWithMedia, LogsActivity;

    protected $table = 'trprogram';

    protected $dates = [
        'created_at',
        'updated_at',
        'tanggalmulai',
        'tanggalselesai'
    ];

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function users()
    {
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
    public function kaitanSDG()
    {
        return $this->belongsToMany(KaitanSdg::class, 'trprogramkaitansdg', 'program_id', 'kaitansdg_id');
    }
    public function partner()
    {
        return $this->belongsToMany(Partner::class, 'trprogrampartner', 'program_id', 'partner_id');
    }
    // Program Lokasi Relation
    public function lokasi()
    {
        return $this->belongsToMany(Provinsi::class, 'trprogramlokasi', 'program_id', 'provinsi_id');
    }

    public function pendonor()
    {
        return $this->belongsToMany(MPendonor::class, 'trprogrampendonor', 'program_id', 'pendonor_id')
            ->withPivot('nilaidonasi');
    }
    public function outcome()
    {
        return $this->hasMany(Program_Outcome::class, 'program_id');
    }

    public function staff()
    {
        return $this->belongsToMany(User::class, 'trprogramuser', 'program_id', 'user_id')
            ->withPivot('peran_id');
    }

    public function peran()
    {
        return $this->belongsToMany(Peran::class, 'trprogramuser', 'program_id', 'peran_id');
    }


    public function objektif()
    {
        return $this->hasOne(ProgramObjektif::class, 'program_id');
    }
    public function goal()
    {
        return $this->hasOne(ProgramGoal::class, 'program_id');
    }
    
    public function jadwalreport()
    {
        return $this->hasMany(Program_Report_Schedule::class, 'program_id');
    }

    public function getImageAttribute()
    {
        $file = $this->getMedia('file_pendukung_program')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }


    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit(Fit::Crop, 240, desiredHeight: 240);
        $this->addMediaConversion('preview')->fit(Fit::Crop, 320, 320);
    }

    public const STATUS_SELECT = [
        'draft'    => 'Draft',
        'running'  => 'Running',
        'submit'   => 'Submit',
        'complete' => 'Complete',
    ];

    public function getTglMulaiAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setTglMulaiAttribute($value)
    {
        $this->attributes['tanggalmulai'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getTglSelesaiAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setTglSelesaiAttribute($value)
    {
        $this->attributes['tanggalselesai'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getTotalBeneficiaries()
    {
        return $this->ekspektasipenerimamanfaatwoman +
            $this->ekspektasipenerimamanfaatman +
            $this->ekspektasipenerimamanfaatgirl +
            $this->ekspektasipenerimamanfaatboy +
            $this->ekspektasipenerimamanfaattidaklangsung;
    }

    public function getDurationInDays()
    {
        return Carbon::parse($this->tanggalmulai)
            ->diffInDays(Carbon::parse($this->tanggalselesai));
    }
}
