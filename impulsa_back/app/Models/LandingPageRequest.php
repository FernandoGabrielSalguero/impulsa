<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingPageRequest extends Model
{
    protected $table = 'landing_page_request';

    protected $fillable = [
        'user_auth_id',
        'nombre_emprendimiento',
        'fecha_inicio',
        'descripcion',
        'dominio_registrado',
        'hosting_propio',
        'cantidad_colaboradores',
        'nombre_fundador',
        'vende_productos',
        'vende_servicios',
        'ya_factura',
        'espacio_fisico',
        'rubro_categoria_id',
        'rubro_subcategoria_id',
        'pais',
        'provincia',
        'localidad',
        'calle',
        'numero',
        'telefono_contacto',
        'completado',
    ];

    protected $casts = [
            'fecha_inicio' => 'date',
            'dominio_registrado' => 'boolean',
            'hosting_propio' => 'boolean',
            'vende_productos' => 'boolean',
            'vende_servicios' => 'boolean',
            'ya_factura' => 'boolean',
            'espacio_fisico' => 'boolean',
            'completado' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];

    public function userAuth(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }
}
