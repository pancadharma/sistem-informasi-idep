<?php

namespace App\Models;

use DateTimeInterface;
use App\Models\Kelurahan;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dusun extends Model
{
    use Auditable, HasFactory;
    public $table = "dusun";
    protected $fillable = [
        'kode', 'nama', 'aktif', 'desa_id', 'kode_pos', 'created_at','updated_at'
    ];  
    protected $casts = [
        'updated_at',
        'created_at'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function desa() {
        return $this->belongsTo(Kelurahan::class, 'desa_id');
    }
}
