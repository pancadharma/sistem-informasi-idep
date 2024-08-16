<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelurahan extends Model
{
    use Auditable, HasFactory;
    public $table = "kelurahan";
    protected $fillable = [
        'kode', 'nama', 'aktif', 'kecamatan_id', 'created_at','updated_at'
    ];
    protected $casts = [
        'updated_at',
        'created_at'
    ];
}
