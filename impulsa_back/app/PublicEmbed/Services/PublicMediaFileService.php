<?php

namespace App\PublicEmbed\Services;

use App\Services\ApiBlog\ApiBlogPostStorageService;
use App\Services\ApiProduct\ApiProductStorageService;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class PublicMediaFileService
{
    private const ALLOWED_PREFIXES = ['API_Blog/', 'API_Product/'];

    public function __construct(
        private readonly ApiBlogPostStorageService $blogStorage,
        private readonly ApiProductStorageService $productStorage,
    ) {}

    /** @return array{path: string, mime: string} */
    public function resolve(string $relativePath): array
    {
        $relativePath = $this->normalizeRelativePath($relativePath);

        if ($relativePath === '') {
            throw ValidationException::withMessages([
                'path' => ['La ruta del archivo es obligatoria.'],
            ]);
        }

        if (! $this->isAllowedPath($relativePath)) {
            throw ValidationException::withMessages([
                'path' => ['Ruta de archivo no permitida.'],
            ]);
        }

        $absolutePath = str_starts_with($relativePath, 'API_Blog/')
            ? $this->blogStorage->resolveAbsolutePath($relativePath)
            : $this->productStorage->resolveAbsolutePath($relativePath);

        if ($absolutePath === null || ! is_file($absolutePath)) {
            throw ValidationException::withMessages([
                'path' => ['Archivo no encontrado.'],
            ]);
        }

        return [
            'path' => $absolutePath,
            'mime' => $this->resolveMimeType($absolutePath),
        ];
    }

    private function normalizeRelativePath(string $relativePath): string
    {
        $relativePath = str_replace('\\', '/', trim($relativePath));
        $relativePath = ltrim($relativePath, '/');

        if ($relativePath === '' || str_contains($relativePath, '..')) {
            return '';
        }

        return $relativePath;
    }

    private function isAllowedPath(string $relativePath): bool
    {
        foreach (self::ALLOWED_PREFIXES as $prefix) {
            if (str_starts_with($relativePath, $prefix)) {
                return true;
            }
        }

        return false;
    }

    private function resolveMimeType(string $absolutePath): string
    {
        $detected = File::mimeType($absolutePath);

        if (is_string($detected) && $detected !== '') {
            return $detected;
        }

        return match (strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION))) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
            default => 'application/octet-stream',
        };
    }
}
