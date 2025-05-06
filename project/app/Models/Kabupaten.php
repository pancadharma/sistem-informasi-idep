<?php

namespace App\Models;

use DateTime;
use DateTimeZone;
use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kabupaten extends Model
{
    use Auditable, HasFactory, LogsActivity;

    public $table = 'kabupaten';
    protected $fillable = ['kode', 'nama', 'type', 'provinsi_id', 'aktif', 'latitude', 'longitude', 'coordinates', 'created_at', 'updated_at'];
    protected $dates = ['created_at','updated_at',];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }


    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class, 'kabupaten_id');
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function dataKabupaten(){
        $kabupaten = Kabupaten::with('provinsi')->get();
        $data = DataTables::of($kabupaten)
            ->addIndexColumn()
            ->addColumn('action', function ($kabupaten) {
                return '<button type="button" class="btn btn-sm btn-info edit-kab-btn" data-action="edit" data-kabupaten-id="'. $kabupaten->id .'" title="'.__('global.edit') .' '. __('cruds.kabupaten.title') .' '. $kabupaten->nama .'"><i class="fas fa-pencil-alt"></i> Edit</button>

                <button type="button" class="btn btn-sm btn-primary view-kabupaten-btn" data-action="view" data-kabupaten-id="'. $kabupaten->id .'" value="'. $kabupaten->id .'" title="'.__('global.view') .' '. __('cruds.kabupaten.title') .' '. $kabupaten->nama .'"><i class="fas fa-folder-open"></i> View</button>';
            })
            ->make(true);
        return $data;
    }

}
