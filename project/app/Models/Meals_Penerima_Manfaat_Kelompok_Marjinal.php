<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meals_Penerima_Manfaat_Kelompok_Marjinal extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'trmeals_penerima_manfaat_kelompok_marjinal';
    protected $fillable = [
        'trmeals_penerima_manfaat_id',
        'master_jenis_kelompok_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function penerima_manfaat()
    {
        // Foreign key di tabel INI, Owner key (PK) di tabel Meals_Penerima_Manfaat
        return $this->belongsTo(Meals_Penerima_Manfaat::class, 'trmeals_penerima_manfaat_id');
    }
    public function kelompok_marjinal()
    {
        return $this->belongsTo(Kelompok_Marjinal::class, 'kelompok_marjinal_id');
    }
}
