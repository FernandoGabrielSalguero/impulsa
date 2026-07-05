<?php

namespace App\Support;

class MarketingLabels
{
    /** @return list<string> */
    public static function planStatuses(): array
    {
        return ['draft', 'published', 'paused', 'archived'];
    }

    public static function planStatusLabel(string $status): string
    {
        return match ($status) {
            'draft' => 'Borrador',
            'published' => 'Publicado',
            'paused' => 'Pausado',
            'archived' => 'Archivado',
            default => $status,
        };
    }

    /** @return list<string> */
    public static function subscriptionStatuses(): array
    {
        return [
            'requested',
            'meeting_scheduled',
            'approved_manually',
            'pending_payment',
            'active',
            'paused',
            'completed',
            'cancelled',
        ];
    }

    public static function subscriptionStatusLabel(string $status): string
    {
        return match ($status) {
            'requested' => 'Solicitado',
            'meeting_scheduled' => 'Reunión agendada',
            'approved_manually' => 'Aprobado manual',
            'pending_payment' => 'Pago pendiente',
            'active' => 'Activo',
            'paused' => 'Pausado',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default => $status,
        };
    }

    /** @return list<string> */
    public static function paymentStatuses(): array
    {
        return ['not_required_yet', 'pending', 'paid', 'failed', 'cancelled'];
    }

    public static function paymentStatusLabel(string $status): string
    {
        return match ($status) {
            'not_required_yet' => 'Sin pago aún',
            'pending' => 'Pendiente',
            'paid' => 'Pagado',
            'failed' => 'Fallido',
            'cancelled' => 'Cancelado',
            default => $status,
        };
    }
}
