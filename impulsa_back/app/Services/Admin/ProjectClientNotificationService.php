<?php

namespace App\Services\Admin;

use App\Mail\ProjectProgressUpdateMail;
use App\Models\Project;
use App\Models\UserAuth;
use App\Services\Mail\ImpulsaMailService;
use App\Support\ProjectLabels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProjectClientNotificationService
{
    private const BUFFER_TTL_SECONDS = 21600;

    public function __construct(
        private readonly ImpulsaMailService $mailService,
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

        $clientEmail = strtolower(trim((string) $project->client_email));

        if ($clientEmail === '' || ! filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        if ($changeLines === [] && trim($updateMessage) === '') {
            return null;
        }

        $progressSnapshot = [
            'progress_percent' => (int) ($progress['progress_percent'] ?? $project->progress_percent),
            'progress_detail' => (string) ($progress['progress_detail'] ?? ''),
        ];

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

    public function flush(Project $project, ?int $actorUserId = null): ?bool
    {
        $buffer = Cache::pull($this->bufferKey((int) $project->id));

        if (! is_array($buffer) || ($buffer['sections'] ?? []) === []) {
            return null;
        }

        $project->refresh();

        if (! $project->client_visible) {
            return null;
        }

        $clientEmail = strtolower(trim((string) $project->client_email));

        if ($clientEmail === '' || ! filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $sections = $buffer['sections'];
        $aggregated = $this->aggregateSections($sections);
        $progressSnapshot = is_array($buffer['progress'] ?? null)
            ? [
                'progress_percent' => (int) ($buffer['progress']['progress_percent'] ?? $project->progress_percent),
                'progress_detail' => (string) ($buffer['progress']['progress_detail'] ?? ''),
            ]
            : [
                'progress_percent' => (int) $project->progress_percent,
                'progress_detail' => '',
            ];

        $actorId = $actorUserId
            ?? (int) ($buffer['created_by_user_id'] ?? 0)
            ?: (int) ($project->manager_user_id ?: 0);

        if ($actorId > 0) {
            DB::table('project_updates')->insert([
                'project_id' => $project->id,
                'phase_id' => $aggregated['phase_id'],
                'created_by' => $actorId,
                'title' => $aggregated['update_title'],
                'message' => $this->buildUpdateRecordMessage(
                    $aggregated['update_message'],
                    $aggregated['change_lines'],
                    $progressSnapshot,
                ),
                'progress_delta' => null,
                'visible_to_client' => true,
                'created_at' => now(),
            ]);
        }

        $clientUser = $project->client_user_id
            ? UserAuth::query()->with('info')->find($project->client_user_id)
            : null;

        $clientName = $this->resolveClientName($project, $clientUser);

        return $this->mailService->send(
            new ProjectProgressUpdateMail(
                recipientEmail: $clientEmail,
                userAuthId: $clientUser?->id,
                clientName: $clientName,
                projectName: (string) $project->project_name,
                updateTitle: $aggregated['update_title'],
                updateMessage: $aggregated['update_message'],
                changeLines: $aggregated['change_lines'],
                progressPercent: $progressSnapshot['progress_percent'],
                progressDetail: $progressSnapshot['progress_detail'],
                statusLabel: ProjectLabels::statusLabel($project->status),
                dashboardUrl: $this->clientDashboardUrl(),
                projectId: (int) $project->id,
            ),
        );
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

    private function clientDashboardUrl(): string
    {
        $base = rtrim((string) config('impulsa.frontend_url'), '/');
        $appPath = trim((string) config('impulsa.frontend_app_path', ''), '/');

        if ($appPath !== '') {
            return $base . '/' . $appPath . '/cliente/dashboard';
        }

        return $base . '/cliente/dashboard';
    }

    private function bufferKey(int $projectId): string
    {
        return 'project_client_notification_buffer:' . $projectId;
    }
}
