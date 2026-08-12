<?php

namespace App\Services\Admin;

use App\Models\UserAuth;
use App\Support\RoleLabels;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminFinanzasService
{
    /** @return list<string> */
    public function allowedRoles(): array
    {
        return ['impulsa_emprendedor', 'impulsa_cliente'];
    }

    public function listUsers(?string $q, int $perPage = 15): LengthAwarePaginator
    {
        $query = UserAuth::query()
            ->with('info')
            ->whereIn('rol', $this->allowedRoles())
            ->orderByDesc('id');

        $search = trim((string) $q);

        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('correo', 'like', $like)
                    ->orWhereHas('info', function ($info) use ($like): void {
                        $info
                            ->where('nombre', 'like', $like)
                            ->orWhere('apellido', 'like', $like)
                            ->orWhere('apodo', 'like', $like);
                    });
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function resolveUser(int $userId): UserAuth
    {
        $user = UserAuth::query()
            ->with('info')
            ->whereIn('rol', $this->allowedRoles())
            ->find($userId);

        if ($user === null) {
            throw new NotFoundHttpException('No encontramos un usuario con módulo de finanzas.');
        }

        return $user;
    }

    /** @return array<string, mixed> */
    public function serializeUser(UserAuth $user): array
    {
        $info = $user->info;
        $nombre = trim((string) ($info?->nombre ?? '') . ' ' . (string) ($info?->apellido ?? ''));

        if ($nombre === '') {
            $nombre = trim((string) ($info?->apodo ?? ''));
        }

        return [
            'id' => (int) $user->id,
            'correo' => (string) $user->correo,
            'rol' => (string) $user->rol,
            'rol_label' => RoleLabels::labelFor((string) $user->rol),
            'nombre' => $nombre !== '' ? $nombre : (string) $user->correo,
        ];
    }
}
