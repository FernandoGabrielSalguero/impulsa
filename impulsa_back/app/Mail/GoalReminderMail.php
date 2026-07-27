<?php

namespace App\Mail;

use App\Enums\MailTemplate;

class GoalReminderMail extends ImpulsaMailable
{
    public function __construct(
        private readonly string $recipientEmail,
        private readonly ?int $userAuthId,
        private readonly string $userName,
        private readonly string $goalTitle,
        private readonly ?string $objectiveTitle,
        private readonly string $reminderKind,
        private readonly int $progressPercent,
        private readonly string $progressDetail,
        private readonly string $dueDateLabel,
        private readonly string $metasUrl,
    ) {}

    public function mailTemplate(): MailTemplate
    {
        return MailTemplate::GoalReminder;
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
        $prefix = $this->reminderKind === 'upcoming_1d' ? 'Vence mañana' : 'Vencimiento superado';

        return $prefix . ' · ' . $this->goalTitle;
    }

    public function htmlView(): string
    {
        return 'mail.goal-reminder';
    }

    public function textView(): string
    {
        return 'mail.goal-reminder-text';
    }

    public function viewData(): array
    {
        return [
            'title' => $this->subjectLine(),
            'userName' => $this->userName,
            'goalTitle' => $this->goalTitle,
            'objectiveTitle' => $this->objectiveTitle,
            'reminderKind' => $this->reminderKind,
            'reminderLabel' => $this->reminderKind === 'upcoming_1d' ? 'Vence mañana' : 'Vencido',
            'progressPercent' => $this->progressPercent,
            'progressDetail' => $this->progressDetail,
            'dueDateLabel' => $this->dueDateLabel,
            'metasUrl' => $this->metasUrl,
        ];
    }

    public function mailMeta(): array
    {
        return [
            'reminder_kind' => $this->reminderKind,
            'goal_title' => $this->goalTitle,
        ];
    }
}
