<?php

namespace App\Console\Commands;

use App\Services\WebsiteSubscription\SubscriptionNotificationService;
use Illuminate\Console\Command;

class SendSubscriptionRemindersCommand extends Command
{
    protected $signature = 'subscriptions:send-reminders';

    protected $description = 'Envía recordatorios de pago de suscripciones web cada 2 días (días 6, 8, 10, 12, 14)';

    public function handle(SubscriptionNotificationService $notificationService): int
    {
        $sent = $notificationService->sendDueReminders();

        $this->info("Recordatorios enviados: {$sent}");

        return self::SUCCESS;
    }
}
