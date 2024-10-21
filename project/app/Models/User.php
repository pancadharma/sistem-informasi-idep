<?php

namespace App\Models;


use Carbon\Carbon;
use DateTimeInterface;
use App\Traits\Auditable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Hash;
use GedeAdi\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Image\Enums\Fit;

class User extends Authenticatable implements HasMedia
{
    use Auditable, HasRoles, HasApiTokens, Notifiable, SoftDeletes, InteractsWithMedia;

    protected $table = 'users';

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nama',
        'email',
        'username',
        'jabatan_id',
        'email_verified_at',
        'password',
        'remember_token',
        'description',
        'aktif',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit(Fit::Crop, 240, 240);
        $this->addMediaConversion('preview')->fit(Fit::Crop, 320, 320);
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function jabatans()
    {
        return $this->belongsTo(Mjabatan::class, 'jabatan_id');
    }

    public function getImageAttribute()
    {
        // $file = $this->getMedia('userprofile')->last();
        // if ($file) {
        //     $file->url       = $file->getUrl();
        //     $file->thumbnail = $file->getUrl('thumb');
        //     $file->preview   = $file->getUrl('preview');
        // }

        // return $file;
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('userprofile')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')->fit(Fit::Crop, 240, 240);
                $this->addMediaConversion('preview')->fit(Fit::Crop, 320, 320);
            });
    }

    public function adminlte_image()
    {
        $media = $this->getFirstMedia('userprofile');
        return $media ? $media->getUrl('thumb') : '/vendor/adminlte/dist/img/idep.png';

    }
    public function full_profile()
    {
        $media = $this->getFirstMedia('userprofile');
        return $media ? $media->getUrl() : '/vendor/adminlte/dist/img/idep.png';

    }

    public function adminlte_desc()
    {
        if ($this->description) {
            return $this->description;
        }
        return " Update in Profile Bio ";
    }


    public function adminlte_profile_url()
    {
        $identifier = $this->username ?? $this->id; // Get username or id
        return route('profile.show', ['identifier' => $identifier]); // Generate route
    }

}
