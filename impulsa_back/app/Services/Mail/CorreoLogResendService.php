<?php

namespace App\Services\Mail;

use App\Enums\MailTemplate;
use App\Models\CorreoLog;
use App\Models\UserAuth;
use App\Services\Auth\EmailVerificationService;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CorreoLogResendService
{
    public function __construct(
        private readonly CorreoLogService $correoLogService,
        private readonly EmailVerificationService $emailVerificationService,
    ) {}

    /**
     * @return array{ok: bool, message: string, log?: CorreoLog}
     */
    public function resend(CorreoLog $correoLog): array
    {
        $template = MailTemplate::tryFrom((string) ($correoLog->template ?? ''));

        if ($template === MailTemplate::VerifyEmail) {
            return $this->resendVerificationEmail($correoLog);
        }

        return $this->resendStoredContent($correoLog);
    }

    /**
     * @return array{ok: bool, message: string}
     */
    private function resendVerificationEmail(CorreoLog $correoLog): array
    {
        $user = $this->resolveUserForLog($correoLog);

        if ($user === null) {
            return [
                'ok' => false,
                'message' => 'No encontramos la cuenta asociada a este correo.',
            ];
        }

        return $this->emailVerificationService->resendVerificationEmail($user);
    }

    /**
     * @return array{ok: bool, message: string, log?: CorreoLog}
     */
    private function resendStoredContent(CorreoLog $correoLog): array
    {
        $correo = trim($correoLog->correo);
        $asunto = trim($correoLog->asunto);
        $html = (string) ($correoLog->mensaje_html ?? '');
        $text = (string) ($correoLog->mensaje_text ?? '');

        if ($correo === '' || ! filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return [
                'ok' => false,
                'message' => 'Correo de destino inválido.',
            ];
        }

        if ($asunto === '') {
            return [
                'ok' => false,
                'message' => 'El asunto guardado está vacío.',
            ];
        }

        if ($html === '' && $text === '') {
            return [
                'ok' => false,
                'message' => 'No hay contenido disponible para reenviar.',
            ];
        }

        $meta = [
            'reenviado_desde_correo_log_id' => $correoLog->id,
            'template_original' => $correoLog->template,
        ];

        $altBody = $text !== '' ? $text : trim(strip_tags($html));

        try {
            if ($html !== '') {
                Mail::html($html, function ($message) use ($correo, $asunto, $altBody): void {
                    $message->to($correo)->subject($asunto);

                    if ($altBody !== '') {
                        $message->text($altBody);
                    }
                });
            } else {
                Mail::raw($text, function ($message) use ($correo, $asunto): void {
                    $message->to($correo)->subject($asunto);
                });
            }

            $log = $this->correoLogService->logSent(
                template: MailTemplate::ReenvioCorreoLog,
                correo: $correo,
                asunto: $asunto,
                mensajeHtml: $html !== '' ? $html : null,
                mensajeText: $altBody !== '' ? $altBody : null,
                userAuthId: $correoLog->user_auth_id,
                meta: $meta,
            );

            return [
                'ok' => true,
                'message' => 'Correo reenviado correctamente.',
                'log' => $log->fresh(),
            ];
        } catch (Throwable $exception) {
            $log = $this->correoLogService->logFailed(
                template: MailTemplate::ReenvioCorreoLog,
                correo: $correo,
                asunto: $asunto,
                error: $exception->getMessage(),
                mensajeHtml: $html !== '' ? $html : null,
                mensajeText: $altBody !== '' ? $altBody : null,
                userAuthId: $correoLog->user_auth_id,
                meta: $meta,
            );

            return [
                'ok' => false,
                'message' => 'No se pudo reenviar el correo seleccionado.',
                'log' => $log->fresh(),
            ];
        }
    }

    private function resolveUserForLog(CorreoLog $correoLog): ?UserAuth
    {
        if ($correoLog->user_auth_id) {
            $user = UserAuth::query()->find($correoLog->user_auth_id);

            if ($user !== null) {
                return $user;
            }
        }

        return UserAuth::query()->where('correo', $correoLog->correo)->first();
    }
}
