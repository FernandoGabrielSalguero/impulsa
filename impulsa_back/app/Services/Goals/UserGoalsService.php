<?php

namespace App\Services\Goals;

use App\Http\Resources\UserGoalObjectiveResource;
use App\Http\Resources\UserGoalResource;
use App\Mail\GoalCompletedMail;
use App\Mail\GoalObjectiveCompletedMail;
use App\Mail\GoalReminderMail;
use App\Models\UserAuth;
use App\Models\UserGoal;
use App\Models\UserGoalObjective;
use App\Models\UserGoalReminderLog;
use App\Services\Mail\ImpulsaMailService;
use App\Services\Notifications\NotificationService;
use App\Support\GoalLabels;
use App\Support\ImpulsaFrontendUrl;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserGoalsService
{
    public function __construct(
        private readonly NotificationService $notificationService,
        private readonly ImpulsaMailService $mailService,
    ) {}

    /** @return array{statuses: list<array{value: string, label: string}>} */
    public function options(): array
    {
        return [
            'statuses' => GoalLabels::statusOptions(),
        ];
    }

    /**
     * @param  array{status?: string|null, q?: string|null, overdue?: bool|string|null}  $filters
     * @return list<array<string, mixed>>
     */
    public function listGoals(UserAuth $user, array $filters = []): array
    {
        $query = UserGoal::query()
            ->where('user_auth_id', $user->id)
            ->orderByDesc('updated_at')
            ->orderByDesc('id');

        if (! empty($filters['status'])) {
            $query->where('status', (string) $filters['status']);
        }

        if (! empty($filters['q'])) {
            $term = '%' . trim((string) $filters['q']) . '%';
            $query->where('title', 'like', $term);
        }

        if ($this->filterTruthy($filters['overdue'] ?? null)) {
            $today = now()->toDateString();
            $query->whereNotIn('status', ['completed', 'cancelled'])
                ->whereNotNull('due_date')
                ->whereDate('due_date', '<', $today);
        }

        return $query->get()
            ->map(fn (UserGoal $goal): array => $this->serializeGoalListItem($goal))
            ->values()
            ->all();
    }

    /** @return array<string, mixed> */
    public function getGoalDetail(UserAuth $user, int $goalId): array
    {
        $goal = $this->findOwnedGoal($user, $goalId);
        $goal->load('objectives');

        return [
            'goal' => $this->serializeGoalListItem($goal),
            'objectives' => $goal->objectives
                ->map(fn (UserGoalObjective $objective): array => $this->serializeObjective($objective, $goal))
                ->values()
                ->all(),
            'summary' => $this->buildSummary($goal),
        ];
    }

    /** @param array{title: string, description?: string|null, start_date?: string|null, due_date?: string|null} $payload */
    public function createGoal(UserAuth $user, array $payload): array
    {
        $goal = new UserGoal([
            'user_auth_id' => $user->id,
            'title' => trim($payload['title']),
            'description' => $this->nullableTrim($payload['description'] ?? null),
            'start_date' => $payload['start_date'] ?? null,
            'due_date' => $payload['due_date'] ?? null,
            'status' => 'pending',
            'progress_percent' => 0,
        ]);

        $this->validateGoalDates($goal);
        $goal->save();

        return $this->serializeGoalListItem($goal);
    }

    /** @param array<string, mixed> $payload */
    public function updateGoal(UserAuth $user, int $goalId, array $payload): array
    {
        $goal = $this->findOwnedGoal($user, $goalId);

        if (array_key_exists('title', $payload)) {
            $goal->title = trim((string) $payload['title']);
        }

        if (array_key_exists('description', $payload)) {
            $goal->description = $this->nullableTrim($payload['description']);
        }

        if (array_key_exists('start_date', $payload)) {
            $goal->start_date = $payload['start_date'];
        }

        if (array_key_exists('due_date', $payload)) {
            $goal->due_date = $payload['due_date'];
        }

        if (array_key_exists('status', $payload)) {
            $this->applyGoalStatus($goal, (string) $payload['status']);
        }

        $this->validateGoalDates($goal);
        $goal->save();

        return $this->serializeGoalListItem($goal->fresh());
    }

    public function deleteGoal(UserAuth $user, int $goalId): void
    {
        $goal = $this->findOwnedGoal($user, $goalId);
        $goal->delete();
    }

    /** @param array{title: string, description?: string|null, due_date?: string|null, sort_order?: int|null} $payload */
    public function createObjective(UserAuth $user, int $goalId, array $payload): array
    {
        $goal = $this->findOwnedGoal($user, $goalId);

        $this->validateObjectiveDueDate($goal, $payload['due_date'] ?? null);

        $sortOrder = $payload['sort_order'] ?? ((int) $goal->objectives()->max('sort_order') + 1);

        $objective = UserGoalObjective::query()->create([
            'goal_id' => $goal->id,
            'title' => trim($payload['title']),
            'description' => $this->nullableTrim($payload['description'] ?? null),
            'due_date' => $payload['due_date'] ?? null,
            'status' => 'pending',
            'sort_order' => max(0, (int) $sortOrder),
        ]);

        $this->syncGoalProgress($goal->fresh(['objectives']));

        return $this->serializeObjective($objective->fresh(), $goal->fresh());
    }

    /** @param array<string, mixed> $payload */
    public function updateObjective(UserAuth $user, int $goalId, int $objectiveId, array $payload): array
    {
        $goal = $this->findOwnedGoal($user, $goalId);
        $objective = $this->findOwnedObjective($goal, $objectiveId);

        if (array_key_exists('title', $payload)) {
            $objective->title = trim((string) $payload['title']);
        }

        if (array_key_exists('description', $payload)) {
            $objective->description = $this->nullableTrim($payload['description']);
        }

        if (array_key_exists('due_date', $payload)) {
            $this->validateObjectiveDueDate($goal, $payload['due_date']);
            $objective->due_date = $payload['due_date'];
        }

        if (array_key_exists('sort_order', $payload)) {
            $objective->sort_order = max(0, (int) $payload['sort_order']);
        }

        $objective->save();

        return $this->serializeObjective($objective->fresh(), $goal);
    }

    public function updateObjectiveStatus(UserAuth $user, int $goalId, int $objectiveId, string $status): array
    {
        $goal = $this->findOwnedGoal($user, $goalId);
        $objective = $this->findOwnedObjective($goal, $objectiveId);
        $previousStatus = $objective->status;

        $this->applyObjectiveStatus($objective, $status);
        $objective->save();

        $goal = $goal->fresh(['objectives']);
        $goalJustCompleted = $this->syncGoalProgress($goal);

        if ($previousStatus !== 'completed' && $status === 'completed') {
            $this->notifyObjectiveCompleted($user, $goal, $objective);
        }

        if ($goalJustCompleted) {
            $this->notifyGoalCompleted($user, $goal->fresh(['objectives']));
        }

        return $this->serializeObjective($objective->fresh(), $goal);
    }

    public function deleteObjective(UserAuth $user, int $goalId, int $objectiveId): void
    {
        $goal = $this->findOwnedGoal($user, $goalId);
        $objective = $this->findOwnedObjective($goal, $objectiveId);
        $objective->delete();

        $this->syncGoalProgress($goal->fresh(['objectives']));
    }

    public function sendDueReminders(): int
    {
        $sent = 0;
        $today = now()->startOfDay();
        $tomorrow = $today->copy()->addDay();

        $activeGoals = UserGoal::query()
            ->with(['objectives', 'user.info'])
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->whereNotNull('due_date')
            ->get();

        foreach ($activeGoals as $goal) {
            if ($goal->user === null) {
                continue;
            }

            $dueDate = $goal->due_date?->startOfDay();

            if ($dueDate === null) {
                continue;
            }

            if ($dueDate->equalTo($tomorrow)) {
                $sent += $this->sendReminder($goal->user, $goal, null, 'upcoming_1d');
            } elseif ($dueDate->lt($today)) {
                $sent += $this->sendReminder($goal->user, $goal, null, 'overdue');
            }
        }

        $activeObjectives = UserGoalObjective::query()
            ->with(['goal.user.info'])
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->whereNotNull('due_date')
            ->get();

        foreach ($activeObjectives as $objective) {
            $goal = $objective->goal;

            if ($goal === null || $goal->user === null) {
                continue;
            }

            $dueDate = $objective->due_date?->startOfDay();

            if ($dueDate === null) {
                continue;
            }

            if ($dueDate->equalTo($tomorrow)) {
                $sent += $this->sendReminder($goal->user, $goal, $objective, 'upcoming_1d');
            } elseif ($dueDate->lt($today)) {
                $sent += $this->sendReminder($goal->user, $goal, $objective, 'overdue');
            }
        }

        return $sent;
    }

    public function isGoalOverdue(UserGoal $goal): bool
    {
        if (in_array($goal->status, ['completed', 'cancelled'], true) || $goal->due_date === null) {
            return false;
        }

        return $goal->due_date->startOfDay()->lt(now()->startOfDay());
    }

    public function isObjectiveOverdue(UserGoalObjective $objective, UserGoal $goal): bool
    {
        if (in_array($objective->status, ['completed', 'cancelled'], true)) {
            return false;
        }

        $dueDate = $objective->due_date ?? $goal->due_date;

        if ($dueDate === null) {
            return false;
        }

        return $dueDate->startOfDay()->lt(now()->startOfDay());
    }

    private function sendReminder(UserAuth $user, UserGoal $goal, ?UserGoalObjective $objective, string $kind): int
    {
        if ($this->reminderAlreadySent($user->id, $objective ? 'objective' : 'goal', $objective?->id ?? $goal->id, $kind)) {
            return 0;
        }

        $goal = $goal->fresh(['objectives']) ?? $goal;
        $summary = $this->buildSummary($goal);
        $isUpcoming = $kind === 'upcoming_1d';

        $this->notificationService->notifyGoalReminder(
            $user->id,
            $goal,
            $objective,
            $isUpcoming,
            $summary,
        );

        $this->mailService->send(new GoalReminderMail(
            recipientEmail: (string) $user->correo,
            userAuthId: $user->id,
            userName: $this->userDisplayName($user),
            goalTitle: $goal->title,
            objectiveTitle: $objective?->title,
            reminderKind: $kind,
            progressPercent: (int) $goal->progress_percent,
            progressDetail: $this->progressDetail($summary),
            dueDateLabel: $this->formatDueDateLabel($objective?->due_date ?? $goal->due_date),
            metasUrl: $this->metasUrlForUser($user, $goal->id),
        ));

        UserGoalReminderLog::query()->create([
            'user_auth_id' => $user->id,
            'entity_type' => $objective ? 'objective' : 'goal',
            'entity_id' => $objective?->id ?? $goal->id,
            'reminder_kind' => $kind,
            'sent_on' => now()->toDateString(),
        ]);

        return 1;
    }

    private function reminderAlreadySent(int $userAuthId, string $entityType, int $entityId, string $kind): bool
    {
        return UserGoalReminderLog::query()
            ->where('user_auth_id', $userAuthId)
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->where('reminder_kind', $kind)
            ->whereDate('sent_on', now()->toDateString())
            ->exists();
    }

    private function notifyObjectiveCompleted(UserAuth $user, UserGoal $goal, UserGoalObjective $objective): void
    {
        $summary = $this->buildSummary($goal);

        $this->notificationService->notifyGoalObjectiveCompleted(
            $user->id,
            $goal,
            $objective,
            $summary,
        );

        $this->mailService->send(new GoalObjectiveCompletedMail(
            recipientEmail: (string) $user->correo,
            userAuthId: $user->id,
            userName: $this->userDisplayName($user),
            goalTitle: $goal->title,
            objectiveTitle: $objective->title,
            progressPercent: (int) $goal->progress_percent,
            progressDetail: $this->progressDetail($summary),
            remainingObjectives: (int) $summary['remaining_objectives'],
            dueDateLabel: $this->formatDueDateLabel($goal->due_date),
            metasUrl: $this->metasUrlForUser($user, $goal->id),
            goalId: $goal->id,
            objectiveId: $objective->id,
        ));
    }

    private function notifyGoalCompleted(UserAuth $user, UserGoal $goal): void
    {
        $summary = $this->buildSummary($goal);
        $completedObjectives = $goal->objectives
            ->where('status', 'completed')
            ->pluck('title')
            ->values()
            ->all();

        $this->notificationService->notifyGoalCompleted(
            $user->id,
            $goal,
            $summary,
        );

        $this->mailService->send(new GoalCompletedMail(
            recipientEmail: (string) $user->correo,
            userAuthId: $user->id,
            userName: $this->userDisplayName($user),
            goalTitle: $goal->title,
            startDateLabel: $this->formatDueDateLabel($goal->start_date),
            completedDateLabel: $this->formatDueDateLabel($goal->completed_at?->toDateString()),
            completedObjectives: $completedObjectives,
            progressDetail: $this->progressDetail($summary),
            metasUrl: $this->metasUrlForUser($user, $goal->id),
            goalId: $goal->id,
        ));
    }

    /** @return array<string, mixed> */
    private function serializeGoalListItem(UserGoal $goal): array
    {
        $data = (new UserGoalResource($goal))->resolve();
        $data['is_overdue'] = $this->isGoalOverdue($goal);

        return $data;
    }

    /** @return array<string, mixed> */
    private function serializeObjective(UserGoalObjective $objective, UserGoal $goal): array
    {
        $data = (new UserGoalObjectiveResource($objective))->resolve();
        $data['is_overdue'] = $this->isObjectiveOverdue($objective, $goal);

        return $data;
    }

    /** @return array{total_objectives: int, completed_objectives: int, remaining_objectives: int, days_until_due: int|null} */
    private function buildSummary(UserGoal $goal): array
    {
        $objectives = $goal->relationLoaded('objectives')
            ? $goal->objectives
            : $goal->objectives()->get();

        $total = $objectives->count();
        $completed = $objectives->where('status', 'completed')->count();

        $daysUntilDue = null;

        if ($goal->due_date !== null) {
            $daysUntilDue = now()->startOfDay()->diffInDays($goal->due_date->startOfDay(), false);
        }

        return [
            'total_objectives' => $total,
            'completed_objectives' => $completed,
            'remaining_objectives' => max(0, $total - $completed),
            'days_until_due' => $daysUntilDue,
        ];
    }

    private function syncGoalProgress(UserGoal $goal): bool
    {
        $goal->loadMissing('objectives');

        $total = $goal->objectives->count();
        $completed = $goal->objectives->where('status', 'completed')->count();
        $wasCompleted = $goal->status === 'completed';

        $goal->progress_percent = $total > 0 ? (int) round(($completed / $total) * 100) : 0;

        if ($total > 0 && $completed === $total) {
            $goal->status = 'completed';
            $goal->completed_at = $goal->completed_at ?? now();
        } elseif ($goal->status === 'completed') {
            $goal->status = 'in_progress';
            $goal->completed_at = null;
        } elseif ($total > 0 && $completed > 0) {
            $goal->status = 'in_progress';
        } elseif ($total === 0 && $goal->status !== 'cancelled') {
            $goal->status = 'pending';
            $goal->completed_at = null;
        }

        $goal->save();

        return ! $wasCompleted && $goal->status === 'completed';
    }

    private function applyGoalStatus(UserGoal $goal, string $status): void
    {
        $goal->status = $status;

        if ($status === 'completed') {
            $goal->completed_at = $goal->completed_at ?? now();
            $goal->progress_percent = 100;
        } elseif ($status === 'cancelled') {
            $goal->completed_at = null;
        } else {
            $goal->completed_at = null;
        }
    }

    private function applyObjectiveStatus(UserGoalObjective $objective, string $status): void
    {
        $objective->status = $status;
        $objective->completed_at = $status === 'completed' ? ($objective->completed_at ?? now()) : null;

        if ($status === 'pending') {
            $objective->completed_at = null;
        }
    }

    private function validateGoalDates(UserGoal $goal): void
    {
        if ($goal->start_date !== null && $goal->due_date !== null && $goal->due_date->lt($goal->start_date)) {
            throw ValidationException::withMessages([
                'due_date' => ['La fecha límite debe ser posterior o igual a la fecha de inicio.'],
            ]);
        }
    }

    private function validateObjectiveDueDate(UserGoal $goal, mixed $dueDate): void
    {
        if ($dueDate === null || $dueDate === '') {
            return;
        }

        $parsed = Carbon::parse((string) $dueDate)->startOfDay();

        if ($goal->due_date !== null && $parsed->gt($goal->due_date->startOfDay())) {
            throw ValidationException::withMessages([
                'due_date' => ['La fecha del objetivo no puede ser posterior a la fecha límite de la meta.'],
            ]);
        }
    }

    private function findOwnedGoal(UserAuth $user, int $goalId): UserGoal
    {
        $goal = UserGoal::query()
            ->where('user_auth_id', $user->id)
            ->where('id', $goalId)
            ->first();

        if ($goal === null) {
            throw new NotFoundHttpException('Meta no encontrada.');
        }

        return $goal;
    }

    private function findOwnedObjective(UserGoal $goal, int $objectiveId): UserGoalObjective
    {
        $objective = UserGoalObjective::query()
            ->where('goal_id', $goal->id)
            ->where('id', $objectiveId)
            ->first();

        if ($objective === null) {
            throw new NotFoundHttpException('Objetivo no encontrado.');
        }

        return $objective;
    }

    /** @param array{total_objectives: int, completed_objectives: int, remaining_objectives: int, days_until_due: int|null} $summary */
    private function progressDetail(array $summary): string
    {
        $parts = [
            $summary['completed_objectives'] . ' de ' . $summary['total_objectives'] . ' objetivos completados',
        ];

        if ($summary['remaining_objectives'] > 0) {
            $parts[] = 'Quedan ' . $summary['remaining_objectives'] . ' por cumplir';
        }

        if ($summary['days_until_due'] !== null) {
            if ($summary['days_until_due'] > 0) {
                $parts[] = 'Vence en ' . $summary['days_until_due'] . ' día(s)';
            } elseif ($summary['days_until_due'] === 0) {
                $parts[] = 'Vence hoy';
            } else {
                $parts[] = 'Venció hace ' . abs((int) $summary['days_until_due']) . ' día(s)';
            }
        }

        return implode(' · ', $parts);
    }

    private function formatDueDateLabel(mixed $date): string
    {
        if ($date === null || $date === '') {
            return 'Sin fecha';
        }

        return Carbon::parse((string) $date)->locale('es')->isoFormat('D MMM YYYY');
    }

    private function metasUrlForUser(UserAuth $user, int $goalId): string
    {
        $prefix = $user->rol === 'impulsa_cliente' ? 'cliente' : 'emprendedor';

        return ImpulsaFrontendUrl::to($prefix . '/metas?goalId=' . $goalId);
    }

    private function userDisplayName(UserAuth $user): string
    {
        $info = $user->info;

        if ($info !== null) {
            $name = trim(((string) $info->nombre) . ' ' . ((string) $info->apellido));

            if ($name !== '') {
                return $name;
            }
        }

        return (string) $user->correo;
    }

    private function nullableTrim(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim((string) $value);

        return $trimmed === '' ? null : $trimmed;
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
