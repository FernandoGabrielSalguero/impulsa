<?php

namespace App\Services\Admin;

use App\Models\AdminTarea;
use App\Models\UserAuth;
use App\Support\TaskLabels;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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

    /**
     * @return array{
     *     granularity: string,
     *     scope: string,
     *     from: string,
     *     to: string,
     *     snapshot: array<string, mixed>,
     *     series: list<array<string, mixed>>,
     *     slowest: list<array<string, mixed>>,
     *     overdue: list<array<string, mixed>>
     * }
     */
    public function metrics(string $granularity, string $scope, int $userId): array
    {
        $today = now()->startOfDay();
        [$from, $to] = $this->metricsWindow($granularity, $today);

        $query = AdminTarea::query();

        if ($scope === 'mine') {
            $query->where('responsable_user_id', $userId);
        }

        $tasks = $query->get([
            'id',
            'nombre_tarea',
            'fecha_entrega',
            'prioridad_defcon',
            'estado',
            'completed_at',
            'created_at',
        ]);

        $completedInWindow = $tasks->filter(
            fn (AdminTarea $task): bool => $this->isCompletedInWindow($task, $from, $to),
        );
        $openTasks = $tasks->filter(fn (AdminTarea $task): bool => $this->isOpen($task->estado));
        $overdueTasks = $openTasks->filter(function (AdminTarea $task) use ($today): bool {
            return $this->dueDate($task)->lt($today);
        });

        $completedWeight = $this->weightedCount($completedInWindow);
        $openDueOrOverdue = $openTasks->filter(function (AdminTarea $task) use ($from, $to, $today): bool {
            $due = $this->dueDate($task);

            return $due->lt($today) || $due->between($from->copy()->startOfDay(), $to->copy()->startOfDay());
        });
        $cumplimientoDenom = $completedWeight + $this->weightedCount($openDueOrOverdue);
        $cumplimiento = $cumplimientoDenom > 0
            ? ($completedWeight / $cumplimientoDenom) * 100
            : 0.0;

        $onTime = $completedInWindow->filter(function (AdminTarea $task): bool {
            if ($task->completed_at === null) {
                return false;
            }

            return $task->completed_at->copy()->startOfDay()->lte($this->dueDate($task));
        });
        $puntualidad = $completedWeight > 0
            ? ($this->weightedCount($onTime) / $completedWeight) * 100
            : 0.0;

        $openWeight = $this->weightedCount($openTasks);
        $overdueWeight = $this->weightedCount($overdueTasks);
        $atrasosScore = $openWeight > 0
            ? (1 - ($overdueWeight / $openWeight)) * 100
            : 100.0;

        $cicloValues = $completedInWindow
            ->map(fn (AdminTarea $task): int => $this->cycleDays($task))
            ->values();
        $cicloPromedio = $cicloValues->count() > 0
            ? round($cicloValues->avg() ?? 0, 1)
            : 0.0;

        $productividad = (int) round(
            ($cumplimiento * 0.50) + ($puntualidad * 0.35) + ($atrasosScore * 0.15),
        );

        $buckets = $this->metricsBuckets($granularity, $from, $to);
        $series = [];

        foreach ($buckets as $bucket) {
            $series[] = [
                'label' => $bucket['label'],
                'completadas' => $completedInWindow
                    ->filter(fn (AdminTarea $task): bool => $this->completedInBucket($task, $bucket))
                    ->count(),
                'pendientes' => $openTasks
                    ->filter(function (AdminTarea $task) use ($bucket, $today): bool {
                        $due = $this->dueDate($task);

                        return $due->gte($today) && $due->between($bucket['start']->copy()->startOfDay(), $bucket['end']->copy()->startOfDay());
                    })
                    ->count(),
                'atrasadas' => $openTasks
                    ->filter(function (AdminTarea $task) use ($bucket, $today): bool {
                        $due = $this->dueDate($task);

                        return $due->lt($today) && $due->between($bucket['start']->copy()->startOfDay(), $bucket['end']->copy()->startOfDay());
                    })
                    ->count(),
            ];
        }

        $slowest = $completedInWindow
            ->sortByDesc(fn (AdminTarea $task): int => $this->cycleDays($task))
            ->take(5)
            ->values()
            ->map(fn (AdminTarea $task): array => [
                'id' => (int) $task->id,
                'nombre_tarea' => $task->nombre_tarea,
                'ciclo_dias' => $this->cycleDays($task),
                'completed_at' => $task->completed_at?->toISOString(),
                'fecha_entrega' => $this->dueDate($task)->toDateString(),
                'prioridad_defcon' => (int) $task->prioridad_defcon,
                'prioridad_label' => TaskLabels::defconLabel((int) $task->prioridad_defcon),
            ])
            ->all();

        $overdue = $overdueTasks
            ->sortByDesc(fn (AdminTarea $task): int => $this->daysOverdue($task, $today))
            ->take(5)
            ->values()
            ->map(fn (AdminTarea $task): array => [
                'id' => (int) $task->id,
                'nombre_tarea' => $task->nombre_tarea,
                'dias_atraso' => $this->daysOverdue($task, $today),
                'fecha_entrega' => $this->dueDate($task)->toDateString(),
                'estado' => $task->estado,
                'estado_label' => TaskLabels::statusLabel($task->estado),
                'prioridad_defcon' => (int) $task->prioridad_defcon,
                'prioridad_label' => TaskLabels::defconLabel((int) $task->prioridad_defcon),
            ])
            ->all();

        return [
            'granularity' => $granularity,
            'scope' => $scope,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'snapshot' => [
                'completadas' => $completedInWindow->count(),
                'pendientes' => $openTasks->where('estado', 'pendiente')->count(),
                'en_progreso' => $openTasks->where('estado', 'en_progreso')->count(),
                'atrasadas' => $overdueTasks->count(),
                'cumplimiento' => $this->roundScore($cumplimiento),
                'puntualidad' => $this->roundScore($puntualidad),
                'ciclo_promedio_dias' => $cicloPromedio,
                'productividad' => max(0, min(100, $productividad)),
                'productividad_breakdown' => [
                    'cumplimiento' => $this->roundScore($cumplimiento),
                    'puntualidad' => $this->roundScore($puntualidad),
                    'atrasos' => $this->roundScore($atrasosScore),
                ],
            ],
            'series' => $series,
            'slowest' => $slowest,
            'overdue' => $overdue,
        ];
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

    /** @return array{0: Carbon, 1: Carbon} */
    private function metricsWindow(string $granularity, Carbon $today): array
    {
        return match ($granularity) {
            'day' => [$today->copy()->subDays(13), $today->copy()->endOfDay()],
            'month' => [$today->copy()->startOfMonth()->subMonths(5), $today->copy()->endOfMonth()],
            default => [
                $today->copy()->startOfWeek(Carbon::MONDAY)->subWeeks(7),
                $today->copy()->endOfWeek(Carbon::SUNDAY),
            ],
        };
    }

    /**
     * @return list<array{label: string, start: Carbon, end: Carbon}>
     */
    private function metricsBuckets(string $granularity, Carbon $from, Carbon $to): array
    {
        $buckets = [];

        if ($granularity === 'day') {
            $period = CarbonPeriod::create($from->copy()->startOfDay(), '1 day', $to->copy()->startOfDay());

            foreach ($period as $date) {
                $buckets[] = [
                    'label' => $date->format('d/m'),
                    'start' => $date->copy()->startOfDay(),
                    'end' => $date->copy()->endOfDay(),
                ];
            }

            return $buckets;
        }

        if ($granularity === 'month') {
            $months = [1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'];
            $cursor = $from->copy()->startOfMonth();
            $last = $to->copy()->startOfMonth();

            while ($cursor->lte($last)) {
                $buckets[] = [
                    'label' => $months[(int) $cursor->month] . ' ' . $cursor->year,
                    'start' => $cursor->copy()->startOfMonth(),
                    'end' => $cursor->copy()->endOfMonth(),
                ];
                $cursor->addMonth();
            }

            return $buckets;
        }

        $cursor = $from->copy()->startOfWeek(Carbon::MONDAY);
        $last = $to->copy()->startOfWeek(Carbon::MONDAY);

        while ($cursor->lte($last)) {
            $weekEnd = $cursor->copy()->endOfWeek(Carbon::SUNDAY);
            $buckets[] = [
                'label' => $cursor->format('d/m') . '–' . $weekEnd->format('d/m'),
                'start' => $cursor->copy()->startOfWeek(Carbon::MONDAY),
                'end' => $weekEnd,
            ];
            $cursor->addWeek();
        }

        return $buckets;
    }

    private function isOpen(?string $estado): bool
    {
        return in_array($estado, ['pendiente', 'en_progreso'], true);
    }

    private function isCompletedInWindow(AdminTarea $task, Carbon $from, Carbon $to): bool
    {
        return $task->estado === 'completada'
            && $task->completed_at !== null
            && $task->completed_at->between($from, $to);
    }

    /** @param array{start: Carbon, end: Carbon} $bucket */
    private function completedInBucket(AdminTarea $task, array $bucket): bool
    {
        return $task->completed_at !== null
            && $task->completed_at->between($bucket['start'], $bucket['end']);
    }

    private function dueDate(AdminTarea $task): Carbon
    {
        $due = $task->fecha_entrega;

        if ($due instanceof Carbon) {
            return $due->copy()->startOfDay();
        }

        return Carbon::parse((string) $due)->startOfDay();
    }

    private function defconWeight(int $level): float
    {
        if ($level <= 2) {
            return 1.5;
        }

        if ($level === 5) {
            return 0.75;
        }

        return 1.0;
    }

    /** @param Collection<int, AdminTarea> $tasks */
    private function weightedCount(Collection $tasks): float
    {
        return (float) $tasks->sum(
            fn (AdminTarea $task): float => $this->defconWeight((int) $task->prioridad_defcon),
        );
    }

    private function cycleDays(AdminTarea $task): int
    {
        if ($task->created_at === null || $task->completed_at === null) {
            return 0;
        }

        return (int) abs($task->created_at->copy()->startOfDay()->diffInDays($task->completed_at->copy()->startOfDay()));
    }

    private function daysOverdue(AdminTarea $task, Carbon $today): int
    {
        return (int) abs($this->dueDate($task)->diffInDays($today));
    }

    private function roundScore(float $value): float
    {
        return round(max(0, min(100, $value)), 1);
    }
}
