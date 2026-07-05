<?php

namespace App\Services\Mail;

use App\Mail\Concerns\LogsCorreo;
use App\Mail\ImpulsaMailable;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ImpulsaMailService
{
    public function __construct(
        private readonly CorreoLogService $correoLogService,
    ) {}

    public function send(ImpulsaMailable $mailable): bool
    {
        $html = null;
        $text = null;

        try {
            $html = $mailable->render();
            $text = view($mailable->textView(), $mailable->viewData())->render();

            Mail::to($mailable->recipientEmail())->send($mailable);

            $this->correoLogService->logSent(
                template: $mailable->mailTemplate(),
                correo: $mailable->recipientEmail(),
                asunto: $mailable->subjectLine(),
                mensajeHtml: $html,
                mensajeText: $text,
                userAuthId: $mailable->userAuthId(),
                meta: $mailable->mailMeta(),
            );

            return true;
        } catch (Throwable $exception) {
            $this->correoLogService->logFailed(
                template: $mailable->mailTemplate(),
                correo: $mailable->recipientEmail(),
                asunto: $mailable->subjectLine(),
                error: $exception->getMessage(),
                mensajeHtml: $html,
                mensajeText: $text,
                userAuthId: $mailable->userAuthId(),
                meta: $mailable->mailMeta(),
            );

            return false;
        }
    }
}