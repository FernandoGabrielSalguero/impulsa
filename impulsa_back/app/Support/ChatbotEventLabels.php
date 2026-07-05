<?php

namespace App\Support;

class ChatbotEventLabels
{
    /** @return array<string, string> */
    public static function labels(): array
    {
        return [
            'widget_loaded' => 'Widget cargado',
            'bubble_opened' => 'Burbuja abierta',
            'question_viewed' => 'Pregunta vista',
            'option_clicked' => 'Opción clickeada',
            'whatsapp_clicked' => 'WhatsApp clickeado',
            'chat_closed' => 'Chat cerrado',
        ];
    }

    public static function label(string $eventType): string
    {
        return self::labels()[$eventType] ?? str_replace('_', ' ', $eventType);
    }
}
