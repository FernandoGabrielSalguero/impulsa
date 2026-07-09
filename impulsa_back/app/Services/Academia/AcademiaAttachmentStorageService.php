<?php

namespace App\Services\Academia;

use App\Services\ApiProduct\ApiProductNormalizer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class AcademiaAttachmentStorageService
{
    /** @return list<array{label: ?string, file_path: string, sort_order: int}> */
    public function storeUploadedFiles(array $files, int $startSortOrder = 1): array
    {
        $uploadedFiles = $files['attachment_files'] ?? $files['attachment_files[]'] ?? [];

        if ($uploadedFiles instanceof UploadedFile) {
            $uploadedFiles = [$uploadedFiles];
        }

        if (! is_array($uploadedFiles)) {
            return [];
        }

        $maxFiles = (int) config('academia.attachment.max_files', 10);

        if (count($uploadedFiles) > $maxFiles) {
            throw ValidationException::withMessages([
                'attachment_files' => ['Podés subir como máximo ' . $maxFiles . ' archivos adjuntos.'],
            ]);
        }

        $stored = [];
        $sortOrder = $startSortOrder;

        foreach ($uploadedFiles as $index => $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $stored[] = [
                'label' => $file->getClientOriginalName(),
                'file_path' => $this->storeUploadedFile($file),
                'sort_order' => $sortOrder,
            ];

            $sortOrder++;
        }

        return $stored;
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
        $candidate = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $baseName;

        if ($this->isExistingFile($candidate)) {
            return $candidate;
        }

        $configuredPrefix = rtrim($this->pathPrefix(), '/');
        $relativeCandidate = rtrim($directory, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . basename($configuredPrefix)
            . DIRECTORY_SEPARATOR
            . $baseName;

        if ($this->isExistingFile($relativeCandidate)) {
            return $relativeCandidate;
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

    private function storeUploadedFile(UploadedFile $file): string
    {
        if (! $file->isValid()) {
            throw ValidationException::withMessages([
                'attachment_files' => ['No se pudo subir uno de los archivos adjuntos.'],
            ]);
        }

        $config = (array) config('academia.attachment', []);
        $maxBytes = (int) ($config['max_bytes'] ?? 10 * 1024 * 1024);
        $extensions = (array) ($config['extensions'] ?? []);
        $mimeTypes = (array) ($config['mime_types'] ?? []);
        $fileSize = (int) $file->getSize();

        if ($fileSize <= 0 || $fileSize > $maxBytes) {
            throw ValidationException::withMessages([
                'attachment_files' => ['Uno de los archivos adjuntos supera el tamaño permitido.'],
            ]);
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());

        if (! in_array($extension, $extensions, true)) {
            throw ValidationException::withMessages([
                'attachment_files' => ['Uno de los archivos adjuntos tiene una extensión no permitida.'],
            ]);
        }

        $mimeType = (string) $file->getMimeType();

        if ($mimeType === '' || ! in_array($mimeType, $mimeTypes, true)) {
            throw ValidationException::withMessages([
                'attachment_files' => ['Uno de los archivos adjuntos no tiene un MIME permitido.'],
            ]);
        }

        $directory = $this->storageDirectory();

        if (! is_dir($directory) && ! File::makeDirectory($directory, 0775, true) && ! is_dir($directory)) {
            throw ValidationException::withMessages([
                'attachment_files' => ['No se pudo preparar la carpeta de uploads para adjuntos.'],
            ]);
        }

        $normalizer = app(ApiProductNormalizer::class);
        $baseSlug = $normalizer->slugify(pathinfo((string) $file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'adjunto';
        $fileName = 'academia_attachment_' . $baseSlug . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $extension;

        $file->move($directory, $fileName);

        return rtrim($this->pathPrefix(), '/') . '/' . $fileName;
    }

    private function storageDirectory(): string
    {
        return (string) config('academia.storage_path');
    }

    private function pathPrefix(): string
    {
        return (string) config('academia.path_prefix', 'Academia');
    }

    private function isExistingFile(string $path): bool
    {
        return $path !== '' && is_file($path);
    }
}
