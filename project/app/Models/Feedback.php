<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedback'; // Opsional jika nama tabel sudah sesuai konvensi

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'program',
        'tanggal_registrasi',
        'umur',
        'penerima',
        'sort_of_complaint',
        'age_group',
        'position',
        'tanggal_selesai',
        'sex',
        'kontak_penerima',
        'handler',
        'phone_number',
        'channels',
        'position_handler',
        'address',
        'other_channel',
        'kontak_handler',
        'kategori_komplain',
        'deskripsi',
        'status_complaint', // Jangan lupa tambahkan status jika bisa diubah lewat form
    ];

     /**
      * The attributes that should be cast.
      *
      * @var array<string, string>
      */
     protected $casts = [
         'tanggal_registrasi' => 'date',
         'tanggal_selesai' => 'date',
     ];
}