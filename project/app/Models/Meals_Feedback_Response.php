<?php

namespace App\Models;

use App\Models\User;
use DateTimeInterface;
use App\Models\Program;
use App\Models\Mjabatan;
use App\Traits\Auditable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meals_Feedback_Response extends Model
{
    use HasFactory, Auditable, LogsActivity;

    protected $table = 'trmealsfrm';

    protected $fillable = [
        'program_id',
        'tanggalregistrasi',
        'umur',
        'jeniskelamin',
        'statuskomplain',
        'notelp',
        'alamat',
        'hide',
        'userpenerima_id',
        'jabatanpenerima_id',
        'notelppenerima',
        'channels',
        'channelslainnya',
        'kategorikomplain',
        'deskripsikomplain',
        'tanggalselesai',
        'userhandler_id',
        'jabatanhandler_id',
        'notelphandler',
        'deskripsi',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);  // Pastikan log yang diinginkan
    }

    // Relationships
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'userpenerima_id', 'id');
    }

    public function jabatanPenerima()
    {
        return $this->belongsTo(Mjabatan::class, 'jabatanpenerima_id', 'id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'userhandler_id', 'id');
    }

    public function jabatanHandler()
    {
        return $this->belongsTo(Mjabatan::class, 'jabatanhandler_id', 'id');
    }
}
