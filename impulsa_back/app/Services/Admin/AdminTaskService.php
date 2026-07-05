<?php

namespace App\Services\Admin;

use App\Models\AdminTarea;
use App\Models\UserAuth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminTaskService
{
    /** @return array{data: LengthAwarePaginator, summary: array<string, int>} */
    public function list(?string $q, ?string $estado, int $perPage = 20): array
    {
        $query = AdminTarea::query()
            ->from('admin_tareas as at')
            ->join('user_auth as ur', 'ur.id', '=', 'at.responsable_user_id')
            ->join('user_auth as ucr', 'ucr.id', '=', 'at.created_by_user_id')
            ->leftJoin('user_info as uir', 'uir.user_auth_id', '=', 'ur.id')
            ->leftJoin('user_info as uic', 'uic.user_auth_id', '=', 'ucr.id')
            ->select([
                'at.*',
                'ur.correo as responsable_correo',
                'ucr.correo as creador_correo',
                'uir.nombre as responsable_nombre',
                'uir.apellido as responsable_apellido',
                'uir.apodo as responsable_apodo',
                'uic.nombre as creador_nombre',
                'uic.apellido as creador_apellido',
                'uic.apodo as creador_apodo',
            ])
            ->orderByDesc('at.updated_at')
            ->orderByDesc('at.id');

        $search = trim((string) $q);

        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('at.nombre_tarea', 'like', $like)
                    ->orWhere('at.descripcion', 'like', $like)
                    ->orWhere('at.reporta_a', 'like', $like)
                    ->orWhere('ur.correo', 'like', $like)
                    ->orWhereRaw('CAST(at.id AS CHAR) LIKE ?', [$like]);
            });
        }

        $estadoFilter = trim((string) $estado);

        if ($estadoFilter !== '' && $estadoFilter !== '__all__') {
            $query->where('at.estado', $estadoFilter);
        }

        return [
            'data' => $query->paginate($perPage)->withQueryString(),
            'summary' => $this->summaryCounts(),
        ];
    }

    public function find(int $taskId): AdminTarea
    {
        $task = $this->baseDetailQuery()->where('at.id', $taskId)->first();

        if ($task === null) {
            throw ValidationException::withMessages([
                'task' => ['La tarea no existe.'],
            ]);
        }

        return $task;
    }

    /** @param array<string, mixed> $data */
    public function create(array $data, int $createdByUserId): AdminTarea
    {
        $this->assertAssigneeExists((int) $data['responsable_user_id']);

        $estado = $data['estado'];

        $task = AdminTarea::query()->create([
            'nombre_tarea' => trim((string) $data['nombre_tarea']),
            'responsable_user_id' => (int) $data['responsable_user_id'],
            'descripcion' => trim((string) $data['descripcion']),
            'fecha_entrega' => $data['fecha_entrega'],
            'prioridad_defcon' => (int) $data['prioridad_defcon'],
            'reporta_a' => trim((string) $data['reporta_a']),
            'estado' => $estado,
            'created_by_user_id' => $createdByUserId,
            'completed_at' => $estado === 'completada' ? now() : null,
        ]);

        return $this->find((int) $task->id);
    }

    /** @param array<string, mixed> $data */
    public function update(AdminTarea $task, array $data): AdminTarea
    {
        $this->assertAssigneeExists((int) $data['responsable_user_id']);

        $estado = $data['estado'];
        $completedAt = null;

        if ($estado === 'completada') {
            $completedAt = $task->completed_at ?? now();
        }

        $task->update([
            'nombre_tarea' => trim((string) $data['nombre_tarea']),
            'responsable_user_id' => (int) $data['responsable_user_id'],
            'descripcion' => trim((string) $data['descripcion']),
            'fecha_entrega' => $data['fecha_entrega'],
            'prioridad_defcon' => (int) $data['prioridad_defcon'],
            'reporta_a' => trim((string) $data['reporta_a']),
            'estado' => $estado,
            'completed_at' => $completedAt,
        ]);

        return $this->find((int) $task->id);
    }

    public function delete(AdminTarea $task): void
    {
        $task->delete();
    }

    /** @return Collection<int, array<string, mixed>> */
    public function listAssignees(): Collection
    {
        return UserAuth::query()
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'user_auth.id')
            ->orderByRaw(
                'COALESCE(NULLIF(TRIM(CONCAT_WS(" ", ui.nombre, ui.apellido)), ""), NULLIF(TRIM(ui.apodo), ""), user_auth.correo) ASC'
            )
            ->get([
                'user_auth.id',
                'user_auth.correo',
                'user_auth.rol',
                'ui.nombre',
                'ui.apellido',
                'ui.apodo',
            ])
            ->map(static function (UserAuth $user): array {
                $name = trim((string) (($user->nombre ?? '') . ' ' . ($user->apellido ?? '')));

                if ($name === '') {
                    $name = trim((string) ($user->apodo ?? ''));
                }

                return [
                    'id' => $user->id,
                    'correo' => $user->correo,
                    'rol' => $user->rol,
                    'nombre' => $name !== '' ? $name : null,
                    'label' => $name !== '' ? $name . ' (' . $user->correo . ')' : $user->correo,
                ];
            });
    }

    /** @return array<string, int> */
    private function summaryCounts(): array
    {
        $rows = DB::table('admin_tareas')
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $summary = [
            'total' => (int) DB::table('admin_tareas')->count(),
            'pendiente' => (int) ($rows['pendiente'] ?? 0),
            'en_progreso' => (int) ($rows['en_progreso'] ?? 0),
            'completada' => (int) ($rows['completada'] ?? 0),
            'cancelada' => (int) ($rows['cancelada'] ?? 0),
        ];

        return $summary;
    }

    private function assertAssigneeExists(int $userId): void
    {
        if (! UserAuth::query()->whereKey($userId)->exists()) {
            throw ValidationException::withMessages([
                'responsable_user_id' => ['El responsable seleccionado no es válido.'],
            ]);
        }
    }

    private function baseDetailQuery()
    {
        return AdminTarea::query()
            ->from('admin_tareas as at')
            ->join('user_auth as ur', 'ur.id', '=', 'at.responsable_user_id')
            ->join('user_auth as ucr', 'ucr.id', '=', 'at.created_by_user_id')
            ->leftJoin('user_info as uir', 'uir.user_auth_id', '=', 'ur.id')
            ->leftJoin('user_info as uic', 'uic.user_auth_id', '=', 'ucr.id')
            ->select([
                'at.*',
                'ur.correo as responsable_correo',
                'ucr.correo as creador_correo',
                'uir.nombre as responsable_nombre',
                'uir.apellido as responsable_apellido',
                'uir.apodo as responsable_apodo',
                'uic.nombre as creador_nombre',
                'uic.apellido as creador_apellido',
                'uic.apodo as creador_apodo',
            ]);
    }
}
