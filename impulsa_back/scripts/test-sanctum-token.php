<?php

declare(strict_types=1);

use App\Models\UserAuth;
use Throwable;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$correo = $argv[1] ?? 'fernandosalguero685@gmail.com';

try {
    $user = UserAuth::query()->where('correo', $correo)->first();

    if ($user === null) {
        echo "USER_NOT_FOUND\n";
        exit(1);
    }

    $user->tokens()->delete();
    $user->createToken('auth-token');

    echo "TOKEN_OK\n";
    exit(0);
} catch (Throwable $exception) {
    echo get_class($exception).': '.$exception->getMessage()."\n";
    exit(1);
}
