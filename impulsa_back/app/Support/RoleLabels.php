<?php

namespace App\Support;

class RoleLabels
{
    private const LABELS = [
        'impulsa_administrador' => 'Administrador',
        'impulsa_marketing' => 'Marketing',
        'impulsa_emprendedor' => 'Emprendedor',
        'impulsa_cliente' => 'Cliente',
        'impulsa_colaborador' => 'Colaborador',
        'impulsa_usuario' => 'Usuario',
    ];

    public static function labelFor(string $role): string
    {
        return self::LABELS[$role] ?? $role;
    }

    public static function all(): array
    {
        return self::LABELS;
    }
}