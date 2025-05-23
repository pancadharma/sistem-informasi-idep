<?php

namespace App\Models;

use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meals_PrePostTestPeserta extends Model
{
    use HasFactory, Auditable, LogsActivity;
    
    protected $table = 'trmealspreposttestpeserta';

    protected $fillable = [
        'preposttest_id',
        'dusun_id',
        'nama',
        'jeniskelamin',
        'notelp',
        'prescore',
        'filedbytraineepre',
        'postscore',
        'filedbytraineepost',
        'valuechange',
        'keterangan',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    public function preposttest()
    {
        return $this->belongsTo(Meals_PrePostTest::class, 'preposttest_id');
    }

    public function dusun()
    {
        return $this->belongsTo(Dusun::class, 'dusun_id');
    }
}
