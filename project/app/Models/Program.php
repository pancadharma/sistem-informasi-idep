<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Program extends Model implements HasMedia
{
    use Auditable, HasFactory, InteractsWithMedia;

    protected $table = 'trprogram';

    protected $dates = [
        'created_at',
        'updated_at',
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

    protected $date = [
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function users() {
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

    public function getImageAttribute()
    {
        $file = $this->getMedia('image')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }


    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit(Fit::Crop, 50, 50);
        $this->addMediaConversion('preview')->fit(Fit::Crop, 120, 120);
    }

    public function getFilePendukungAttribute()
    {
        return $this->getMedia('file_pendukung_program');
    }
}
