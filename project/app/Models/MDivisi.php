<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MDivisi extends Model
{
    use HasFactory;

    protected $table = 'mdivisi';

    protected $fillable = [
        'nama',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    /* ===============================
     * RELATION
     * =============================== */

    public function jabatans()
    {
        return $this->hasMany(MJabatan::class, 'divisi_id');
    }
}
