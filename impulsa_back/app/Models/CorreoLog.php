<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $user_auth_id
 * @property string $correo
 * @property string $asunto
 * @property string|null $template
 * @property string|null $mensaje_html
 * @property string|null $mensaje_text
 * @property string $estado
 * @property string|null $error
 * @property string|null $meta
 * @property Carbon|null $created_at
 */
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
    ];

    public function userAuth(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }
}