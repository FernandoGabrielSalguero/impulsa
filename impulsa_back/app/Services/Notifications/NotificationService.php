<?php

namespace App\Services\Notifications;

use App\Support\NotificationCopy;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotificationService
{
    public const TYPE_PROJECT_COMMENT = 'project.comment_created';

    public const TYPE_PROJECT_PHASE = 'project.phase_created';

    public const TYPE_PROJECT_DELIVERABLE = 'project.deliverable_created';

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

        $this->notifyMany(
            $this->projectInternalRecipients($projectId, $actorUserId),
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

    public function notifyProjectPhaseCreated(
        int $projectId,
        int $phaseId,
        string $phaseTitle,
        ?int $actorUserId,
        bool $notifyClient,
    ): void {
        $project = DB::table('projects')->where('id', $projectId)->first(['id', 'project_name', 'client_user_id']);

        if ($project === null) {
            return;
        }

        $copy = NotificationCopy::projectPhaseCreated((string) $project->project_name, $phaseTitle);
        $recipients = $this->projectInternalRecipients($projectId, $actorUserId);

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

    public function notifyProjectDeliverableCreated(
        int $projectId,
        int $deliverableId,
        string $deliverableTitle,
        ?int $actorUserId,
        bool $notifyClient,
    ): void {
        $project = DB::table('projects')->where('id', $projectId)->first(['id', 'project_name', 'client_user_id']);

        if ($project === null) {
            return;
        }

        $copy = NotificationCopy::projectDeliverableCreated((string) $project->project_name, $deliverableTitle);
        $recipients = $this->projectInternalRecipients($projectId, $actorUserId);

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
                self::TYPE_PROJECT_PHASE => 'view_timeline',
                self::TYPE_PROJECT_DELIVERABLE => 'flag',
                default => 'notifications',
            },
        ];
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
