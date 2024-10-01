<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Satuan extends Model
{
    use HasFactory, Auditable, SoftDeletes;

    protected $table = 'msatuan';

    protected $fillable = [
        'nama',
        'aktif',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    function serializeDate(DateTimeInterface $date){
        return $date->format('Y-m-d H:i:s');
    }
}
