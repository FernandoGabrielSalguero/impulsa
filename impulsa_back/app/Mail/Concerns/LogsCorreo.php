<?php

namespace App\Mail\Concerns;

use App\Enums\MailTemplate;

interface LogsCorreo
{
    public function mailTemplate(): MailTemplate;

    public function recipientEmail(): string;

    public function userAuthId(): ?int;

    public function mailMeta(): array;
}