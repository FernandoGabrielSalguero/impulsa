<?php

namespace App\Support;

class ApiProductLabels
{
    /** @return list<string> */
    public static function statuses(): array
    {
        return ['draft', 'active', 'inactive'];
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'draft' => 'Borrador',
            'active' => 'Activo',
            'inactive' => 'Inactivo',
            default => $status,
        };
    }

    /** @return list<string> */
    public static function availabilities(): array
    {
        return ['in_stock', 'out_of_stock', 'preorder', 'on_request'];
    }

    public static function availabilityLabel(string $availability): string
    {
        return match ($availability) {
            'in_stock' => 'En stock',
            'out_of_stock' => 'Sin stock',
            'preorder' => 'Preventa',
            'on_request' => 'Bajo consulta',
            default => $availability,
        };
    }
}
