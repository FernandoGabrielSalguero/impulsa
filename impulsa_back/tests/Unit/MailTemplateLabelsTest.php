<?php

namespace Tests\Unit;

use App\Enums\MailTemplate;
use App\Support\MailTemplateLabels;
use PHPUnit\Framework\TestCase;

class MailTemplateLabelsTest extends TestCase
{
    public function test_goal_templates_have_labels(): void
    {
        $this->assertSame('Meta completada', MailTemplateLabels::labelFor(MailTemplate::GoalCompleted->value));
        $this->assertSame('Objetivo de meta completado', MailTemplateLabels::labelFor(MailTemplate::GoalObjectiveCompleted->value));
        $this->assertSame('Recordatorio de meta', MailTemplateLabels::labelFor(MailTemplate::GoalReminder->value));
    }
}
