<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use App\Models\User;
use App\Models\Mjabatan;

class Meals_Feedback_Response extends Model
{
    use HasFactory;

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
