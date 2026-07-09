<?php

namespace App\Mail;

use App\Enums\MailTemplate;
use App\Models\UserAuth;

class ResetPasswordMail extends ImpulsaMailable
{
    public function __construct(
        private readonly UserAuth $user,
        private readonly string $resetUrl,
    ) {}

    public function mailTemplate(): MailTemplate
    {
        return MailTemplate::ResetPassword;
    }

    public function recipientEmail(): string
    {
        return $this->user->correo;
    }

    public function userAuthId(): ?int
    {
        return $this->user->id;
    }

    public function subjectLine(): string
    {
        return 'Restablecé tu contraseña — Impulsa';
    }

    public function htmlView(): string
    {
        return 'mail.reset-password';
    }

    public function textView(): string
    {
        return 'mail.reset-password-text';
    }

    public function viewData(): array
    {
        $nombre = $this->user->info?->nombre;

        return [
            'title' => $this->subjectLine(),
            'nombre' => $nombre !== null && $nombre !== '' ? $nombre : null,
            'correo' => $this->user->correo,
            'link' => $this->resetUrl,
        ];
    }
}
