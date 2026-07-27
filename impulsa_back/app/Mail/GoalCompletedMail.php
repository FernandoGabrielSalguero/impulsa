<?php

namespace App\Mail;

use App\Enums\MailTemplate;

class GoalCompletedMail extends ImpulsaMailable
{
    /**
     * @param  list<string>  $completedObjectives
     */
    public function __construct(
        private readonly string $recipientEmail,
        private readonly ?int $userAuthId,
        private readonly string $userName,
        private readonly string $goalTitle,
        private readonly string $startDateLabel,
        private readonly string $completedDateLabel,
        private readonly array $completedObjectives,
        private readonly string $progressDetail,
        private readonly string $metasUrl,
        private readonly int $goalId,
    ) {}

    public function mailTemplate(): MailTemplate
    {
        return MailTemplate::GoalCompleted;
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
        return 'Meta completada · ' . $this->goalTitle;
    }

    public function htmlView(): string
    {
        return 'mail.goal-completed';
    }

    public function textView(): string
    {
        return 'mail.goal-completed-text';
    }

    public function viewData(): array
    {
        return [
            'title' => $this->subjectLine(),
            'userName' => $this->userName,
            'goalTitle' => $this->goalTitle,
            'startDateLabel' => $this->startDateLabel,
            'completedDateLabel' => $this->completedDateLabel,
            'completedObjectives' => $this->completedObjectives,
            'progressDetail' => $this->progressDetail,
            'metasUrl' => $this->metasUrl,
        ];
    }

    public function mailMeta(): array
    {
        return [
            'goal_id' => $this->goalId,
        ];
    }
}
