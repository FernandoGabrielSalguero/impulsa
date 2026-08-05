<?php

namespace App\Services\Projects;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectAttachmentService
{
    public const MAX_PER_ENTITY = 3;

    public function __construct(
        private readonly ProjectAttachmentStorageService $storageService,
    ) {}

    /**
     * @param  list<int>  $phaseIds
     * @param  list<int>  $deliverableIds
     * @return array{phases: array<int, list<array<string, mixed>>>, deliverables: array<int, list<array<string, mixed>>>}
     */
    public function mapForEntities(int $projectId, array $phaseIds, array $deliverableIds): array
    {
        if (! $this->tableExists()) {
            return ['phases' => [], 'deliverables' => []];
        }

        $phaseMap = [];
        foreach ($phaseIds as $id) {
            $phaseMap[(int) $id] = [];
        }

        $deliverableMap = [];
        foreach ($deliverableIds as $id) {
            $deliverableMap[(int) $id] = [];
        }

        $rows = DB::table('project_attachments')
            ->where('project_id', $projectId)
            ->orderBy('id')
            ->get();

        foreach ($rows as $row) {
            $attachment = [
                'id' => (int) $row->id,
                'original_name' => $row->original_name,
                'mime_type' => $row->mime_type,
                'size_bytes' => (int) $row->size_bytes,
                'created_at' => $row->created_at,
            ];

            if ($row->phase_id !== null) {
                $phaseId = (int) $row->phase_id;
                if (array_key_exists($phaseId, $phaseMap)) {
                    $phaseMap[$phaseId][] = $attachment;
                }
            }

            if ($row->deliverable_id !== null) {
                $deliverableId = (int) $row->deliverable_id;
                if (array_key_exists($deliverableId, $deliverableMap)) {
                    $deliverableMap[$deliverableId][] = $attachment;
                }
            }
        }

        return [
            'phases' => $phaseMap,
            'deliverables' => $deliverableMap,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $phases
     * @param  list<array<string, mixed>>  $deliverables
     * @return array{0: list<array<string, mixed>>, 1: list<array<string, mixed>>}
     */
    public function attachToDetail(
        int $projectId,
        array $phases,
        array $deliverables,
        ?int $viewerUserId = null,
    ): array {
        $map = $this->mapForEntities(
            $projectId,
            array_map(static fn (array $phase): int => (int) $phase['id'], $phases),
            array_map(static fn (array $deliverable): int => (int) $deliverable['id'], $deliverables),
        );

        $phaseUnread = $viewerUserId !== null
            ? $this->unreadCountsByPhase($viewerUserId, $projectId, array_keys($map['phases']))
            : [];
        $deliverableUnread = $viewerUserId !== null
            ? $this->unreadCountsByDeliverable($viewerUserId, $projectId, array_keys($map['deliverables']))
            : [];

        $phases = array_map(static function (array $phase) use ($map, $phaseUnread): array {
            $id = (int) $phase['id'];
            $attachments = $map['phases'][$id] ?? [];
            $phase['attachments'] = $attachments;
            $phase['attachments_count'] = count($attachments);
            $phase['unread_attachments_count'] = $phaseUnread[$id] ?? 0;

            return $phase;
        }, $phases);

        $deliverables = array_map(static function (array $deliverable) use ($map, $deliverableUnread): array {
            $id = (int) $deliverable['id'];
            $attachments = $map['deliverables'][$id] ?? [];
            $deliverable['attachments'] = $attachments;
            $deliverable['attachments_count'] = count($attachments);
            $deliverable['unread_attachments_count'] = $deliverableUnread[$id] ?? 0;

            return $deliverable;
        }, $deliverables);

        return [$phases, $deliverables];
    }

    /**
     * @return array<string, mixed>
     */
    public function storeForPhase(int $userAuthId, int $projectId, int $phaseId, UploadedFile $file): array
    {
        $this->assertPhaseBelongsToProject($projectId, $phaseId);
        $this->assertUnderLimit('phase_id', $phaseId);

        $stored = $this->storageService->storeUploadedFile($file);

        $id = (int) DB::table('project_attachments')->insertGetId([
            'project_id' => $projectId,
            'phase_id' => $phaseId,
            'deliverable_id' => null,
            'file_path' => $stored['file_path'],
            'original_name' => $stored['original_name'],
            'mime_type' => $stored['mime_type'],
            'size_bytes' => $stored['size_bytes'],
            'uploaded_by' => $userAuthId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->markPhaseAttachmentsRead($userAuthId, $projectId, $phaseId);

        return $this->findAttachmentRow($projectId, $id);
    }

    /**
     * @return array<string, mixed>
     */
    public function storeForDeliverable(int $userAuthId, int $projectId, int $deliverableId, UploadedFile $file): array
    {
        $this->assertDeliverableBelongsToProject($projectId, $deliverableId);
        $this->assertUnderLimit('deliverable_id', $deliverableId);

        $stored = $this->storageService->storeUploadedFile($file);

        $id = (int) DB::table('project_attachments')->insertGetId([
            'project_id' => $projectId,
            'phase_id' => null,
            'deliverable_id' => $deliverableId,
            'file_path' => $stored['file_path'],
            'original_name' => $stored['original_name'],
            'mime_type' => $stored['mime_type'],
            'size_bytes' => $stored['size_bytes'],
            'uploaded_by' => $userAuthId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->markDeliverableAttachmentsRead($userAuthId, $projectId, $deliverableId);

        return $this->findAttachmentRow($projectId, $id);
    }

    public function markPhaseAttachmentsRead(int $userAuthId, int $projectId, int $phaseId): int
    {
        $this->assertPhaseBelongsToProject($projectId, $phaseId);
        $this->markEntityAttachmentsRead($userAuthId, $projectId, 'phase_id', $phaseId);

        return 0;
    }

    public function markDeliverableAttachmentsRead(int $userAuthId, int $projectId, int $deliverableId): int
    {
        $this->assertDeliverableBelongsToProject($projectId, $deliverableId);
        $this->markEntityAttachmentsRead($userAuthId, $projectId, 'deliverable_id', $deliverableId);

        return 0;
    }

    public function delete(int $projectId, int $attachmentId): void
    {
        $row = DB::table('project_attachments')
            ->where('project_id', $projectId)
            ->where('id', $attachmentId)
            ->first();

        if ($row === null) {
            throw new NotFoundHttpException('El adjunto no existe.');
        }

        $this->storageService->deleteStoredPath($row->file_path);

        DB::table('project_attachments')->where('id', $attachmentId)->delete();
    }

    public function fileResponse(int $projectId, int $attachmentId): BinaryFileResponse
    {
        $row = DB::table('project_attachments')
            ->where('project_id', $projectId)
            ->where('id', $attachmentId)
            ->first();

        if ($row === null) {
            throw new NotFoundHttpException('El adjunto no existe.');
        }

        $absolutePath = $this->storageService->resolveAbsolutePath($row->file_path);

        if ($absolutePath === null) {
            throw new NotFoundHttpException('No se encontró el archivo en el servidor.');
        }

        return response()->file($absolutePath, [
            'Content-Type' => $row->mime_type,
            'Content-Disposition' => 'inline; filename="'.addslashes((string) $row->original_name).'"',
        ]);
    }

    public function assertCollaboratorAssigned(int $userAuthId, int $projectId): void
    {
        $exists = DB::table('project_collaborators')
            ->where('project_id', $projectId)
            ->where('user_auth_id', $userAuthId)
            ->exists();

        if (! $exists) {
            throw new NotFoundHttpException('El proyecto no existe o no tenés acceso.');
        }
    }

    private function assertUnderLimit(string $column, int $entityId): void
    {
        if (! $this->tableExists()) {
            throw ValidationException::withMessages([
                'file' => ['La tabla de adjuntos no está disponible. Ejecutá la migración project_attachments.'],
            ]);
        }

        $count = (int) DB::table('project_attachments')->where($column, $entityId)->count();

        if ($count >= self::MAX_PER_ENTITY) {
            throw ValidationException::withMessages([
                'file' => ['Podés cargar como máximo '.self::MAX_PER_ENTITY.' archivos. Eliminá uno para subir otro.'],
            ]);
        }
    }

    /**
     * @param  list<int|string>  $phaseIds
     * @return array<int, int>
     */
    private function unreadCountsByPhase(int $userAuthId, int $projectId, array $phaseIds): array
    {
        return $this->unreadCountsForColumn($userAuthId, $projectId, 'phase_id', $phaseIds);
    }

    /**
     * @param  list<int|string>  $deliverableIds
     * @return array<int, int>
     */
    private function unreadCountsByDeliverable(int $userAuthId, int $projectId, array $deliverableIds): array
    {
        return $this->unreadCountsForColumn($userAuthId, $projectId, 'deliverable_id', $deliverableIds);
    }

    /**
     * @param  list<int|string>  $entityIds
     * @return array<int, int>
     */
    private function unreadCountsForColumn(
        int $userAuthId,
        int $projectId,
        string $column,
        array $entityIds,
    ): array {
        if (! $this->tableExists() || ! $this->readsTableExists()) {
            return [];
        }

        $ids = collect($entityIds)
            ->map(static fn ($id): int => (int) $id)
            ->filter(static fn (int $id): bool => $id > 0)
            ->unique()
            ->values()
            ->all();

        if ($ids === []) {
            return [];
        }

        $counts = array_fill_keys($ids, 0);

        $rows = DB::table('project_attachments as a')
            ->leftJoin('project_attachment_reads as r', function ($join) use ($userAuthId, $column): void {
                $join->on('r.'.$column, '=', 'a.'.$column)
                    ->where('r.user_auth_id', '=', $userAuthId);
            })
            ->where('a.project_id', $projectId)
            ->whereIn('a.'.$column, $ids)
            ->where('a.uploaded_by', '!=', $userAuthId)
            ->whereRaw('a.id > COALESCE(r.last_read_attachment_id, 0)')
            ->groupBy('a.'.$column)
            ->selectRaw('a.'.$column.' as entity_id, COUNT(*) as unread_count')
            ->get();

        foreach ($rows as $row) {
            $counts[(int) $row->entity_id] = (int) $row->unread_count;
        }

        return $counts;
    }

    private function markEntityAttachmentsRead(
        int $userAuthId,
        int $projectId,
        string $column,
        int $entityId,
    ): void {
        if (! $this->tableExists() || ! $this->readsTableExists()) {
            return;
        }

        $maxId = DB::table('project_attachments')
            ->where('project_id', $projectId)
            ->where($column, $entityId)
            ->max('id');

        $now = now();
        $existing = DB::table('project_attachment_reads')
            ->where('user_auth_id', $userAuthId)
            ->where($column, $entityId)
            ->first();

        $payload = [
            'project_id' => $projectId,
            'phase_id' => $column === 'phase_id' ? $entityId : null,
            'deliverable_id' => $column === 'deliverable_id' ? $entityId : null,
            'last_read_attachment_id' => $maxId !== null ? (int) $maxId : null,
            'last_read_at' => $now,
            'updated_at' => $now,
        ];

        if ($existing !== null) {
            DB::table('project_attachment_reads')
                ->where('id', $existing->id)
                ->update($payload);

            return;
        }

        DB::table('project_attachment_reads')->insert([
            ...$payload,
            'user_auth_id' => $userAuthId,
            'created_at' => $now,
        ]);
    }

    private function readsTableExists(): bool
    {
        return Schema::hasTable('project_attachment_reads');
    }

    private function assertPhaseBelongsToProject(int $projectId, int $phaseId): void
    {
        $exists = DB::table('project_phases')
            ->where('id', $phaseId)
            ->where('project_id', $projectId)
            ->exists();

        if (! $exists) {
            throw new NotFoundHttpException('La fase no existe en este proyecto.');
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

    /**
     * @return array<string, mixed>
     */
    private function findAttachmentRow(int $projectId, int $attachmentId): array
    {
        $row = DB::table('project_attachments')
            ->where('project_id', $projectId)
            ->where('id', $attachmentId)
            ->first();

        if ($row === null) {
            throw new NotFoundHttpException('El adjunto no existe.');
        }

        return [
            'id' => (int) $row->id,
            'project_id' => (int) $row->project_id,
            'phase_id' => $row->phase_id !== null ? (int) $row->phase_id : null,
            'deliverable_id' => $row->deliverable_id !== null ? (int) $row->deliverable_id : null,
            'original_name' => $row->original_name,
            'mime_type' => $row->mime_type,
            'size_bytes' => (int) $row->size_bytes,
            'created_at' => $row->created_at,
        ];
    }

    private function tableExists(): bool
    {
        return Schema::hasTable('project_attachments');
    }
}
