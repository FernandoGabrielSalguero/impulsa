<?php

namespace App\Exceptions;

use RuntimeException;

class HostingerMailException extends RuntimeException
{
    public function __construct(string $message = 'No pudimos conectar con el correo de Hostinger.')
    {
        parent::__construct($message);
    }
}
