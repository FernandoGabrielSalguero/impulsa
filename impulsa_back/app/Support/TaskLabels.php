<?php

namespace App\Support;

final class TaskLabels
{
    /** @var array<string, string> */
    private const STATUS = [
        'pendiente' => 'Pendiente',
        'en_progreso' => 'En progreso',
        'completada' => 'Completada',
        'cancelada' => 'Cancelada',
    ];

    public static function statusLabel(?string $status): string
    {
        return self::STATUS[$status ?? ''] ?? ($status ?? '—');
    }

    public static function defconLabel(int $level): string
    {
        return 'DEFCON ' . $level;
    }

    /** @return list<string> */
    public static function statuses(): array
    {
        return array_keys(self::STATUS);
    }

    /** @return list<int> */
    public static function defconLevels(): array
    {
        return [1, 2, 3, 4, 5];
    }
}
