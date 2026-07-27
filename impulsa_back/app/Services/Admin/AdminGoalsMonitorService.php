<?php

namespace App\Services\Admin;

use App\Models\UserGoal;
use App\Models\UserGoalObjective;
use App\Services\Goals\UserGoalsService;
use App\Support\GoalLabels;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminGoalsMonitorService
{
    /** @var list<string> */
    private const MONITORED_ROLES = ['impulsa_emprendedor', 'impulsa_cliente'];

    public function __construct(
        private readonly UserGoalsService $goalsService,
    ) {}

    /** @return array{statuses: list<array{value: string, label: string}>, roles: list<array{value: string, label: string}>} */
    public function options(): array
    {
        return [
            'statuses' => GoalLabels::statusOptions(),
            'roles' => [
                ['value' => 'impulsa_emprendedor', 'label' => 'Emprendedor'],
                ['value' => 'impulsa_cliente', 'label' => 'Cliente'],
            ],
        ];
    }

    /** @return array<string, int> */
    public function summary(): array
    {
        $goalsQuery = $this->baseGoalsQuery();
        $today = now()->toDateString();

        $objectivesQuery = UserGoalObjective::query()
            ->whereHas('goal.user', function ($builder): void {
                $builder->whereIn('rol', self::MONITORED_ROLES);
            });

        return [
            'total_goals' => (clone $goalsQuery)->count(),
            'total_objectives' => (clone $objectivesQuery)->count(),
            'goals_completed' => (clone $goalsQuery)->where('status', 'completed')->count(),
            'goals_in_progress' => (clone $goalsQuery)->where('status', 'in_progress')->count(),
            'goals_overdue' => (clone $goalsQuery)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->whereNotNull('due_date')
                ->whereDate('due_date', '<', $today)
                ->count(),
            'objectives_completed' => (clone $objectivesQuery)->where('status', 'completed')->count(),
            'users_with_goals' => (int) (clone $goalsQuery)->distinct('user_auth_id')->count('user_auth_id'),
        ];
    }

    /**
     * @param  array{q?: string|null, status?: string|null, role?: string|null, overdue?: bool|string|null, user_id?: int|string|null}  $filters
     */
    public function listGoals(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->baseGoalsQuery()
            ->with(['user.info'])
            ->withCount([
                'objectives as objectives_total',
                'objectives as objectives_completed' => static fn ($builder) => $builder->where('status', 'completed'),
            ])
            ->orderByDesc('updated_at')
            ->orderByDesc('id');

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    /** @return array<string, mixed> */
    public function getGoalDetail(int $goalId): array
    {
        $goal = $this->baseGoalsQuery()
            ->with(['user.info', 'objectives'])
            ->where('id', $goalId)
            ->first();

        if ($goal === null) {
            throw new NotFoundHttpException('Meta no encontrada.');
        }

        return [
            'owner' => $this->serializeOwner($goal),
            'goal' => $this->goalsService->goalListItem($goal),
            'objectives' => $goal->objectives
                ->map(fn (UserGoalObjective $objective): array => $this->goalsService->objectiveItem($objective, $goal))
                ->values()
                ->all(),
            'summary' => $this->goalsService->goalSummary($goal),
        ];
    }

    /** @param  array{q?: string|null, status?: string|null, role?: string|null, overdue?: bool|string|null, user_id?: int|string|null}  $filters
     */
    private function applyFilters(\Illuminate\Database\Eloquent\Builder $query, array $filters): void
    {
        if (! empty($filters['status'])) {
            $query->where('status', (string) $filters['status']);
        }

        if (! empty($filters['role']) && in_array($filters['role'], self::MONITORED_ROLES, true)) {
            $query->whereHas('user', static fn ($builder) => $builder->where('rol', $filters['role']));
        }

        if (! empty($filters['user_id'])) {
            $query->where('user_auth_id', (int) $filters['user_id']);
        }

        if ($this->filterTruthy($filters['overdue'] ?? null)) {
            $today = now()->toDateString();
            $query->whereNotIn('status', ['completed', 'cancelled'])
                ->whereNotNull('due_date')
                ->whereDate('due_date', '<', $today);
        }

        if (! empty($filters['q'])) {
            $term = '%' . trim((string) $filters['q']) . '%';
            $query->where(function ($builder) use ($term): void {
                $builder->where('title', 'like', $term)
                    ->orWhereHas('user', function ($userQuery) use ($term): void {
                        $userQuery->where('correo', 'like', $term)
                            ->orWhereHas('info', function ($infoQuery) use ($term): void {
                                $infoQuery->where('nombre', 'like', $term)
                                    ->orWhere('apellido', 'like', $term)
                                    ->orWhere('apodo', 'like', $term);
                            });
                    });
            });
        }
    }

    private function baseGoalsQuery()
    {
        return UserGoal::query()->whereHas('user', static function ($builder): void {
            $builder->whereIn('rol', self::MONITORED_ROLES);
        });
    }

    /** @return array<string, mixed> */
    public function serializeListRow(UserGoal $goal): array
    {
        $goal->loadMissing('user.info');

        return [
            'id' => $goal->id,
            'goal' => $this->goalsService->goalListItem($goal),
            'owner' => $this->serializeOwner($goal),
            'objectives_total' => (int) ($goal->objectives_total ?? $goal->objectives()->count()),
            'objectives_completed' => (int) ($goal->objectives_completed ?? $goal->objectives()->where('status', 'completed')->count()),
        ];
    }

    /** @return array{id: int, name: string, email: string, role: string, role_label: string} */
    private function serializeOwner(UserGoal $goal): array
    {
        $user = $goal->user;
        $info = $user?->info;
        $name = trim(((string) ($info?->nombre ?? '')) . ' ' . ((string) ($info?->apellido ?? '')));

        if ($name === '') {
            $name = trim((string) ($info?->apodo ?? ''));
        }

        if ($name === '') {
            $name = (string) ($user?->correo ?? 'Usuario');
        }

        $role = (string) ($user?->rol ?? '');

        return [
            'id' => (int) ($user?->id ?? 0),
            'name' => $name,
            'email' => (string) ($user?->correo ?? ''),
            'role' => $role,
            'role_label' => match ($role) {
                'impulsa_emprendedor' => 'Emprendedor',
                'impulsa_cliente' => 'Cliente',
                default => $role,
            },
        ];
    }

    private function filterTruthy(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $normalized = strtolower(trim((string) $value));

        return in_array($normalized, ['1', 'true', 'yes', 'on'], true);
    }
}
