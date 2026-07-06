<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class UserAuth extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'user_auth';

    protected $fillable = [
        'correo',
        'password',
        'rol',
        'verification_token',
        'email_verified_at',
        'usuario_tipo',
    ];

    protected $hidden = [
        'password',
        'verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function info(): HasOne
    {
        return $this->hasOne(UserInfo::class, 'user_auth_id');
    }

    public function contacto(): HasOne
    {
        return $this->hasOne(UserContacto::class, 'user_auth_id');
    }

    public function params(): HasOne
    {
        return $this->hasOne(UserParams::class, 'user_auth_id');
    }

    public function menuViews(): HasMany
    {
        return $this->hasMany(UserMenuView::class, 'user_auth_id');
    }
}
