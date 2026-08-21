<?php

namespace App\Services\Admin;

use App\Mail\ProjectProgressUpdateMail;
use App\Models\Project;
use App\Models\ProjectCollaborator;
use App\Models\UserAuth;
use App\Services\Mail\ImpulsaMailService;
use App\Services\Notifications\NotificationService;
use App\Support\NotificationCopy;
use App\Support\ProjectLabels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProjectClientNotificationService
{
    private const BUFFER_TTL_SECONDS = 21600;

    public function __construct(
        private readonly ImpulsaMailService $mailService,
        private readonly NotificationService $notificationService,
    ) {}

    /**
     * @param  list<string>  $changeLines
     * @param  array{progress_percent?: int, progress_detail?: string}|null  $progress
     */
    public function notify(
        Project $project,
        string $updateTitle,
        string $updateMessage,
        array $changeLines,
        ?int $createdByUserId = null,
        ?int $phaseId = null,
        ?array $progress = null,
    ): ?bool {
        $project->refresh();

        if (! $project->client_visible) {
            return null;
        }

        if ($changeLines === [] && trim($updateMessage) === '') {
            return null;
        }

        $progressSnapshot = [
            'progress_percent' => (int) ($progress['progress_percent'] ?? $project->progress_percent),
            'progress_detail' => (string) ($progress['progress_detail'] ?? ''),
        ];

        $actorId = $createdByUserId
            ?: (int) ($project->manager_user_id ?: 0);

        if ($actorId > 0) {
            DB::table('project_updates')->insert([
                'project_id' => $project->id,
                'phase_id' => $phaseId,
                'created_by' => $actorId,
                'title' => $updateTitle,
                'message' => $this->buildUpdateRecordMessage(
                    trim($updateMessage),
                    $changeLines,
                    $progressSnapshot,
                ),
                'progress_delta' => null,
                'visible_to_client' => true,
                'created_at' => now(),
            ]);
        }

        if ($project->client_user_id) {
            $copy = NotificationCopy::projectUpdatedForClient(
                (string) $project->project_name,
                $updateTitle,
            );

            $this->notificationService->notifyMany(
                [(int) $project->client_user_id],
                NotificationService::TYPE_PROJECT_CLIENT_UPDATE,
                $copy['title'],
                $copy['body'],
                [
                    'project_id' => (int) $project->id,
                    'phase_id' => $phaseId,
                    'actor_user_id' => $actorId > 0 ? $actorId : null,
                ],
            );
        }

        $clientEmail = strtolower(trim((string) $project->client_email));

        if ($clientEmail === '' || ! filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $this->appendChange((int) $project->id, [
            'update_title' => $updateTitle,
            'update_message' => trim($updateMessage),
            'change_lines' => $changeLines,
            'phase_id' => $phaseId,
            'created_by_user_id' => $createdByUserId,
            'progress' => $progressSnapshot,
        ]);

        return null;
    }

    public function flush(
        Project $project,
        ?int $actorUserId = null,
        bool $notifyClient = true,
        bool $notifyCollaborators = false,
    ): array {
        $empty = [
            'client_email_sent' => null,
            'collaborators_email_sent' => null,
            'collaborators_notified' => 0,
        ];

        if (! $notifyClient && ! $notifyCollaborators) {
            return $empty;
        }

        $buffer = Cache::get($this->bufferKey((int) $project->id));

        if (! is_array($buffer) || ($buffer['sections'] ?? []) === []) {
            return $empty;
        }

        Cache::forget($this->bufferKey((int) $project->id));

        $project->refresh();

        $aggregated = $this->aggregateSections($buffer['sections']);
        $progressSnapshot = is_array($buffer['progress'] ?? null)
            ? [
                'progress_percent' => (int) ($buffer['progress']['progress_percent'] ?? $project->progress_percent),
                'progress_detail' => (string) ($buffer['progress']['progress_detail'] ?? ''),
            ]
            : [
                'progress_percent' => (int) $project->progress_percent,
                'progress_detail' => '',
            ];

        $clientEmailSent = null;

        if ($notifyClient) {
            $clientEmailSent = $this->sendClientMail($project, $aggregated, $progressSnapshot);
        }

        $collaboratorsEmailSent = null;
        $collaboratorsNotified = 0;

        if ($notifyCollaborators) {
            [$collaboratorsEmailSent, $collaboratorsNotified] = $this->sendCollaboratorMails(
                $project,
                $aggregated,
                $progressSnapshot,
            );
        }

        return [
            'client_email_sent' => $clientEmailSent,
            'collaborators_email_sent' => $collaboratorsEmailSent,
            'collaborators_notified' => $collaboratorsNotified,
        ];
    }

    /**
     * @param  array{update_title: string, update_message: string, change_lines: list<string>}  $aggregated
     * @param  array{progress_percent: int, progress_detail: string}  $progressSnapshot
     */
    private function sendClientMail(Project $project, array $aggregated, array $progressSnapshot): ?bool
    {
        if (! $project->client_visible) {
            return null;
        }

        $clientEmail = strtolower(trim((string) $project->client_email));

        if ($clientEmail === '' || ! filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $clientUser = $project->client_user_id
            ? UserAuth::query()->with('info')->find($project->client_user_id)
            : null;

        return $this->mailService->send(
            new ProjectProgressUpdateMail(
                recipientEmail: $clientEmail,
                userAuthId: $clientUser?->id,
                clientName: $this->resolveClientName($project, $clientUser),
                projectName: (string) $project->project_name,
                updateTitle: $aggregated['update_title'],
                updateMessage: $aggregated['update_message'],
                changeLines: $aggregated['change_lines'],
                progressPercent: $progressSnapshot['progress_percent'],
                progressDetail: $progressSnapshot['progress_detail'],
                statusLabel: ProjectLabels::statusLabel($project->status),
                dashboardUrl: $this->frontendPathUrl('/cliente/dashboard'),
                projectId: (int) $project->id,
            ),
        );
    }

    /**
     * @param  array{update_title: string, update_message: string, change_lines: list<string>}  $aggregated
     * @param  array{progress_percent: int, progress_detail: string}  $progressSnapshot
     * @return array{0: bool|null, 1: int}
     */
    private function sendCollaboratorMails(Project $project, array $aggregated, array $progressSnapshot): array
    {
        $collaboratorIds = ProjectCollaborator::query()
            ->where('project_id', $project->id)
            ->pluck('user_auth_id')
            ->map(static fn ($id): int => (int) $id)
            ->all();

        if ($collaboratorIds === []) {
            return [null, 0];
        }

        $collaborators = UserAuth::query()
            ->with('info')
            ->whereIn('id', $collaboratorIds)
            ->get();

        $attempted = 0;
        $sent = 0;
        $failed = 0;

        foreach ($collaborators as $collaborator) {
            $email = strtolower(trim((string) $collaborator->correo));

            if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $attempted++;
            $result = $this->mailService->send(
                new ProjectProgressUpdateMail(
                    recipientEmail: $email,
                    userAuthId: (int) $collaborator->id,
                    clientName: $this->resolveUserDisplayName($collaborator, 'Colaborador'),
                    projectName: (string) $project->project_name,
                    updateTitle: $aggregated['update_title'],
                    updateMessage: $aggregated['update_message'],
                    changeLines: $aggregated['change_lines'],
                    progressPercent: $progressSnapshot['progress_percent'],
                    progressDetail: $progressSnapshot['progress_detail'],
                    statusLabel: ProjectLabels::statusLabel($project->status),
                    dashboardUrl: $this->frontendPathUrl('/colaborador'),
                    projectId: (int) $project->id,
                ),
            );

            if ($result === true) {
                $sent++;
            } else {
                $failed++;
            }
        }

        if ($attempted === 0) {
            return [null, 0];
        }

        return [$failed === 0, $sent];
    }

    public function discard(Project $project): void
    {
        Cache::forget($this->bufferKey((int) $project->id));
    }

    /**
     * @param  array{
     *   update_title: string,
     *   update_message: string,
     *   change_lines: list<string>,
     *   phase_id: ?int,
     *   created_by_user_id: ?int,
     *   progress: array{progress_percent?: int, progress_detail?: string},
     * }  $change
     */
    private function appendChange(int $projectId, array $change): void
    {
        $key = $this->bufferKey($projectId);
        $buffer = Cache::get($key, [
            'project_id' => $projectId,
            'created_by_user_id' => null,
            'sections' => [],
            'progress' => null,
        ]);

        $buffer['sections'][] = [
            'update_title' => $change['update_title'],
            'update_message' => $change['update_message'],
            'change_lines' => $change['change_lines'],
            'phase_id' => $change['phase_id'],
        ];

        if (($change['created_by_user_id'] ?? 0) > 0) {
            $buffer['created_by_user_id'] = $change['created_by_user_id'];
        }

        $buffer['progress'] = $change['progress'];

        Cache::put($key, $buffer, self::BUFFER_TTL_SECONDS);
    }

    /**
     * @param  list<array{update_title: string, update_message: string, change_lines: list<string>, phase_id: ?int}>  $sections
     * @return array{update_title: string, update_message: string, change_lines: list<string>, phase_id: ?int}
     */
    private function aggregateSections(array $sections): array
    {
        if (count($sections) === 1) {
            return [
                'update_title' => $sections[0]['update_title'],
                'update_message' => $sections[0]['update_message'],
                'change_lines' => $sections[0]['change_lines'],
                'phase_id' => $sections[0]['phase_id'],
            ];
        }

        $changeLines = [];

        foreach ($sections as $section) {
            $changeLines[] = '— ' . $section['update_title'] . ' —';

            if ($section['change_lines'] !== []) {
                foreach ($section['change_lines'] as $line) {
                    $changeLines[] = $line;
                }
            } elseif ($section['update_message'] !== '') {
                $changeLines[] = $section['update_message'];
            }
        }

        return [
            'update_title' => 'Actualizaciones en tu proyecto',
            'update_message' => 'Realizamos varias actualizaciones en tu proyecto. Este es el detalle:',
            'change_lines' => $changeLines,
            'phase_id' => null,
        ];
    }

    /**
     * @param  array{progress_percent?: int, progress_detail?: string}  $progress
     * @param  list<string>  $changeLines
     */
    private function buildUpdateRecordMessage(string $updateMessage, array $changeLines, array $progress): string
    {
        $parts = array_filter([
            $updateMessage,
            $changeLines !== [] ? implode("\n", $changeLines) : null,
            'Avance actual: ' . (int) ($progress['progress_percent'] ?? 0) . '%.',
            filled($progress['progress_detail'] ?? null) ? (string) $progress['progress_detail'] : null,
        ]);

        return implode("\n\n", $parts);
    }

    private function resolveClientName(Project $project, ?UserAuth $clientUser): string
    {
        $name = trim((string) $project->client_name);

        if ($name !== '' && $name !== 'Cliente') {
            return $name;
        }

        if ($clientUser !== null) {
            $fullName = trim((string) (($clientUser->info?->nombre ?? '') . ' ' . ($clientUser->info?->apellido ?? '')));

            if ($fullName !== '') {
                return $fullName;
            }

            $apodo = trim((string) ($clientUser->info?->apodo ?? ''));

            if ($apodo !== '') {
                return $apodo;
            }
        }

        return 'Cliente';
    }

    private function resolveUserDisplayName(UserAuth $user, string $fallback): string
    {
        $fullName = trim((string) (($user->info?->nombre ?? '') . ' ' . ($user->info?->apellido ?? '')));

        if ($fullName !== '') {
            return $fullName;
        }

        $apodo = trim((string) ($user->info?->apodo ?? ''));

        if ($apodo !== '') {
            return $apodo;
        }

        return $fallback;
    }

    private function frontendPathUrl(string $path): string
    {
        $base = rtrim((string) config('impulsa.frontend_url'), '/');
        $appPath = trim((string) config('impulsa.frontend_app_path', ''), '/');
        $normalized = '/' . ltrim($path, '/');

        if ($appPath !== '') {
            return $base . '/' . $appPath . $normalized;
        }

        return $base . $normalized;
    }

    private function bufferKey(int $projectId): string
    {
        return 'project_client_notification_buffer:' . $projectId;
    }
}
