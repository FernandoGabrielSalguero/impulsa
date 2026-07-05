<?php

namespace App\Mail;

use App\Enums\MailTemplate;
use App\Models\ApiIntegration;
use App\Models\UserAuth;
use App\Models\WebsiteSubscription;
use App\Models\WebsiteSubscriptionPeriod;

class SubscriptionPaymentReminderMail extends ImpulsaMailable
{
    public function __construct(
        private readonly UserAuth $user,
        private readonly WebsiteSubscription $subscription,
        private readonly WebsiteSubscriptionPeriod $period,
        private readonly ApiIntegration $integration,
        private readonly ?string $paymentUrl = null,
    ) {}

    public function mailTemplate(): MailTemplate
    {
        return MailTemplate::SubscriptionPaymentReminder;
    }

    public function recipientEmail(): string
    {
        return $this->user->correo;
    }

    public function userAuthId(): ?int
    {
        return $this->user->id;
    }

    public function subjectLine(): string
    {
        return 'Recordatorio de pago — ' . $this->integration->project_name;
    }

    public function htmlView(): string
    {
        return 'mail.subscription-payment-reminder';
    }

    public function textView(): string
    {
        return 'mail.subscription-payment-reminder-text';
    }

    public function viewData(): array
    {
        return [
            'title' => $this->subjectLine(),
            'project_name' => $this->integration->project_name,
            'period' => $this->period->periodKey(),
            'amount' => number_format((float) $this->period->amount, 2, ',', '.'),
            'payment_url' => $this->paymentUrl ?? config('mercadopago.subscription_plan_url'),
        ];
    }

    public function mailMeta(): array
    {
        return [
            'website_subscription_id' => $this->subscription->id,
            'period' => $this->period->periodKey(),
        ];
    }
}
