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
        'jenis_kegiatan_id',
        'desa_id',
        'user_id',
        'mitra_id',
        'bentuk_kegiatan_id',
        'sektor_kegiatan_id',
        'fase_pelaporan',

        'kode',
        'nama',
        'tanggalmulai',
        'tanggalselesai',
        'long',
        'lat',
        'lokasi',
        'status',

        // update based on idep.pu
        'fasepelaporan',
        'deskripsilatarbelakang',
        'deskripsitujuan',
        'deskripsikeluaran',
        'deskripsiyangdikaji',

        'assessmentyangterlibat',
        'assessmenttemuan',
        'assessmenttambahan',
        'assessmenttambahan_ket',
        'assessmentkendala',
        'assessmentisu',
        'assessmentpembelajaran',
        'sosialisasiyangterlibat',
        'sosialisasitemuan',
        'sosialisasitambahan',
        'sosialisasitambahan_ket',
        'sosialisasikendala',
        'sosialisasiisu',
        'sosialisasipembelajaran',
        'pelatihanpelatih',
        'pelatihanhasil',
        'pelatihandistribusi',
        'pelatihandistribusi_ket',
        'pelatihanrencana',
        'pelatihanunggahan',
        'pelatihanisu',
        'pelatihanpembelajaran',
        'pembelanjaandetailbarang',
        'pembelanjaanmulai',
        'pembelanjaanselesai',
        'pembelanjaandistribusimulai',
        'pembelanjaandistribusiselesai',
        'pembelanjaanterdistribusi',
        'pembelanjaanakandistribusi',
        'pembelanjaanakandistribusi_ket',
        'pembelanjaankendala',
        'pembelanjaanisu',
        'pembelanjaanpembelajaran',
        'pengembanganjeniskomponen',
        'pengembanganberapakomponen',
        'pengembanganlokasikomponen',
        'pengembanganyangterlibat',
        'pengembanganrencana',
        'pengembangankendala',
        'pengembanganisu',
        'pengembanganpembelajaran',
        'kampanyeyangdikampanyekan',
        'kampanyejenis',
        'kampanyebentukkegiatan',
        'kampanyeyangterlibat',
        'kampanyeyangdisasar',
        'kampanyejangkauan',
        'kampanyerencana',
        'kampanyekendala',
        'kampanyeisu',
        'kampanyepembelajaran',
        'pemetaanyangdihasilkan',
        'pemetaanluasan',
        'pemetaanunit',
        'pemetaanyangterlibat',
        'pemetaanrencana',
        'pemetaanisu',
        'pemetaanpembelajaran',
        'monitoringyangdipantau',
        'monitoringdata',
        'monitoringyangterlibat',
        'monitoringmetode',
        'monitoringhasil',
        'monitoringkegiatanselanjutnya',
        'monitoringkegiatanselanjutnya_ket',
        'monitoringkendala',
        'monitoringisu',
        'monitoringpembelajaran',
        'kunjunganlembaga',
        'kunjunganpeserta',
        'kunjunganyangdilakukan',
        'kunjunganhasil',
        'kunjunganpotensipendapatan',
        'kunjunganrencana',
        'kunjungankendala',
        'kunjunganisu',
        'kunjunganpembelajaran',
        'konsultasilembaga',
        'konsultasikomponen',
        'konsultasiyangdilakukan',
        'konsultasihasil',
        'konsultasipotensipendapatan',
        'konsultasirencana',
        'konsultasikendala',
        'konsultasiisu',
        'konsultasipembelajaran',
        'lainnyamengapadilakukan',
        'lainnyadampak',
        'lainnyasumberpendanaan',
        'lainnyasumberpendanaan_ket',
        'lainnyayangterlibat',
        'lainnyarencana',
        'lainnyakendala',
        'lainnyaisu',
        'lainnyapembelajaran',
        'penerimamanfaatdewasaperempuan',
        'penerimamanfaatdewasalakilaki',
        'penerimamanfaatdewasatotal',
        'penerimamanfaatlansiaperempuan',
        'penerimamanfaatlansialakilaki',
        'penerimamanfaatlansiatotal',
        'penerimamanfaatremajaperempuan',
        'penerimamanfaatremajalakilak',
        'penerimamanfaatremajatotal',
        'penerimamanfaatanakperempuan',
        'penerimamanfaatanaklakilaki',
        'penerimamanfaatanaktotal',
        'penerimamanfaatdisabilitasperempuan',
        'penerimamanfaatdisabilitaslakilak',
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
