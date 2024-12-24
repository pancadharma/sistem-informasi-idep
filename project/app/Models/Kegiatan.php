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
        'kode',
        'nama',
        'tanggalmulai',
        'tanggalselesai',
        'dusun_id',
        'long',
        'lat',
        'kategorilokasikegiatan_id',
        'tempat',
        'deskripsi',
        'tujuan',
        'yangterlibat',
        'pelatih',
        'informasilain',
        'luaslahan',
        'jenisbantuan_id',
        'satuan_id',
        'tindaklanjut',
        'tantangan',
        'rekomendasi',
        'user_id',
        'status',
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

    // public function getDurationInDays()
    // {
    //     return Carbon::parse($this->tanggalmulai)
    //         ->diffInDays(Carbon::parse($this->tanggalselesai));
    // }

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

    public const STATUS_SELECT = [
        'draft'    => 'Draft',
        'ongoing'  => 'Ongoing',
        'completed' => 'Completed',
        'cancelled ' => 'Cancelled',
    ];

    public const JENIS_KEGIATAN = [
        '1'     => 'Assessment',
        '2'     => 'Sosialisasi, workshop, peluasan jejaring, dan/atau pemetaan stakeholder',
        '3'     => 'Pelatihan / penguatan kapasitas',
        '4'     => 'Pembelanjaan / distribusi',
        '5'     => 'Pengembangan model',
        '6'     => 'Kampanye / advokasi',
        '7'     => 'Pemetaan',
        '8'     => 'Monitoring dan evaluasi',
        '9'     => 'Kunjungan / visitasi',
        '10'    => 'Konsultansi',
        '11'    => 'Others',
        '12'    => 'Ekonomi',
        '14'    => 'Lingkungan',
        '15'    => 'Sosial Budaya',
        '16'    => 'Kebencanaan',
        '17'    => 'Pendidikan',
        '18'    => 'Kesehatan Masyarakat',
    ];
}