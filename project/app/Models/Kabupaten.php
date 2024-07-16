<?php

namespace App\Models;

use DateTimeZone;
use DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kabupaten extends Model
{
    use Auditable, HasFactory;

    public $table = 'kabupaten';
    protected $fillable = ['kode', 'nama', 'type', 'aktif', 'created_at', 'updated_at'];
    protected $dates = ['created_at','updated_at',];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function kecamatan()
    {
        // return $this->hasMany(Kecamatan::class, 'kabupten_id');
    }


    protected function serializeDate(DateTimeInterface $date)
    {
        // Assuming UTC+8 is Asia/Singapore timezone
        $date = $date->setTimezone(new DateTimeZone('Asia/Singapore'));
        return $date->format('Y-m-d H:i:s');
    }

    public function dataKabupaten(){
        $kabupaten = Kabupaten::with('provinsi')->get();
        // Prepare data for DataTables (without modifying original collection)
        $data = DataTables::of($kabupaten)
            ->addColumn('action', function ($kabupaten) {
                return '<button type="button" class="btn btn-sm btn-info edit-kab-btn" data-action="edit" data-kabupaten-id="'. $kabupaten->id .'" title="'.__('global.edit') .' '. __('cruds.kabupaten.title') .' '. $kabupaten->nama .'"><i class="fas fa-pencil-alt"></i> Edit</button>

                <button type="button" class="btn btn-sm btn-primary view-kabupaten-btn" data-action="view" data-kabupaten-id="'. $kabupaten->id .'" value="'. $kabupaten->id .'" title="'.__('global.view') .' '. __('cruds.kabupaten.title') .' '. $kabupaten->nama .'"><i class="fas fa-folder-open"></i> View</button>';
            })
            ->make(true);
        return $data;
    }
    
}
