<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use HasFactory, Auditable, SoftDeletes, HasRoles;
    protected $table = 'mpartner';

    protected $fillable = [
        'nama',
        'keterangan',
        'aktif',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
