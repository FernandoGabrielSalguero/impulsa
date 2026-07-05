<?php

namespace App\Mail;

use App\Enums\MailTemplate;
use App\Models\UserAuth;

class NewUserClienteMail extends ImpulsaMailable
{
    public function __construct(
        private readonly UserAuth $user,
        private readonly string $password,
        private readonly string $link,
    ) {}

    public function mailTemplate(): MailTemplate
    {
        return MailTemplate::NewUserCliente;
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
        return 'Tu acceso a Impulsa';
    }

    public function htmlView(): string
    {
        return 'mail.new-user-cliente';
    }

    public function textView(): string
    {
        return 'mail.new-user-cliente-text';
    }

    public function viewData(): array
    {
        return [
            'title' => $this->subjectLine(),
            'nombre' => $this->user->info?->nombre ?: 'Cliente',
            'correo' => $this->user->correo,
            'password' => $this->password,
            'link' => $this->link,
        ];
    }
}
