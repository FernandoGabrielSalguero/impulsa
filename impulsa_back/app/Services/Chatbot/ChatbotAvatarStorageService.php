<?php

namespace App\Services\Chatbot;

use App\Models\Chatbot;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class ChatbotAvatarStorageService
{
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    private const IMAGE_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    private const MAX_BYTES = 2 * 1024 * 1024;

    public function store(Chatbot $chatbot, UploadedFile $file): string
    {
        if (! $file->isValid()) {
            throw ValidationException::withMessages([
                'avatar_file' => ['No se pudo subir la imagen del avatar.'],
            ]);
        }

        $fileSize = (int) $file->getSize();

        if ($fileSize <= 0 || $fileSize > self::MAX_BYTES) {
            throw ValidationException::withMessages([
                'avatar_file' => ['La imagen del avatar supera el tamaño permitido (2 MB).'],
            ]);
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());

        if (! in_array($extension, self::IMAGE_EXTENSIONS, true)) {
            throw ValidationException::withMessages([
                'avatar_file' => ['Formato no permitido. Usá JPG, PNG o WEBP.'],
            ]);
        }

        $mimeType = (string) $file->getMimeType();

        if ($mimeType === '' || ! in_array($mimeType, self::IMAGE_MIME_TYPES, true)) {
            throw ValidationException::withMessages([
                'avatar_file' => ['El archivo no es una imagen válida.'],
            ]);
        }

        $directory = $this->storageDirectory();

        if (! is_dir($directory) && ! File::makeDirectory($directory, 0775, true) && ! is_dir($directory)) {
            throw ValidationException::withMessages([
                'avatar_file' => ['No se pudo preparar la carpeta de avatares.'],
            ]);
        }

        $fileName = sprintf(
            'chatbot_%d_%s.%s',
            (int) $chatbot->id,
            date('Ymd_His') . '_' . bin2hex(random_bytes(4)),
            $extension,
        );

        $previousPath = trim((string) $chatbot->avatar_url);
        $file->move($directory, $fileName);

        if ($previousPath !== '' && $this->isManagedPath($previousPath)) {
            $this->deleteStoredPath($previousPath);
        }

        return $this->pathPrefix() . '/' . $fileName;
    }

    public function resolveAbsolutePath(?string $storedPath): ?string
    {
        $storedPath = trim((string) $storedPath);

        if ($storedPath === '' || ! $this->isManagedPath($storedPath)) {
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

    public function isManagedPath(?string $storedPath): bool
    {
        $storedPath = trim((string) $storedPath);

        return $storedPath !== '' && str_starts_with($storedPath, $this->pathPrefix() . '/');
    }

    private function storageDirectory(): string
    {
        return (string) config('uploads.chatbot_avatar.storage_path');
    }

    private function pathPrefix(): string
    {
        return (string) config('uploads.chatbot_avatar.path_prefix', 'chatbot-avatars');
    }
}
