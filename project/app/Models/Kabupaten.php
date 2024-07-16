<?php

namespace App\Models;

use DateTimeZone;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kabupaten extends Model
{
    use HasFactory;



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

    

}
