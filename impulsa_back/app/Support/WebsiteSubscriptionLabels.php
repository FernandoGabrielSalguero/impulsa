<?php

namespace App\Support;

class WebsiteSubscriptionLabels
{
    /** @return list<string> */
    public static function subscriptionStatuses(): array
    {
        return ['active', 'paused', 'cancelled'];
    }

    public static function subscriptionStatusLabel(string $status): string
    {
        return match ($status) {
            'active' => 'Activa',
            'paused' => 'Pausada',
            'cancelled' => 'Cancelada',
            default => $status,
        };
    }

    /** @return list<string> */
    public static function periodStatuses(): array
    {
        return ['pending', 'paid', 'grace', 'waived', 'overdue'];
    }

    public static function periodStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Pendiente',
            'paid' => 'Pagado',
            'grace' => 'Gracia',
            'waived' => 'Exento',
            'overdue' => 'Vencido',
            default => $status,
        };
    }
}
