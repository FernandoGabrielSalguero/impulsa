<?php

namespace App\Services\Colaborador;

use App\Services\Notifications\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeliverableCommentService
{
    public function __construct(
        private readonly NotificationService $notificationService,
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function listForCollaborator(int $userAuthId, int $projectId, int $deliverableId): array
    {
        $this->assertCollaboratorAssigned($userAuthId, $projectId);
        $this->assertDeliverableBelongsToProject($projectId, $deliverableId);

        return $this->listComments($projectId, $deliverableId, $userAuthId);
    }

    /**
     * @return array<string, mixed>
     */
    public function createForCollaborator(
        int $userAuthId,
        int $projectId,
        int $deliverableId,
        string $message,
    ): array {
        $this->assertCollaboratorAssigned($userAuthId, $projectId);

        return $this->createComment($userAuthId, $projectId, $deliverableId, $message);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listForAdmin(int $projectId, int $deliverableId, ?int $viewerUserId = null): array
    {
        $this->assertDeliverableBelongsToProject($projectId, $deliverableId);

        return $this->listComments($projectId, $deliverableId, $viewerUserId);
    }

    /**
     * @return array<string, mixed>
     */
    public function createForAdmin(
        int $userAuthId,
        int $projectId,
        int $deliverableId,
        string $message,
    ): array {
        return $this->createComment($userAuthId, $projectId, $deliverableId, $message);
    }

    public function markReadForCollaborator(int $userAuthId, int $projectId, int $deliverableId): int
    {
        $this->assertCollaboratorAssigned($userAuthId, $projectId);
        $this->assertDeliverableBelongsToProject($projectId, $deliverableId);

        $this->markDeliverableCommentsRead($userAuthId, $deliverableId);

        return 0;
    }

    public function markReadForAdmin(int $userAuthId, int $projectId, int $deliverableId): int
    {
        $this->assertDeliverableBelongsToProject($projectId, $deliverableId);
        $this->markDeliverableCommentsRead($userAuthId, $deliverableId);

        return 0;
    }

    /**
     * @param  list<int>  $deliverableIds
     * @return array<int, int>
     */
    public function unreadCountsByDeliverable(int $userAuthId, array $deliverableIds): array
    {
        if (! $this->readsTableExists()) {
            return [];
        }

        $ids = collect($deliverableIds)
            ->map(static fn ($id): int => (int) $id)
            ->filter(static fn (int $id): bool => $id > 0)
            ->unique()
            ->values()
            ->all();

        if ($ids === []) {
            return [];
        }

        $counts = array_fill_keys($ids, 0);

        $rows = DB::table('project_deliverable_comments as c')
            ->leftJoin('project_deliverable_comment_reads as r', function ($join) use ($userAuthId): void {
                $join->on('r.deliverable_id', '=', 'c.deliverable_id')
                    ->where('r.user_auth_id', '=', $userAuthId);
            })
            ->whereIn('c.deliverable_id', $ids)
            ->where('c.user_auth_id', '!=', $userAuthId)
            ->whereRaw('c.id > COALESCE(r.last_read_comment_id, 0)')
            ->groupBy('c.deliverable_id')
            ->selectRaw('c.deliverable_id, COUNT(*) as unread_count')
            ->get();

        foreach ($rows as $row) {
            $counts[(int) $row->deliverable_id] = (int) $row->unread_count;
        }

        return $counts;
    }

    /**
     * @param  list<array<string, mixed>>  $deliverables
     * @return list<array<string, mixed>>
     */
    public function attachUnreadCounts(array $deliverables, ?int $viewerUserId): array
    {
        if ($viewerUserId === null || $deliverables === []) {
            return array_map(static function (array $deliverable): array {
                $deliverable['unread_comments_count'] = 0;

                return $deliverable;
            }, $deliverables);
        }

        $counts = $this->unreadCountsByDeliverable(
            $viewerUserId,
            array_map(static fn (array $deliverable): int => (int) $deliverable['id'], $deliverables),
        );

        return array_map(static function (array $deliverable) use ($counts): array {
            $deliverable['unread_comments_count'] = $counts[(int) $deliverable['id']] ?? 0;

            return $deliverable;
        }, $deliverables);
    }

    /**
     * @return array<string, mixed>
     */
    private function createComment(
        int $userAuthId,
        int $projectId,
        int $deliverableId,
        string $message,
    ): array {
        $this->assertDeliverableBelongsToProject($projectId, $deliverableId);

        $trimmed = trim($message);

        if ($trimmed === '') {
            throw ValidationException::withMessages([
                'message' => ['El comentario no puede estar vacío.'],
            ]);
        }

        $commentId = (int) DB::table('project_deliverable_comments')->insertGetId([
            'project_id' => $projectId,
            'deliverable_id' => $deliverableId,
            'user_auth_id' => $userAuthId,
            'message' => $trimmed,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->notificationService->notifyProjectCommentCreated(
            $projectId,
            $deliverableId,
            $commentId,
            $userAuthId,
        );

        $this->markDeliverableCommentsRead($userAuthId, $deliverableId);

        $comments = $this->listComments($projectId, $deliverableId, $userAuthId);

        foreach ($comments as $comment) {
            if ((int) $comment['id'] === $commentId) {
                return $comment;
            }
        }

        throw new NotFoundHttpException('No pudimos recuperar el comentario creado.');
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listComments(int $projectId, int $deliverableId, ?int $viewerUserId): array
    {
        return DB::table('project_deliverable_comments as c')
            ->join('user_auth as ua', 'ua.id', '=', 'c.user_auth_id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->where('c.project_id', $projectId)
            ->where('c.deliverable_id', $deliverableId)
            ->orderBy('c.created_at')
            ->orderBy('c.id')
            ->get([
                'c.id',
                'c.message',
                'c.created_at',
                'c.user_auth_id',
                'ua.correo as author_correo',
                'ui.nombre as author_nombre',
                'ui.apellido as author_apellido',
            ])
            ->map(static function ($row) use ($viewerUserId): array {
                $name = trim((string) (($row->author_nombre ?? '') . ' ' . ($row->author_apellido ?? '')));

                return [
                    'id' => (int) $row->id,
                    'message' => $row->message,
                    'author_name' => $name !== '' ? $name : null,
                    'author_correo' => $row->author_correo,
                    'created_at' => $row->created_at,
                    'is_mine' => $viewerUserId !== null && (int) $row->user_auth_id === $viewerUserId,
                ];
            })
            ->all();
    }

    private function markDeliverableCommentsRead(int $userAuthId, int $deliverableId): void
    {
        if (! $this->readsTableExists()) {
            return;
        }

        $maxCommentId = DB::table('project_deliverable_comments')
            ->where('deliverable_id', $deliverableId)
            ->max('id');

        $now = now();

        $existing = DB::table('project_deliverable_comment_reads')
            ->where('user_auth_id', $userAuthId)
            ->where('deliverable_id', $deliverableId)
            ->first();

        $payload = [
            'last_read_comment_id' => $maxCommentId !== null ? (int) $maxCommentId : null,
            'last_read_at' => $now,
            'updated_at' => $now,
        ];

        if ($existing !== null) {
            DB::table('project_deliverable_comment_reads')
                ->where('id', $existing->id)
                ->update($payload);

            return;
        }

        DB::table('project_deliverable_comment_reads')->insert([
            ...$payload,
            'user_auth_id' => $userAuthId,
            'deliverable_id' => $deliverableId,
            'created_at' => $now,
        ]);
    }

    private function readsTableExists(): bool
    {
        return Schema::hasTable('project_deliverable_comment_reads');
    }

    private function assertCollaboratorAssigned(int $userAuthId, int $projectId): void
    {
        $exists = DB::table('project_collaborators')
            ->where('project_id', $projectId)
            ->where('user_auth_id', $userAuthId)
            ->exists();

        if (! $exists) {
            throw new NotFoundHttpException('El proyecto no existe o no tenés acceso.');
        }
    }

    private function assertDeliverableBelongsToProject(int $projectId, int $deliverableId): void
    {
        $exists = DB::table('project_deliverables')
            ->where('id', $deliverableId)
            ->where('project_id', $projectId)
            ->exists();

        if (! $exists) {
            throw new NotFoundHttpException('El objetivo no existe en este proyecto.');
        }
    }
}
