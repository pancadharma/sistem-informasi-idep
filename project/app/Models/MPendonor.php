<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\Auditable;
use Yajra\DataTables\DataTables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MPendonor extends Model
{
    use Auditable, HasFactory;
    protected $table = 'mpendonor';

    protected $fillable = [
        'mpendonorkategori_id',
        'nama',
        'pic',
        'email',
        'phone',
        'aktif',
        'created_at',
        'updated_at',
    ];

    protected $date = [
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function mpendonnorkategori()
    {
        return $this->belongsTo(Kategori_Pendonor::class, 'mpendonorkategori_id');
    }

    public function program()
    {
        return $this->belongsToMany(Program::class, 'trprogrampendonor', 'pendonor_id', 'program_id')->withPivot('nilaidonasi');;
    }
}
