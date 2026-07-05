<?php

namespace App\Support;

final class IntegrationLabels
{
    /** @var array<string, string> */
    private const STATUS = [
        'active' => 'Activa',
        'inactive' => 'Inactiva',
    ];

    public static function statusLabel(?string $status): string
    {
        return self::STATUS[$status ?? ''] ?? ($status ?? '—');
    }

    /** @return list<string> */
    public static function statuses(): array
    {
        return array_keys(self::STATUS);
    }
}
