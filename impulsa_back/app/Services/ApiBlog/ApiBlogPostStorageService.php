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

        $baseName = basename(str_replace('\\', '/', $storedPath));
        $candidate = rtrim($this->storageDirectory(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $baseName;

        return is_file($candidate) ? $candidate : null;
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
}
