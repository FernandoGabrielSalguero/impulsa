<?php

namespace App\Services\ApiBlog;

use App\Services\ApiProduct\ApiProductNormalizer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class ApiBlogPostStorageService
{
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    private const IMAGE_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    private const ATTACHMENT_EXTENSIONS = ['pdf', 'doc', 'docx', 'txt'];

    private const ATTACHMENT_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'text/plain',
    ];

    /** @return array<string, string|null> */
    public function resolveUploadedFiles(array $files, ?array $existing = null, array $payload = []): array
    {
        $result = [];

        foreach ($this->fileFieldConfigs() as $fieldName => $config) {
            $column = $config['column'];
            $current = trim((string) ($existing[$column] ?? ''));
            $removeField = (string) ($config['remove_field'] ?? '');
            $shouldRemove = $removeField !== '' && filter_var($payload[$removeField] ?? false, FILTER_VALIDATE_BOOL);

            if ($shouldRemove && $current !== '') {
                $this->deleteStoredPath($current);
                $current = '';
            }

            $uploaded = $files[$fieldName] ?? null;
            $storedPath = $this->storeUploadedFile($uploaded, $config, $current);

            $result[$column] = $storedPath !== '' ? $storedPath : null;
        }

        return $result;
    }

    public function resolveAbsolutePath(?string $storedPath): ?string
    {
        $storedPath = trim((string) $storedPath);

        if ($storedPath === '') {
            return null;
        }

        $normalized = str_replace('\\', '/', $storedPath);

        if ($this->isExistingFile($normalized)) {
            return $normalized;
        }

        $baseName = basename($normalized);

        foreach ($this->candidateStorageDirectories() as $directory) {
            $candidate = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $baseName;

            if ($this->isExistingFile($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    public function deleteStoredPath(?string $storedPath): void
    {
        $absolutePath = $this->resolveAbsolutePath($storedPath);

        if ($absolutePath !== null && is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }

    /** @return array<string, array<string, mixed>> */
    private function fileFieldConfigs(): array
    {
        return [
            'cover_image_file' => [
                'column' => 'cover_image_path',
                'label' => 'portada',
                'extensions' => self::IMAGE_EXTENSIONS,
                'mime_types' => self::IMAGE_MIME_TYPES,
                'max_bytes' => 4 * 1024 * 1024,
                'prefix' => 'blog_cover',
                'remove_field' => 'remove_cover_image',
            ],
            'attachment_file' => [
                'column' => 'attachment_path',
                'label' => 'adjunto',
                'extensions' => self::ATTACHMENT_EXTENSIONS,
                'mime_types' => self::ATTACHMENT_MIME_TYPES,
                'max_bytes' => 8 * 1024 * 1024,
                'prefix' => 'blog_attachment',
                'remove_field' => 'remove_attachment',
            ],
        ];
    }

    private function storeUploadedFile(?UploadedFile $file, array $config, string $currentPath): string
    {
        if (! $file instanceof UploadedFile) {
            return $currentPath;
        }

        if (! $file->isValid()) {
            throw ValidationException::withMessages([
                $config['column'] => ['No se pudo subir el archivo para ' . $config['label'] . '.'],
            ]);
        }

        $fileSize = (int) $file->getSize();

        if ($fileSize <= 0 || $fileSize > (int) $config['max_bytes']) {
            throw ValidationException::withMessages([
                $config['column'] => ['El archivo ' . $config['label'] . ' supera el tamaño permitido.'],
            ]);
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());

        if (! in_array($extension, $config['extensions'], true)) {
            throw ValidationException::withMessages([
                $config['column'] => ['El archivo ' . $config['label'] . ' tiene una extensión no permitida.'],
            ]);
        }

        $mimeType = (string) $file->getMimeType();

        if ($mimeType === '' || ! in_array($mimeType, $config['mime_types'], true)) {
            throw ValidationException::withMessages([
                $config['column'] => ['El archivo ' . $config['label'] . ' no tiene un MIME permitido.'],
            ]);
        }

        $directory = $this->storageDirectory();

        if (! is_dir($directory) && ! File::makeDirectory($directory, 0775, true) && ! is_dir($directory)) {
            throw ValidationException::withMessages([
                $config['column'] => ['No se pudo preparar la carpeta de uploads para ' . $config['label'] . '.'],
            ]);
        }

        $normalizer = app(ApiProductNormalizer::class);
        $prefixSlug = $normalizer->slugify((string) $config['prefix']) ?: 'archivo';
        $baseSlug = $normalizer->slugify(pathinfo((string) $file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'adjunto';
        $fileName = $prefixSlug . '_' . $baseSlug . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $extension;

        $file->move($directory, $fileName);

        if ($currentPath !== '') {
            $this->deleteStoredPath($currentPath);
        }

        return rtrim($this->pathPrefix(), '/') . '/' . $fileName;
    }

    private function storageDirectory(): string
    {
        return (string) config('api_blog.storage_path');
    }

    private function pathPrefix(): string
    {
        return (string) config('api_blog.path_prefix', 'API_Blog');
    }

    private function isExistingFile(string $path): bool
    {
        return $path !== '' && is_file($path);
    }

    /** @return list<string> */
    private function candidateStorageDirectories(): array
    {
        $directories = [];

        $configured = rtrim($this->storageDirectory(), DIRECTORY_SEPARATOR);

        if ($configured !== '') {
            $directories[] = $configured;
        }

        $legacy = $this->legacyStorageDirectory();

        if ($legacy !== null && ! in_array($legacy, $directories, true)) {
            $directories[] = $legacy;
        }

        return $directories;
    }

    private function legacyStorageDirectory(): ?string
    {
        $legacyFromEnv = trim((string) env('UPLOAD_API_BLOG_LEGACY_PATH', ''));

        if ($legacyFromEnv !== '') {
            return rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $legacyFromEnv), DIRECTORY_SEPARATOR);
        }

        // Misma convención que el PHP legacy: {dominio}/storage/API_Blog (fuera de public_html).
        $domainRoot = dirname(dirname(base_path()));

        if ($domainRoot === '' || $domainRoot === base_path()) {
            return null;
        }

        return $domainRoot
            . DIRECTORY_SEPARATOR . 'storage'
            . DIRECTORY_SEPARATOR . basename(str_replace('\\', '/', $this->pathPrefix()));
    }
}
