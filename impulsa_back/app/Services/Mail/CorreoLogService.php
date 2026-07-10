<?php

namespace App\Services\Mail;

use App\Enums\MailTemplate;
use App\Models\CorreoLog;

class CorreoLogService
{
    public function logSent(
        MailTemplate $template,
        string $correo,
        string $asunto,
        ?string $mensajeHtml,
        ?string $mensajeText,
        ?int $userAuthId = null,
        array $meta = [],
    ): CorreoLog {
        return CorreoLog::query()->create([
            'user_auth_id' => $userAuthId,
            'correo' => $correo,
            'asunto' => $asunto,
            'template' => $template->value,
            'mensaje_html' => $mensajeHtml,
            'mensaje_text' => $mensajeText,
            'estado' => 'enviado',
            'meta' => $this->encodeMeta($meta),
        ]);
    }

    public function logFailed(
        MailTemplate $template,
        string $correo,
        string $asunto,
        string $error,
        ?string $mensajeHtml = null,
        ?string $mensajeText = null,
        ?int $userAuthId = null,
        array $meta = [],
    ): CorreoLog {
        return CorreoLog::query()->create([
            'user_auth_id' => $userAuthId,
            'correo' => $correo,
            'asunto' => $asunto,
            'template' => $template->value,
            'mensaje_html' => $mensajeHtml,
            'mensaje_text' => $mensajeText,
            'estado' => 'fallido',
            'error' => $error,
            'meta' => $this->encodeMeta($meta),
        ]);
    }

    private function encodeMeta(array $meta): ?string
    {
        if ($meta === []) {
            return null;
        }

        $encoded = json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $encoded === false ? null : $encoded;
    }
}