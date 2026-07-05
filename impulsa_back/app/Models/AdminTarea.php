<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminTarea extends Model
{
    protected $table = 'admin_tareas';

    protected $fillable = [
        'nombre_tarea',
        'responsable_user_id',
        'descripcion',
        'fecha_entrega',
        'prioridad_defcon',
        'reporta_a',
        'estado',
        'created_by_user_id',
        'completed_at',
    ];

    protected $casts = [
            'fecha_entrega' => 'date',
            'prioridad_defcon' => 'integer',
            'completed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'responsable_user_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'created_by_user_id');
    }
}
