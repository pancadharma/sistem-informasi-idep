<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Satuan extends Model
{
    use HasFactory, Auditable, SoftDeletes, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    function serializeDate(DateTimeInterface $date){
        return $date->format('Y-m-d H:i:s');
    }
}
