<?php

namespace App\Services\Projects;

use App\Services\ApiProduct\ApiProductNormalizer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class ProjectAttachmentStorageService
{
    private const MAX_BYTES = 10 * 1024 * 1024;

    /** @var list<string> */
    private const EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf'];

    /** @var list<string> */
    private const MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'application/pdf',
    ];

    public function storeUploadedFile(UploadedFile $file): array
    {
        if (! $file->isValid()) {
            throw ValidationException::withMessages([
                'file' => ['No se pudo subir el archivo.'],
            ]);
        }

        $fileSize = (int) $file->getSize();

        if ($fileSize <= 0 || $fileSize > self::MAX_BYTES) {
            throw ValidationException::withMessages([
                'file' => ['El archivo supera el tamaño máximo permitido (10 MB).'],
            ]);
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());

        if (! in_array($extension, self::EXTENSIONS, true)) {
            throw ValidationException::withMessages([
                'file' => ['Solo se permiten imágenes (jpg, png, webp, gif) o PDF.'],
            ]);
        }

        $mimeType = (string) $file->getMimeType();

        if ($mimeType === '' || ! in_array($mimeType, self::MIME_TYPES, true)) {
            throw ValidationException::withMessages([
                'file' => ['El tipo de archivo no está permitido.'],
            ]);
        }

        $directory = $this->storageDirectory();

        if (! is_dir($directory) && ! File::makeDirectory($directory, 0775, true) && ! is_dir($directory)) {
            throw ValidationException::withMessages([
                'file' => ['No se pudo preparar la carpeta de uploads.'],
            ]);
        }

        $originalName = (string) $file->getClientOriginalName();
        $normalizer = app(ApiProductNormalizer::class);
        $baseSlug = $normalizer->slugify(pathinfo($originalName, PATHINFO_FILENAME)) ?: 'adjunto';
        $fileName = 'project_attachment_'.$baseSlug.'_'.date('Ymd_His').'_'.bin2hex(random_bytes(6)).'.'.$extension;

        $file->move($directory, $fileName);

        return [
            'file_path' => rtrim($this->pathPrefix(), '/').'/'.$fileName,
            'original_name' => $originalName,
            'mime_type' => $mimeType,
            'size_bytes' => $fileSize,
        ];
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
        $directory = $this->storageDirectory();
        $candidate = rtrim($directory, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$baseName;

        if ($this->isExistingFile($candidate)) {
            return $candidate;
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

    private function storageDirectory(): string
    {
        return (string) config('uploads.project_attachment.storage_path');
    }

    private function pathPrefix(): string
    {
        return (string) config('uploads.project_attachment.path_prefix', 'project-attachments');
    }

    private function isExistingFile(string $path): bool
    {
        return $path !== '' && is_file($path);
    }
}
