<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorreoLog extends Model
{
    public $timestamps = false;

    protected $table = 'correos_log';

    protected $fillable = [
        'user_auth_id',
        'correo',
        'asunto',
        'template',
        'mensaje_html',
        'mensaje_text',
        'estado',
        'error',
        'meta',
    ];

    protected $casts = [
            'created_at' => 'datetime',
            'meta' => 'array',
        ];

    public function userAuth(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }
}