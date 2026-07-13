<?php

namespace App\Services\Admin;

use App\Mail\ProjectProgressUpdateMail;
use App\Models\Project;
use App\Models\UserAuth;
use App\Services\Mail\ImpulsaMailService;
use App\Support\ProjectLabels;
use Illuminate\Support\Facades\DB;

class ProjectClientNotificationService
{
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

        $progressSnapshot = [
            'progress_percent' => (int) ($progress['progress_percent'] ?? $project->progress_percent),
            'progress_detail' => (string) ($progress['progress_detail'] ?? ''),
        ];

        $actorId = $createdByUserId ?? (int) ($project->manager_user_id ?: 0);

        if ($actorId > 0) {
            DB::table('project_updates')->insert([
                'project_id' => $project->id,
                'phase_id' => $phaseId,
                'created_by' => $actorId,
                'title' => $updateTitle,
                'message' => $this->buildUpdateRecordMessage($updateMessage, $changeLines, $progressSnapshot),
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
                updateTitle: $updateTitle,
                updateMessage: $updateMessage,
                changeLines: $changeLines,
                progressPercent: $progressSnapshot['progress_percent'],
                progressDetail: $progressSnapshot['progress_detail'],
                statusLabel: ProjectLabels::statusLabel($project->status),
                dashboardUrl: $this->clientDashboardUrl(),
                projectId: (int) $project->id,
            ),
        );
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
}
