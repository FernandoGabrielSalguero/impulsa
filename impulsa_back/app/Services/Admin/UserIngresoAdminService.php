<?php

namespace App\Services\Admin;

use App\Support\RoleLabels;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserIngresoAdminService
{
    public function list(
        ?string $nombreUsuario,
        ?string $rol,
        ?string $fecha,
        int $perPage = 20,
    ): LengthAwarePaginator {
        return $this->baseQuery($nombreUsuario, $rol, $fecha)
            ->orderByDesc('ui.fecha_ingreso')
            ->orderByDesc('ui.hora_ingreso')
            ->orderByDesc('ui.id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /** @return list<array{value: string, label: string}> */
    public function roleOptions(): array
    {
        $roles = DB::table('user_ingresos')
            ->whereNotNull('rol')
            ->where('rol', '!=', '')
            ->distinct()
            ->orderBy('rol')
            ->pluck('rol')
            ->map(static fn ($value): string => (string) $value)
            ->all();

        return array_map(
            static fn (string $role): array => [
                'value' => $role,
                'label' => RoleLabels::labelFor($role),
            ],
            $roles,
        );
    }

    private function baseQuery(?string $nombreUsuario, ?string $rol, ?string $fecha)
    {
        $query = DB::table('user_ingresos as ui')
            ->leftJoin('user_auth as ua', 'ua.id', '=', 'ui.user_auth_id')
            ->select([
                'ui.id',
                'ui.user_auth_id',
                'ui.nombre_usuario',
                'ui.rol',
                'ui.fecha_ingreso',
                'ui.hora_ingreso',
                'ui.created_at',
                'ua.correo as usuario_correo',
            ]);

        $nombreFilter = trim((string) $nombreUsuario);
        if ($nombreFilter !== '') {
            $like = '%' . $nombreFilter . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('ui.nombre_usuario', 'like', $like)
                    ->orWhere('ua.correo', 'like', $like);
            });
        }

        $rolFilter = trim((string) $rol);
        if ($rolFilter !== '' && $rolFilter !== '__all__') {
            $query->where('ui.rol', $rolFilter);
        }

        $fechaFilter = trim((string) $fecha);
        if ($fechaFilter !== '') {
            $query->whereDate('ui.fecha_ingreso', $fechaFilter);
        }

        return $query;
    }
}
