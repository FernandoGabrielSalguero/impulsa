<?php

namespace App\Services\Colaborador;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeliverableCommentService
{
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
    public function listForAdmin(int $projectId, int $deliverableId, ?int $viewerUserId = null): array
    {
        $this->assertDeliverableBelongsToProject($projectId, $deliverableId);

        return $this->listComments($projectId, $deliverableId, $viewerUserId);
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
