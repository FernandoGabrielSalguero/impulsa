<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Throwable;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$correo = $argv[1] ?? 'fernandosalguero685@gmail.com';
$password = $argv[2] ?? '';

$payload = json_encode([
    'correo' => $correo,
    'password' => $password,
], JSON_THROW_ON_ERROR);

$base = Request::create(
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

/** @var LoginRequest $request */
$request = LoginRequest::createFrom($base);
$request->setContainer($app);
$request->setRedirector($app->make('redirect'));
$request->validateResolved();

try {
    $response = $app->make(AuthController::class)->login($request);
    echo 'STATUS='.$response->getStatusCode()."\n";
    echo $response->getContent()."\n";
} catch (Throwable $exception) {
    echo get_class($exception).': '.$exception->getMessage()."\n";
    exit(1);
}
