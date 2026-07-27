<?php

namespace App\Mail;

use App\Enums\MailTemplate;

class GoalObjectiveCompletedMail extends ImpulsaMailable
{
    public function __construct(
        private readonly string $recipientEmail,
        private readonly ?int $userAuthId,
        private readonly string $userName,
        private readonly string $goalTitle,
        private readonly string $objectiveTitle,
        private readonly int $progressPercent,
        private readonly string $progressDetail,
        private readonly int $remainingObjectives,
        private readonly string $dueDateLabel,
        private readonly string $metasUrl,
        private readonly int $goalId,
        private readonly int $objectiveId,
    ) {}

    public function mailTemplate(): MailTemplate
    {
        return MailTemplate::GoalObjectiveCompleted;
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
        return 'Objetivo completado · ' . $this->goalTitle;
    }

    public function htmlView(): string
    {
        return 'mail.goal-objective-completed';
    }

    public function textView(): string
    {
        return 'mail.goal-objective-completed-text';
    }

    public function viewData(): array
    {
        return [
            'title' => $this->subjectLine(),
            'userName' => $this->userName,
            'goalTitle' => $this->goalTitle,
            'objectiveTitle' => $this->objectiveTitle,
            'progressPercent' => $this->progressPercent,
            'progressDetail' => $this->progressDetail,
            'remainingObjectives' => $this->remainingObjectives,
            'dueDateLabel' => $this->dueDateLabel,
            'metasUrl' => $this->metasUrl,
        ];
    }

    public function mailMeta(): array
    {
        return [
            'goal_id' => $this->goalId,
            'objective_id' => $this->objectiveId,
        ];
    }
}
