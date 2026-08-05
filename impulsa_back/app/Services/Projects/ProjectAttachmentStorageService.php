<?php

namespace App\Services\Projects;

use App\Services\ApiProduct\ApiProductNormalizer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

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

        $mimeType = $this->resolveMimeType($file, $extension);
        $directory = $this->ensureStorageDirectory();
        $originalName = (string) $file->getClientOriginalName();
        $baseSlug = $this->slugify(pathinfo($originalName, PATHINFO_FILENAME)) ?: 'adjunto';
        $fileName = 'project_attachment_'.$baseSlug.'_'.date('Ymd_His').'_'.bin2hex(random_bytes(6)).'.'.$extension;
        $absoluteTarget = rtrim($directory, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$fileName;

        try {
            if (! $file->move($directory, $fileName) && ! is_file($absoluteTarget)) {
                throw ValidationException::withMessages([
                    'file' => ['No se pudo guardar el archivo en el servidor.'],
                ]);
            }
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'file' => ['No se pudo guardar el archivo en el servidor. Verificá permisos de la carpeta de uploads.'],
            ]);
        }

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

    private function resolveMimeType(UploadedFile $file, string $extension): string
    {
        $mimeType = trim((string) ($file->getMimeType() ?: $file->getClientMimeType()));

        if (in_array($mimeType, self::MIME_TYPES, true)) {
            return $mimeType;
        }

        if ($mimeType === '' || $mimeType === 'application/octet-stream') {
            return match ($extension) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'webp' => 'image/webp',
                'gif' => 'image/gif',
                'pdf' => 'application/pdf',
                default => $mimeType,
            };
        }

        throw ValidationException::withMessages([
            'file' => ['El tipo de archivo no está permitido.'],
        ]);
    }

    private function ensureStorageDirectory(): string
    {
        $directory = $this->storageDirectory();

        if ($directory === '') {
            throw ValidationException::withMessages([
                'file' => ['No está configurada la carpeta de uploads de adjuntos.'],
            ]);
        }

        try {
            if (! is_dir($directory) && ! File::makeDirectory($directory, 0775, true) && ! is_dir($directory)) {
                throw ValidationException::withMessages([
                    'file' => ['No se pudo preparar la carpeta de uploads.'],
                ]);
            }
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'file' => ['No se pudo preparar la carpeta de uploads: '.$directory],
            ]);
        }

        if (! is_writable($directory)) {
            throw ValidationException::withMessages([
                'file' => ['La carpeta de uploads no tiene permisos de escritura.'],
            ]);
        }

        return $directory;
    }

    private function slugify(string $text): string
    {
        try {
            $slug = app(ApiProductNormalizer::class)->slugify($text);

            if (is_string($slug) && $slug !== '') {
                return $slug;
            }
        } catch (Throwable) {
            // fallback below
        }

        return (string) (Str::slug($text) ?: 'adjunto');
    }

    private function storageDirectory(): string
    {
        $configured = config('uploads.project_attachment.storage_path');

        if (is_string($configured) && trim($configured) !== '') {
            return $configured;
        }

        return storage_path('app/project-attachments');
    }

    private function pathPrefix(): string
    {
        $configured = config('uploads.project_attachment.path_prefix');

        if (is_string($configured) && trim($configured) !== '') {
            return $configured;
        }

        return 'project-attachments';
    }

    private function isExistingFile(string $path): bool
    {
        return $path !== '' && is_file($path);
    }
}
