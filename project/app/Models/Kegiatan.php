<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Image\Enums\Fit;

use App\Models\Jenis_Kegiatan;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\LogOptions;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\MediaCollections\Models\MediaCollection;




class Kegiatan extends Model implements HasMedia
{
    use InteractsWithMedia, Auditable, HasFactory, HasRoles, LogsActivity;

    protected $table = 'trkegiatan';

    protected $dates = [
        'created_at',
        'updated_at',
        'tanggalmulai',
        'tanggalselesai'
    ];

    protected $fillable = [
        'programoutcomeoutputactivity_id',
        'jeniskegiatan_id',
        'user_id',
        'fasepelaporan',
        'tanggalmulai',
        'tanggalselesai',
        'status',
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    public function getTglMulaiAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setPenulisAttribute($value)
    {
        // Set the penulis_id attribute to the ID of the user associated with the penulis input
        $this->attributes['penulis_id'] = $value;
    }

    public function getPenulisAttribute()
    {
        return isset($this->attributes['penulis_id']) ? User::find($this->attributes['penulis_id']) : null;
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
        $file = $this->getMedia('media_pendukung')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    // media files
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('dokumen_pendukung')
        ->useDisk('kegiatan_uploads');

        $this->addMediaCollection('media_pendukung')
        ->useDisk('kegiatan_uploads');
    }


    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit(Fit::Crop, 320, 320)->performOnCollections('media_pendukung');
        $this->addMediaConversion('preview')->fit(Fit::Crop, 600, 800)->performOnCollections('media_pendukung');
    }



    public function getDokumenPendukung()
    {
        return $this->getMedia('dokumen_pendukung');
    }

    public function getMediaPendukung()
    {
        return $this->getMedia('media_pendukung');
    }
    // end media files


    public function getDurationInDays()
    {
        return Carbon::parse($this->tanggalmulai)
            ->diffInDays(Carbon::parse($this->tanggalselesai));
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

    public function jenisKegiatan()
    {
        return $this->belongsTo(Jenis_Kegiatan::class, 'jeniskegiatan_id');
    }

    public function programOutcomeOutputActivity()
    {
        return $this->belongsTo(Program_Outcome_Output_Activity::class, 'programoutcomeoutputactivity_id');
    }

    public function lokasi()
    {
        return $this->hasMany(Kegiatan_Lokasi::class, 'kegiatan_id');
    }

    public function lokasi_kegiatan()
    {
        return $this->hasMany(Kegiatan_Lokasi::class, 'kegiatan_id');
    }

    public function penulis()
    {
        return $this->belongsToMany(User::class, 'trkegiatanpenulis', 'kegiatan_id', 'penulis_id')->withPivot('peran_id')->withTimestamps();
    }


    public const STATUS_SELECT = [
        'draft'    => 'Draft',
        'ongoing'  => 'Ongoing',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    public static function getJenisKegiatan(): array
    {
        return Jenis_Kegiatan::select('id', 'nama')->get()->mapWithKeys(function ($item) {
            return [$item->id => $item->nama];
        })->toArray();
    }

    // relation with other model in dynamic input kegiatan

    public function assessment()
    {
        return $this->hasOne(Kegiatan_Assessment::class, 'kegiatan_id');
    }

    public function kampanye(){
        return $this->hasOne(Kegiatan_Kampanye::class, 'kegiatan_id');
    }

    public function konsultasi(){
        return $this->hasMany(Kegiatan_Konsultasi::class, 'kegiatan_id');
    }

    public function kunjungan(){
        return $this->hasOne(Kegiatan_Kunjungan::class, 'kegiatan_id');
    }
    public function lainnya(){
        return $this->hasOne(Kegiatan_Lainnya::class, 'kegiatan_id');
    }

    public function mitra()
    {
        return $this->belongsToMany(Partner::class, 'trkegiatan_mitra', 'kegiatan_id', 'mitra_id');
    }

    public function monitoring(){
        return $this->hasOne(Kegiatan_Monitoring::class, 'kegiatan_id');
    }
    public function pelatihan(){
        return $this->hasOne(Kegiatan_Pelatihan::class, 'kegiatan_id');
    }
    public function pembelanjaan(){
        return $this->hasOne(Kegiatan_Pembelanjaan::class, 'kegiatan_id');
    }
    public function pemetaan(){
        return $this->hasOne(Kegiatan_Pemetaan::class, 'kegiatan_id');
    }
    public function pengembangan(){
        return $this->hasOne(Kegiatan_Pengembangan::class, 'kegiatan_id');
    }
    public function sosialisasi(){
        return $this->hasOne(Kegiatan_Sosialisasi::class, 'kegiatan_id');
    }


    public function kegiatan_penulis()
    {
        return $this->belongsToMany(User::class, 'trkegiatanpenulis', 'kegiatan_id', 'penulis_id')
        ->withPivot('peran_id')
        ->withTimestamps();
    }
    public function sektor()
    {
        return $this->belongsToMany(mSektor::class, 'trkegiatan_sektor', 'kegiatan_id', 'sektor_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function desa()
    {
        return $this->belongsTo(Kelurahan::class, 'desa_id');
    }

}
