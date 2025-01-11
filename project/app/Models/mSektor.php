<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class mSektor extends Model
{
    use HasFactory, Auditable, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    protected $table = 'msektor';
    protected $fillable = [
        'nama',
        'created_at',
        'updated_at',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $appends = [
        'created_at_human',
        'updated_at_human',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    public function kegiatan_sektor()
    {
        return $this->hasMany(Kegiatan_Sektor::class, 'sektor_id', 'id');
    }
    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'trkegiatan_sektor', 'sektor_id', 'kegiatan_id');
    }
}
