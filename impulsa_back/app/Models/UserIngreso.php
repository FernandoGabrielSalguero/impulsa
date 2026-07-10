<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserIngreso extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'user_ingresos';

    protected $fillable = [
        'user_auth_id',
        'nombre_usuario',
        'rol',
        'fecha_ingreso',
        'hora_ingreso',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'created_at' => 'datetime',
    ];

    public function userAuth(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }
}
