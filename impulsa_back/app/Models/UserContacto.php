<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserContacto extends Model
{
    protected $table = 'user_contacto';

    protected $fillable = [
        'user_auth_id',
        'correo',
        'check_correo',
        'permison_correo',
        'whatsapp',
        'check_whatsapp',
        'permison_whatsapp',
    ];

    protected function casts(): array
    {
        return [
            'check_correo' => 'boolean',
            'permison_correo' => 'boolean',
            'check_whatsapp' => 'boolean',
            'permison_whatsapp' => 'boolean',
        ];
    }

    public function userAuth(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }
}
