<?php

namespace App\Support;

class GoalLabels
{
    /** @return list<string> */
    public static function statuses(): array
    {
        return ['pending', 'in_progress', 'completed', 'cancelled'];
    }

    /** @return list<array{value: string, label: string}> */
    public static function statusOptions(): array
    {
        return [
            ['value' => 'pending', 'label' => 'Pendiente'],
            ['value' => 'in_progress', 'label' => 'En progreso'],
            ['value' => 'completed', 'label' => 'Completada'],
            ['value' => 'cancelled', 'label' => 'Cancelada'],
        ];
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Pendiente',
            'in_progress' => 'En progreso',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            default => $status,
        };
    }
}
