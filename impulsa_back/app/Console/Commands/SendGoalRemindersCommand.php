<?php

namespace App\Console\Commands;

use App\Services\Goals\UserGoalsService;
use Illuminate\Console\Command;

class SendGoalRemindersCommand extends Command
{
    protected $signature = 'goals:send-reminders';

    protected $description = 'Envía recordatorios de vencimiento próximo y vencido para metas y objetivos personales';

    public function handle(UserGoalsService $goalsService): int
    {
        $sent = $goalsService->sendDueReminders();

        $this->info("Recordatorios de metas enviados: {$sent}");

        return self::SUCCESS;
    }
}
