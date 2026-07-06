<?php

namespace App\Support;

class ImpulsaFrontendUrl
{
    public static function to(string $path = ''): string
    {
        $base = rtrim((string) config('impulsa.frontend_url'), '/');
        $appPath = trim((string) config('impulsa.frontend_app_path', 'impulsa_front'), '/');

        if ($appPath !== '' && ! self::isLocalBase($base) && ! self::baseAlreadyIncludesAppPath($base, $appPath)) {
            $base .= '/' . $appPath;
        }

        $path = ltrim($path, '/');

        return $path === '' ? $base : $base . '/' . $path;
    }

    private static function isLocalBase(string $base): bool
    {
        return str_contains($base, 'localhost') || str_contains($base, '127.0.0.1');
    }

    private static function baseAlreadyIncludesAppPath(string $base, string $appPath): bool
    {
        return str_ends_with($base, '/' . $appPath) || str_ends_with($base, $appPath);
    }
}
