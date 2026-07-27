<?php

namespace App\Services\Notifications;

use App\Support\NotificationCopy;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotificationService
{
    public const TYPE_PROJECT_COMMENT = 'project.comment_created';

    public const TYPE_PROJECT_CREATED = 'project.created';

    public const TYPE_PROJECT_UPDATED = 'project.updated';

    public const TYPE_PROJECT_PHASE = 'project.phase_created';

    public const TYPE_PROJECT_PHASE_UPDATED = 'project.phase_updated';

    public const TYPE_PROJECT_PHASE_DELETED = 'project.phase_deleted';

    public const TYPE_PROJECT_DELIVERABLE = 'project.deliverable_created';

    public const TYPE_PROJECT_DELIVERABLE_UPDATED = 'project.deliverable_updated';

    public const TYPE_PROJECT_DELIVERABLE_DELETED = 'project.deliverable_deleted';

    public const TYPE_PROJECT_STATUS = 'project.status_changed';

    public const TYPE_PROJECT_CLIENT_UPDATE = 'project.client_update';

    public const TYPE_GOAL_OBJECTIVE_COMPLETED = 'goals.objective_completed';

    public const TYPE_GOAL_COMPLETED = 'goals.goal_completed';

    public const TYPE_GOAL_REMINDER_UPCOMING = 'goals.reminder_upcoming';

    public const TYPE_GOAL_REMINDER_OVERDUE = 'goals.reminder_overdue';

    /**
     * @param  list<int>  $userIds
     * @param  array<string, mixed>  $payload
     */
    public function notifyMany(
        array $userIds,
        string $type,
        string $title,
        ?string $body,
        array $payload = [],
    ): int {
        $ids = collect($userIds)
            ->map(static fn ($id): int => (int) $id)
            ->filter(static fn (int $id): bool => $id > 0)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return 0;
        }

        $now = now();
        $rows = $ids->map(static fn (int $userId): array => [
            'user_auth_id' => $userId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'payload' => json_encode($payload, JSON_UNESCAPED_UNICODE),
            'read_at' => null,
            'dismissed_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        DB::table('user_notifications')->insert($rows);

        return count($rows);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return list<array<string, mixed>>
     */
    public function listForUser(int $userAuthId, int $limit = 30): array
    {
        $limit = max(1, min($limit, 100));

        return DB::table('user_notifications')
            ->where('user_auth_id', $userAuthId)
            ->whereNull('dismissed_at')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->map(fn ($row): array => $this->formatRow($row))
            ->all();
    }

    public function unreadCount(int $userAuthId): int
    {
        return (int) DB::table('user_notifications')
            ->where('user_auth_id', $userAuthId)
            ->whereNull('dismissed_at')
            ->whereNull('read_at')
            ->count();
    }

    /** @return array<string, mixed> */
    public function markRead(int $userAuthId, int $notificationId): array
    {
        $row = $this->findOwned($userAuthId, $notificationId);

        if ($row->read_at === null) {
            DB::table('user_notifications')
                ->where('id', $notificationId)
                ->update([
                    'read_at' => now(),
                    'updated_at' => now(),
                ]);
        }

        return $this->formatRow(
            DB::table('user_notifications')->where('id', $notificationId)->first(),
        );
    }

    public function markAllRead(int $userAuthId): int
    {
        return DB::table('user_notifications')
            ->where('user_auth_id', $userAuthId)
            ->whereNull('dismissed_at')
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
                'updated_at' => now(),
            ]);
    }

    public function dismiss(int $userAuthId, int $notificationId): void
    {
        $this->findOwned($userAuthId, $notificationId);

        DB::table('user_notifications')
            ->where('id', $notificationId)
            ->update([
                'dismissed_at' => now(),
                'read_at' => now(),
                'updated_at' => now(),
            ]);
    }

    public function notifyProjectCommentCreated(
        int $projectId,
        int $deliverableId,
        int $commentId,
        int $actorUserId,
    ): void {
        $project = DB::table('projects')->where('id', $projectId)->first(['id', 'project_name', 'manager_user_id']);
        $deliverable = DB::table('project_deliverables')->where('id', $deliverableId)->first(['id', 'title']);

        if ($project === null || $deliverable === null) {
            return;
        }

        $actorLabel = $this->userLabel($actorUserId) ?? 'Alguien';
        $copy = NotificationCopy::projectCommentCreated(
            (string) $project->project_name,
            (string) $deliverable->title,
            $actorLabel,
        );

        $this->notifyTeam(
            $projectId,
            $actorUserId,
            self::TYPE_PROJECT_COMMENT,
            $copy['title'],
            $copy['body'],
            [
                'project_id' => $projectId,
                'deliverable_id' => $deliverableId,
                'comment_id' => $commentId,
                'actor_user_id' => $actorUserId,
            ],
        );
    }

    public function notifyProjectCreated(int $projectId, ?int $actorUserId, bool $notifyClient = false): void
    {
        $project = DB::table('projects')->where('id', $projectId)->first(['id', 'project_name', 'client_user_id']);

        if ($project === null) {
            return;
        }

        $actorLabel = $actorUserId ? ($this->userLabel($actorUserId) ?? 'Alguien') : 'Alguien';
        $copy = NotificationCopy::projectCreated((string) $project->project_name, $actorLabel);

        $this->notifyTeam(
            $projectId,
            $actorUserId,
            self::TYPE_PROJECT_CREATED,
            $copy['title'],
            $copy['body'],
            [
                'project_id' => $projectId,
                'actor_user_id' => $actorUserId,
            ],
        );

        if ($notifyClient && $project->client_user_id) {
            $clientId = (int) $project->client_user_id;
            if ($actorUserId === null || $clientId !== $actorUserId) {
                $clientCopy = NotificationCopy::projectUpdatedForClient(
                    (string) $project->project_name,
                    'Proyecto creado',
                );

                $this->notifyMany(
                    [$clientId],
                    self::TYPE_PROJECT_CLIENT_UPDATE,
                    $clientCopy['title'],
                    $clientCopy['body'],
                    [
                        'project_id' => $projectId,
                        'actor_user_id' => $actorUserId,
                    ],
                );
            }
        }
    }

    /**
     * @param  list<string>  $changeLines
     */
    public function notifyProjectUpdated(int $projectId, ?int $actorUserId, array $changeLines = []): void
    {
        $project = DB::table('projects')->where('id', $projectId)->first(['id', 'project_name']);

        if ($project === null || $changeLines === []) {
            return;
        }

        $actorLabel = $actorUserId ? ($this->userLabel($actorUserId) ?? 'Alguien') : 'Alguien';
        $copy = NotificationCopy::projectUpdated((string) $project->project_name, $actorLabel, $changeLines);

        $this->notifyTeam(
            $projectId,
            $actorUserId,
            self::TYPE_PROJECT_UPDATED,
            $copy['title'],
            $copy['body'],
            [
                'project_id' => $projectId,
                'actor_user_id' => $actorUserId,
                'changes' => $changeLines,
            ],
        );
    }

    public function notifyProjectPhaseCreated(
        int $projectId,
        int $phaseId,
        string $phaseTitle,
        ?int $actorUserId,
        bool $notifyClient = false,
    ): void {
        $project = DB::table('projects')->where('id', $projectId)->first(['id', 'project_name', 'client_user_id']);

        if ($project === null) {
            return;
        }

        $copy = NotificationCopy::projectPhaseCreated((string) $project->project_name, $phaseTitle);
        $recipients = $this->teamRecipientIds($projectId, $actorUserId);

        if ($notifyClient && $project->client_user_id) {
            $clientId = (int) $project->client_user_id;
            if ($actorUserId === null || $clientId !== $actorUserId) {
                $recipients[] = $clientId;
            }
        }

        $this->notifyMany(
            $recipients,
            self::TYPE_PROJECT_PHASE,
            $copy['title'],
            $copy['body'],
            [
                'project_id' => $projectId,
                'phase_id' => $phaseId,
                'actor_user_id' => $actorUserId,
            ],
        );
    }

    /**
     * @param  list<string>  $changeLines
     */
    public function notifyProjectPhaseUpdated(
        int $projectId,
        int $phaseId,
        string $phaseTitle,
        ?int $actorUserId,
        array $changeLines = [],
    ): void {
        $project = DB::table('projects')->where('id', $projectId)->first(['id', 'project_name']);

        if ($project === null || $changeLines === []) {
            return;
        }

        $actorLabel = $actorUserId ? ($this->userLabel($actorUserId) ?? 'Alguien') : 'Alguien';
        $copy = NotificationCopy::projectPhaseUpdated(
            (string) $project->project_name,
            $phaseTitle,
            $actorLabel,
            $changeLines,
        );

        $this->notifyTeam(
            $projectId,
            $actorUserId,
            self::TYPE_PROJECT_PHASE_UPDATED,
            $copy['title'],
            $copy['body'],
            [
                'project_id' => $projectId,
                'phase_id' => $phaseId,
                'actor_user_id' => $actorUserId,
                'changes' => $changeLines,
            ],
        );
    }

    public function notifyProjectPhaseDeleted(
        int $projectId,
        string $phaseTitle,
        ?int $actorUserId,
    ): void {
        $project = DB::table('projects')->where('id', $projectId)->first(['id', 'project_name']);

        if ($project === null) {
            return;
        }

        $actorLabel = $actorUserId ? ($this->userLabel($actorUserId) ?? 'Alguien') : 'Alguien';
        $copy = NotificationCopy::projectPhaseDeleted((string) $project->project_name, $phaseTitle, $actorLabel);

        $this->notifyTeam(
            $projectId,
            $actorUserId,
            self::TYPE_PROJECT_PHASE_DELETED,
            $copy['title'],
            $copy['body'],
            [
                'project_id' => $projectId,
                'actor_user_id' => $actorUserId,
            ],
        );
    }

    public function notifyProjectDeliverableCreated(
        int $projectId,
        int $deliverableId,
        string $deliverableTitle,
        ?int $actorUserId,
        bool $notifyClient = false,
    ): void {
        $project = DB::table('projects')->where('id', $projectId)->first(['id', 'project_name', 'client_user_id']);

        if ($project === null) {
            return;
        }

        $copy = NotificationCopy::projectDeliverableCreated((string) $project->project_name, $deliverableTitle);
        $recipients = $this->teamRecipientIds($projectId, $actorUserId);

        if ($notifyClient && $project->client_user_id) {
            $clientId = (int) $project->client_user_id;
            if ($actorUserId === null || $clientId !== $actorUserId) {
                $recipients[] = $clientId;
            }
        }

        $this->notifyMany(
            $recipients,
            self::TYPE_PROJECT_DELIVERABLE,
            $copy['title'],
            $copy['body'],
            [
                'project_id' => $projectId,
                'deliverable_id' => $deliverableId,
                'actor_user_id' => $actorUserId,
            ],
        );
    }

    /**
     * @param  list<string>  $changeLines
     */
    public function notifyProjectDeliverableUpdated(
        int $projectId,
        int $deliverableId,
        string $deliverableTitle,
        ?int $actorUserId,
        array $changeLines = [],
    ): void {
        $project = DB::table('projects')->where('id', $projectId)->first(['id', 'project_name']);

        if ($project === null || $changeLines === []) {
            return;
        }

        $actorLabel = $actorUserId ? ($this->userLabel($actorUserId) ?? 'Alguien') : 'Alguien';
        $copy = NotificationCopy::projectDeliverableUpdated(
            (string) $project->project_name,
            $deliverableTitle,
            $actorLabel,
            $changeLines,
        );

        $this->notifyTeam(
            $projectId,
            $actorUserId,
            self::TYPE_PROJECT_DELIVERABLE_UPDATED,
            $copy['title'],
            $copy['body'],
            [
                'project_id' => $projectId,
                'deliverable_id' => $deliverableId,
                'actor_user_id' => $actorUserId,
                'changes' => $changeLines,
            ],
        );
    }

    public function notifyProjectDeliverableDeleted(
        int $projectId,
        string $deliverableTitle,
        ?int $actorUserId,
    ): void {
        $project = DB::table('projects')->where('id', $projectId)->first(['id', 'project_name']);

        if ($project === null) {
            return;
        }

        $actorLabel = $actorUserId ? ($this->userLabel($actorUserId) ?? 'Alguien') : 'Alguien';
        $copy = NotificationCopy::projectDeliverableDeleted(
            (string) $project->project_name,
            $deliverableTitle,
            $actorLabel,
        );

        $this->notifyTeam(
            $projectId,
            $actorUserId,
            self::TYPE_PROJECT_DELIVERABLE_DELETED,
            $copy['title'],
            $copy['body'],
            [
                'project_id' => $projectId,
                'actor_user_id' => $actorUserId,
            ],
        );
    }

    public function notifyProjectStatusChanged(
        int $projectId,
        string $entityLabel,
        string $statusLabel,
        ?int $actorUserId,
        array $payload = [],
    ): void {
        $project = DB::table('projects')->where('id', $projectId)->first(['id', 'project_name']);

        if ($project === null) {
            return;
        }

        $actorLabel = $actorUserId ? ($this->userLabel($actorUserId) ?? 'Alguien') : 'Alguien';
        $copy = NotificationCopy::projectStatusChanged(
            (string) $project->project_name,
            $entityLabel,
            $statusLabel,
            $actorLabel,
        );

        $this->notifyTeam(
            $projectId,
            $actorUserId,
            self::TYPE_PROJECT_STATUS,
            $copy['title'],
            $copy['body'],
            array_merge([
                'project_id' => $projectId,
                'actor_user_id' => $actorUserId,
            ], $payload),
        );
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function notifyTeam(
        int $projectId,
        ?int $actorUserId,
        string $type,
        string $title,
        ?string $body,
        array $payload = [],
    ): void {
        $this->notifyMany(
            $this->teamRecipientIds($projectId, $actorUserId),
            $type,
            $title,
            $body,
            $payload,
        );
    }

    /** @return list<int> */
    public function teamRecipientIds(int $projectId, ?int $excludeUserId = null): array
    {
        return collect($this->projectInternalRecipients($projectId, $excludeUserId))
            ->merge($this->adminRecipientIds($excludeUserId))
            ->unique()
            ->values()
            ->all();
    }

    /** @return list<int> */
    public function projectInternalRecipients(int $projectId, ?int $excludeUserId = null): array
    {
        $managerId = (int) (DB::table('projects')->where('id', $projectId)->value('manager_user_id') ?? 0);

        $collaboratorIds = DB::table('project_collaborators')
            ->where('project_id', $projectId)
            ->pluck('user_auth_id')
            ->map(static fn ($id): int => (int) $id)
            ->all();

        return collect([$managerId, ...$collaboratorIds])
            ->filter(static fn (int $id): bool => $id > 0)
            ->when(
                $excludeUserId !== null && $excludeUserId > 0,
                static fn (Collection $ids) => $ids->reject(static fn (int $id): bool => $id === $excludeUserId),
            )
            ->unique()
            ->values()
            ->all();
    }

    /** @return list<int> */
    public function adminRecipientIds(?int $excludeUserId = null): array
    {
        return DB::table('user_auth')
            ->where('rol', 'impulsa_administrador')
            ->pluck('id')
            ->map(static fn ($id): int => (int) $id)
            ->filter(static fn (int $id): bool => $id > 0)
            ->when(
                $excludeUserId !== null && $excludeUserId > 0,
                static fn (Collection $ids) => $ids->reject(static fn (int $id): bool => $id === $excludeUserId),
            )
            ->unique()
            ->values()
            ->all();
    }

    private function findOwned(int $userAuthId, int $notificationId): object
    {
        $row = DB::table('user_notifications')
            ->where('id', $notificationId)
            ->where('user_auth_id', $userAuthId)
            ->whereNull('dismissed_at')
            ->first();

        if ($row === null) {
            throw new NotFoundHttpException('La notificación no existe.');
        }

        return $row;
    }

    /** @return array<string, mixed> */
    private function formatRow(object $row): array
    {
        $payload = $row->payload;

        if (is_string($payload)) {
            $decoded = json_decode($payload, true);
            $payload = is_array($decoded) ? $decoded : [];
        } elseif (! is_array($payload)) {
            $payload = [];
        }

        return [
            'id' => (int) $row->id,
            'type' => $row->type,
            'title' => $row->title,
            'body' => $row->body,
            'payload' => $payload,
            'created_at' => $row->created_at,
            'is_unread' => $row->read_at === null,
            'icon_hint' => match ((string) $row->type) {
                self::TYPE_PROJECT_COMMENT => 'chat',
                self::TYPE_PROJECT_CREATED => 'folder',
                self::TYPE_PROJECT_UPDATED => 'edit_note',
                self::TYPE_PROJECT_PHASE,
                self::TYPE_PROJECT_PHASE_UPDATED,
                self::TYPE_PROJECT_PHASE_DELETED => 'view_timeline',
                self::TYPE_PROJECT_DELIVERABLE,
                self::TYPE_PROJECT_DELIVERABLE_UPDATED,
                self::TYPE_PROJECT_DELIVERABLE_DELETED => 'flag',
                self::TYPE_PROJECT_STATUS => 'sync_alt',
                self::TYPE_PROJECT_CLIENT_UPDATE => 'campaign',
                self::TYPE_GOAL_OBJECTIVE_COMPLETED,
                self::TYPE_GOAL_COMPLETED => 'flag',
                self::TYPE_GOAL_REMINDER_UPCOMING,
                self::TYPE_GOAL_REMINDER_OVERDUE => 'event',
                default => 'notifications',
            },
        ];
    }

    /**
     * @param  array{total_objectives: int, completed_objectives: int, remaining_objectives: int, days_until_due: int|null}  $summary
     */
    public function notifyGoalObjectiveCompleted(
        int $userAuthId,
        object $goal,
        object $objective,
        array $summary,
    ): void {
        $copy = NotificationCopy::goalObjectiveCompleted(
            (string) $goal->title,
            (string) $objective->title,
            (int) $goal->progress_percent,
            (int) $summary['remaining_objectives'],
        );

        $this->notifyMany(
            [$userAuthId],
            self::TYPE_GOAL_OBJECTIVE_COMPLETED,
            $copy['title'],
            $copy['body'],
            [
                'goal_id' => (int) $goal->id,
                'objective_id' => (int) $objective->id,
                'progress_percent' => (int) $goal->progress_percent,
                'remaining_objectives' => (int) $summary['remaining_objectives'],
            ],
        );
    }

    /**
     * @param  array{total_objectives: int, completed_objectives: int, remaining_objectives: int, days_until_due: int|null}  $summary
     */
    public function notifyGoalCompleted(int $userAuthId, object $goal, array $summary): void
    {
        $copy = NotificationCopy::goalCompleted((string) $goal->title);

        $this->notifyMany(
            [$userAuthId],
            self::TYPE_GOAL_COMPLETED,
            $copy['title'],
            $copy['body'],
            [
                'goal_id' => (int) $goal->id,
                'progress_percent' => (int) $goal->progress_percent,
                'total_objectives' => (int) $summary['total_objectives'],
            ],
        );
    }

    /**
     * @param  array{total_objectives: int, completed_objectives: int, remaining_objectives: int, days_until_due: int|null}  $summary
     */
    public function notifyGoalReminder(
        int $userAuthId,
        object $goal,
        ?object $objective,
        bool $isUpcoming,
        array $summary,
    ): void {
        $entityLabel = $objective !== null
            ? 'El objetivo "'.$objective->title.'"'
            : 'La meta';

        $copy = $isUpcoming
            ? NotificationCopy::goalReminderUpcoming($entityLabel, (string) $goal->title)
            : NotificationCopy::goalReminderOverdue($entityLabel, (string) $goal->title);

        $type = $isUpcoming ? self::TYPE_GOAL_REMINDER_UPCOMING : self::TYPE_GOAL_REMINDER_OVERDUE;

        $payload = [
            'goal_id' => (int) $goal->id,
            'progress_percent' => (int) $goal->progress_percent,
            'remaining_objectives' => (int) $summary['remaining_objectives'],
        ];

        if ($objective !== null) {
            $payload['objective_id'] = (int) $objective->id;
        }

        $this->notifyMany(
            [$userAuthId],
            $type,
            $copy['title'],
            $copy['body'],
            $payload,
        );
    }

    private function userLabel(int $userAuthId): ?string
    {
        $row = DB::table('user_auth as ua')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->where('ua.id', $userAuthId)
            ->first(['ua.correo', 'ui.nombre', 'ui.apellido']);

        if ($row === null) {
            return null;
        }

        $name = trim((string) (($row->nombre ?? '') . ' ' . ($row->apellido ?? '')));

        return $name !== '' ? $name : (string) $row->correo;
    }
}
