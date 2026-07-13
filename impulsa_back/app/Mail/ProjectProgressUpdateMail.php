<?php

namespace App\Mail;

use App\Enums\MailTemplate;

class ProjectProgressUpdateMail extends ImpulsaMailable
{
    /**
     * @param  list<string>  $changeLines
     */
    public function __construct(
        private readonly string $recipientEmail,
        private readonly ?int $userAuthId,
        private readonly string $clientName,
        private readonly string $projectName,
        private readonly string $updateTitle,
        private readonly string $updateMessage,
        private readonly array $changeLines,
        private readonly int $progressPercent,
        private readonly string $progressDetail,
        private readonly string $statusLabel,
        private readonly string $dashboardUrl,
        private readonly int $projectId,
    ) {}

    public function mailTemplate(): MailTemplate
    {
        return MailTemplate::ProjectProgressUpdate;
    }

    public function recipientEmail(): string
    {
        return $this->recipientEmail;
    }

    public function userAuthId(): ?int
    {
        return $this->userAuthId;
    }

    public function subjectLine(): string
    {
        return 'Actualización de tu proyecto · ' . $this->projectName;
    }

    public function htmlView(): string
    {
        return 'mail.project-progress-update';
    }

    public function textView(): string
    {
        return 'mail.project-progress-update-text';
    }

    public function viewData(): array
    {
        return [
            'title' => $this->subjectLine(),
            'clientName' => $this->clientName,
            'projectName' => $this->projectName,
            'updateTitle' => $this->updateTitle,
            'updateMessage' => $this->updateMessage,
            'changeLines' => $this->changeLines,
            'progressPercent' => $this->progressPercent,
            'progressDetail' => $this->progressDetail,
            'statusLabel' => $this->statusLabel,
            'dashboardUrl' => $this->dashboardUrl,
            'projectId' => $this->projectId,
        ];
    }

    public function mailMeta(): array
    {
        return [
            'project_id' => $this->projectId,
            'update_title' => $this->updateTitle,
        ];
    }
}
