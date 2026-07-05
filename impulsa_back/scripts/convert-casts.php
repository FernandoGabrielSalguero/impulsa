<?php

$dir = __DIR__ . '/../app/Models';

foreach (glob($dir . '/*.php') as $file) {
    $content = file_get_contents($file);

    if (! str_contains($content, 'protected function casts(): array')) {
        continue;
    }

    $updated = preg_replace(
        '/protected function casts\(\): array\s*\{\s*return (\[[\s\S]*?\]);\s*\}/',
        'protected $casts = $1;',
        $content,
        1,
        $count
    );

    if ($count) {
        file_put_contents($file, $updated);
        echo basename($file) . PHP_EOL;
    }
}
