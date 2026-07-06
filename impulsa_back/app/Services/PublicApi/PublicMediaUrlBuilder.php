<?php

namespace App\Services\PublicApi;

class PublicMediaUrlBuilder
{
    public function url(?string $storedPath): ?string
    {
        $storedPath = trim((string) $storedPath);

        if ($storedPath === '') {
            return null;
        }

        $normalized = str_replace('\\', '/', $storedPath);
        $normalized = ltrim($normalized, '/');

        $baseUrl = trim((string) config('impulsa.public_storage_base_url', ''));

        if ($baseUrl === '') {
            $baseUrl = rtrim((string) config('impulsa.public_api_base_url'), '/') . '/storage';
        }

        return rtrim($baseUrl, '/') . '/' . $normalized;
    }
}
