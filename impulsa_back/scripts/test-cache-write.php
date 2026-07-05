<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$cacheDir = storage_path('framework/cache/data');
$testFile = $cacheDir.'/permission-test-'.uniqid('', true).'.txt';

echo 'PHP_USER='.(function_exists('posix_getpwuid') && function_exists('posix_geteuid')
    ? posix_getpwuid(posix_geteuid())['name']
    : get_current_user())."\n";

try {
    if (! is_dir($cacheDir)) {
        mkdir($cacheDir, 0775, true);
    }

    if (file_put_contents($testFile, 'ok') === false) {
        echo "CACHE_WRITE=FAIL\n";
        exit(1);
    }

    unlink($testFile);
    echo "CACHE_WRITE=OK\n";

    cache()->put('impulsa-permission-test', 'ok', 60);
    echo 'CACHE_STORE='.config('cache.default')."\n";
    echo cache()->get('impulsa-permission-test') === 'ok' ? "CACHE_DRIVER=OK\n" : "CACHE_DRIVER=FAIL\n";
} catch (Throwable $exception) {
    echo 'CACHE_ERROR='.get_class($exception).': '.$exception->getMessage()."\n";
    exit(1);
}
