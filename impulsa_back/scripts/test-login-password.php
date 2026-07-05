<?php

declare(strict_types=1);

use App\Models\UserAuth;
use Illuminate\Support\Facades\Hash;
use Throwable;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$correo = $argv[1] ?? null;
$password = $argv[2] ?? null;

if ($correo === null || $password === null) {
    echo "Uso: php scripts/test-login-password.php correo@ejemplo.com \"tu-clave\"\n";
    exit(1);
}

try {
    $user = UserAuth::query()->where('correo', $correo)->first();

    if ($user === null) {
        echo "USER_NOT_FOUND\n";
        exit(1);
    }

    $hash = $user->getAuthPassword();
    echo 'HASH_PREFIX='.substr((string) $hash, 0, 7)."\n";
    echo Hash::check($password, $hash) ? "PASSWORD_OK\n" : "PASSWORD_FAIL\n";
} catch (Throwable $exception) {
    echo get_class($exception).': '.$exception->getMessage()."\n";
    exit(1);
}
