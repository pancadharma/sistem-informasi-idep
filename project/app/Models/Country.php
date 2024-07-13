<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;


class Country extends Model
{
    use Auditable, HasFactory;

    protected $table = 'country';

    protected $fillable = [
        'nama',
        'aktif',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
