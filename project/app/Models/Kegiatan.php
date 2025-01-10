<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Carbon\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Kegiatan extends Model implements HasMedia
{
    use Auditable, HasFactory, InteractsWithMedia, HasRoles;

    protected $table = 'trkegiatan';

    protected $dates = [
        'created_at',
        'updated_at',
        'tanggalmulai',
        'tanggalselesai'
    ];

    protected $fillable = [
        'programoutcomeoutputactivity_id',
        'fasepelaporan',
        'jeniskegiatan_id',
        'desa_id',
        'user_id',
        'tanggalmulai',
        'tanggalselesai',
        'status',
        'mitra_id',
        'deskripsilatarbelakang',
        'deskripsitujuan',
        'deskripsikeluaran',
        'deskripsiyangdikaji',
        'penerimamanfaatdewasaperempuan',
        'penerimamanfaatdewasalakilaki',
        'penerimamanfaatdewasatotal',
        'penerimamanfaatlansiaperempuan',
        'penerimamanfaatlansialakilaki',
        'penerimamanfaatlansiatotal',
        'penerimamanfaatremajaperempuan',
        'penerimamanfaatremajalakilaki',
        'penerimamanfaatremajatotal',
        'penerimamanfaatanakperempuan',
        'penerimamanfaatanaklakilaki',
        'penerimamanfaatanaktotal',
        'penerimamanfaatdisabilitasperempuan',
        'penerimamanfaatdisabilitaslakilaki',
        'penerimamanfaatdisabilitastotal',
        'penerimamanfaatnondisabilitasperempuan',
        'penerimamanfaatnondisabilitaslakilaki',
        'penerimamanfaatnondisabilitastotal',
        'penerimamanfaatmarjinalperempuan',
        'penerimamanfaatmarjinallakilaki',
        'penerimamanfaatmarjinaltotal',
        'penerimamanfaatperempuantotal',
        'penerimamanfaatlakilakitotal',
        'penerimamanfaattotal',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
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

    public function getImageAttribute()
    {
        $file = $this->getMedia('file_pendukung_kegiatan')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function getDurationInDays()
    {
        return Carbon::parse($this->tanggalmulai)
            ->diffInDays(Carbon::parse($this->tanggalselesai));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit(Fit::Crop, 240, desiredHeight: 240);
        $this->addMediaConversion('preview')->fit(Fit::Crop, 320, 320);
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dusun()
    {
        return $this->belongsTo(Dusun::class, 'dusun_id');
    }

    public function kategori_lokasi()
    {
        return $this->belongsTo(Kategori_Lokasi_Kegiatan::class, 'kategorilokasikegiatan_id');
    }

    public function jenis_bantuan()
    {
        return $this->belongsTo(Jenis_Bantuan::class, 'jenisbantuan_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function activity()
    {
        return $this->belongsTo(Program_Outcome_Output_Activity::class, 'programoutcomeoutputactivity_id');
    }

    public function jenis_kegiatan()
    {
        return $this->belongsTo(Jenis_Kegiatan::class, 'jenis_kegiatan_id');
    }

    public function lokasi()
    {
        return $this->hasMany(Kegiatan_Lokasi::class, 'kegiatan_id');
    }

    public function mitra()
    {
        return $this->belongsToMany(Partner::class, 'trkegiatan_mitra', 'kegiatan_id', 'mitra_id');
    }



    public const STATUS_SELECT = [
        'draft'    => 'Draft',
        'ongoing'  => 'Ongoing',
        'completed' => 'Completed',
        'cancelled ' => 'Cancelled',
    ];


    public static function getJenisKegiatan(): array
    {
        return [
            1  => __('cruds.kegiatan.basic.data_jenis_kegiatan.1'),
            2  => __('cruds.kegiatan.basic.data_jenis_kegiatan.2'),
            3  => __('cruds.kegiatan.basic.data_jenis_kegiatan.3'),
            4  => __('cruds.kegiatan.basic.data_jenis_kegiatan.4'),
            5  => __('cruds.kegiatan.basic.data_jenis_kegiatan.5'),
            6  => __('cruds.kegiatan.basic.data_jenis_kegiatan.6'),
            7  => __('cruds.kegiatan.basic.data_jenis_kegiatan.7'),
            8  => __('cruds.kegiatan.basic.data_jenis_kegiatan.8'),
            9  => __('cruds.kegiatan.basic.data_jenis_kegiatan.9'),
            10 => __('cruds.kegiatan.basic.data_jenis_kegiatan.10'),
            11 => __('cruds.kegiatan.basic.data_jenis_kegiatan.11'),
            12 => __('cruds.kegiatan.basic.data_jenis_kegiatan.12'),
            13 => __('cruds.kegiatan.basic.data_jenis_kegiatan.13'),
            14 => __('cruds.kegiatan.basic.data_jenis_kegiatan.14'),
            15 => __('cruds.kegiatan.basic.data_jenis_kegiatan.15'),
            16 => __('cruds.kegiatan.basic.data_jenis_kegiatan.16'),
            17 => __('cruds.kegiatan.basic.data_jenis_kegiatan.17'),
        ];
    }
}