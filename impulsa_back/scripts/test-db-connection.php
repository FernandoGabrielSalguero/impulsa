<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    Illuminate\Support\Facades\DB::connection()->getPdo();
    echo 'DB connection: OK' . PHP_EOL;
    echo 'Cache driver: ' . config('cache.default') . PHP_EOL;
    echo 'Session driver: ' . config('session.driver') . PHP_EOL;
} catch (Throwable $e) {
    echo 'DB connection: FAIL' . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
    exit(1);
}
