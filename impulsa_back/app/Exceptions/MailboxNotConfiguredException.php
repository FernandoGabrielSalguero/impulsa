<?php

namespace App\Exceptions;

use RuntimeException;

class MailboxNotConfiguredException extends RuntimeException
{
    public function __construct(string $message = 'No tenés un correo corporativo habilitado.')
    {
        parent::__construct($message);
    }
}
