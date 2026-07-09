<?php

namespace App\Support;

class AcademiaLabels
{
    /** @return list<string> */
    public static function statuses(): array
    {
        return ['draft', 'active', 'inactive'];
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'active' => 'Activo',
            'inactive' => 'Inactivo',
            default => 'Borrador',
        };
    }
}
