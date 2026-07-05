<?php

namespace App\Support;

class ChatbotLabels
{
    /** @return list<string> */
    public static function statuses(): array
    {
        return ['active', 'inactive'];
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'active' => 'Activo',
            'inactive' => 'Inactivo',
            default => $status,
        };
    }

    public static function adminLockLabel(bool $disabledByAdmin): string
    {
        return $disabledByAdmin ? 'Bloqueado' : 'Libre';
    }

    /** @return list<string> */
    public static function eventTypes(): array
    {
        return [
            'widget_loaded',
            'bubble_opened',
            'question_viewed',
            'option_clicked',
            'whatsapp_clicked',
            'chat_closed',
        ];
    }

    public static function eventTypeLabel(string $eventType): string
    {
        return match ($eventType) {
            'widget_loaded' => 'Widget cargado',
            'bubble_opened' => 'Burbuja abierta',
            'question_viewed' => 'Pregunta vista',
            'option_clicked' => 'Opción clickeada',
            'whatsapp_clicked' => 'WhatsApp clickeado',
            'chat_closed' => 'Chat cerrado',
            default => $eventType,
        };
    }
}
