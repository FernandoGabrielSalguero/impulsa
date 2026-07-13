<?php

namespace App\Services\Profile;

use App\Models\UserAuth;
use App\Models\UserContacto;
use App\Models\UserInfo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProfileService
{
    public function __construct(
        private readonly UserAvatarStorageService $avatarStorage,
    ) {}

    public function show(UserAuth $user): UserAuth
    {
        return $user->loadMissing(['info', 'contacto']);
    }

    /** @param array<string, mixed> $data */
    public function update(UserAuth $user, array $data, ?UploadedFile $avatarFile = null): UserAuth
    {
        return DB::transaction(function () use ($user, $data, $avatarFile): UserAuth {
            $user->loadMissing(['info', 'contacto']);

            $avatarPath = trim((string) ($user->info?->avatar_path ?? ''));

            if ($avatarFile instanceof UploadedFile) {
                $avatarPath = $this->avatarStorage->store($user, $avatarFile);
            } elseif (filter_var($data['remove_avatar'] ?? false, FILTER_VALIDATE_BOOL)) {
                $this->avatarStorage->deleteStoredPath($avatarPath);
                $avatarPath = '';
            }

            UserInfo::query()->updateOrCreate(
                ['user_auth_id' => $user->id],
                [
                    'nombre' => filled($data['nombre'] ?? null) ? $data['nombre'] : null,
                    'apellido' => filled($data['apellido'] ?? null) ? $data['apellido'] : null,
                    'apodo' => filled($data['apodo'] ?? null) ? $data['apodo'] : null,
                    'fecha_nacimiento' => filled($data['fecha_nacimiento'] ?? null) ? $data['fecha_nacimiento'] : null,
                    'avatar_path' => $avatarPath !== '' ? $avatarPath : null,
                ],
            );

            $contactEmail = trim((string) ($data['correo_contacto'] ?? ''));

            if ($contactEmail === '') {
                $contactEmail = $user->correo;
            }

            UserContacto::query()->updateOrCreate(
                ['user_auth_id' => $user->id],
                [
                    'correo' => $contactEmail,
                    'check_correo' => filled($contactEmail),
                    'permison_correo' => (bool) ($data['permison_correo'] ?? true),
                    'whatsapp' => filled($data['whatsapp'] ?? null) ? $data['whatsapp'] : null,
                    'check_whatsapp' => filled($data['whatsapp'] ?? null),
                    'permison_whatsapp' => (bool) ($data['permison_whatsapp'] ?? true),
                ],
            );

            return $user->fresh(['info', 'contacto']);
        });
    }

    /** @return array{path: string, mime: string} */
    public function resolveAvatarFile(UserAuth $user): array
    {
        $user->loadMissing('info');
        $absolutePath = $this->avatarStorage->resolveAbsolutePath($user->info?->avatar_path);

        if ($absolutePath === null) {
            throw ValidationException::withMessages([
                'avatar' => ['No hay avatar cargado para este usuario.'],
            ]);
        }

        return [
            'path' => $absolutePath,
            'mime' => $this->avatarStorage->resolveMimeType($absolutePath),
        ];
    }
}
