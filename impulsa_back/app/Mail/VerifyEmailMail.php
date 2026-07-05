<?php

namespace App\Mail;

use App\Enums\MailTemplate;
use App\Models\UserAuth;

class VerifyEmailMail extends ImpulsaMailable
{
    public function __construct(
        private readonly UserAuth $user,
        private readonly string $verificationUrl,
    ) {}

    public function mailTemplate(): MailTemplate
    {
        return MailTemplate::VerifyEmail;
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
        return 'Verificá tu dirección de correo — Impulsa';
    }

    public function htmlView(): string
    {
        return 'mail.verify-email';
    }

    public function textView(): string
    {
        return 'mail.verify-email-text';
    }

    public function viewData(): array
    {
        return [
            'title' => $this->subjectLine(),
            'correo' => $this->user->correo,
            'link' => $this->verificationUrl,
        ];
    }
}