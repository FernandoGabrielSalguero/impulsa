<?php

declare(strict_types=1);

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
/** @var Kernel $kernel */
$kernel = $app->make(Kernel::class);

$correo = $argv[1] ?? 'fernandosalguero685@gmail.com';
$password = $argv[2] ?? '';

$payload = json_encode([
    'correo' => $correo,
    'password' => $password,
], JSON_THROW_ON_ERROR);

$request = Request::create(
    '/api/v1/auth/login',
    'POST',
    [],
    [],
    [],
    [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_ACCEPT' => 'application/json',
    ],
    $payload,
);

$response = $kernel->handle($request);

echo 'STATUS='.$response->getStatusCode()."\n";
echo $response->getContent()."\n";

$kernel->terminate($request, $response);
