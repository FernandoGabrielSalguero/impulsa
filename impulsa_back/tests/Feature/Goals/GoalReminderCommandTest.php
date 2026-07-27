<?php

namespace Tests\Feature\Goals;

use App\Models\UserAuth;
use App\Models\UserGoal;
use App\Models\UserGoalObjective;
use App\Models\UserInfo;
use App\Services\Goals\UserGoalsService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GoalReminderCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    public function test_reminder_command_does_not_duplicate_same_day(): void
    {
        Mail::fake();

        $user = $this->createUser('emprendedor@test.com', 'impulsa_emprendedor');

        $goal = UserGoal::query()->create([
            'user_auth_id' => $user->id,
            'title' => 'Meta vencida',
            'status' => 'in_progress',
            'due_date' => now()->subDay()->toDateString(),
            'progress_percent' => 0,
        ]);

        UserGoalObjective::query()->create([
            'goal_id' => $goal->id,
            'title' => 'Objetivo',
            'status' => 'pending',
            'sort_order' => 1,
        ]);

        $this->artisan('goals:send-reminders')->assertSuccessful();
        $this->artisan('goals:send-reminders')->assertSuccessful();

        $this->assertSame(1, \App\Models\UserGoalReminderLog::query()->where('entity_type', 'goal')->count());
        $this->assertDatabaseHas('user_notifications', [
            'user_auth_id' => $user->id,
            'type' => 'goals.reminder_overdue',
        ]);
    }

    public function test_upcoming_reminder_is_sent_for_tomorrow_due_date(): void
    {
        Mail::fake();

        $user = $this->createUser('emprendedor@test.com', 'impulsa_emprendedor');

        UserGoal::query()->create([
            'user_auth_id' => $user->id,
            'title' => 'Meta proxima',
            'status' => 'in_progress',
            'due_date' => now()->addDay()->toDateString(),
            'progress_percent' => 0,
        ]);

        $sent = app(UserGoalsService::class)->sendDueReminders();

        $this->assertSame(1, $sent);
        $this->assertDatabaseHas('user_notifications', [
            'user_auth_id' => $user->id,
            'type' => 'goals.reminder_upcoming',
        ]);
    }

    private function createUser(string $email, string $role): UserAuth
    {
        $user = UserAuth::query()->create([
            'correo' => $email,
            'password' => bcrypt('secret'),
            'rol' => $role,
            'email_verified_at' => now(),
        ]);

        UserInfo::query()->create([
            'user_auth_id' => $user->id,
            'nombre' => 'Test',
            'apellido' => 'User',
        ]);

        return $user;
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('user_goal_reminder_logs');
        Schema::dropIfExists('user_goal_objectives');
        Schema::dropIfExists('user_goals');
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('correos_log');

        Schema::create('user_auth', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('correo')->unique();
            $table->string('password');
            $table->string('rol');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('user_info', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_auth_id');
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->timestamps();
        });

        Schema::create('user_goals', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status', 20)->default('pending');
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('user_goal_objectives', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('goal_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status', 20)->default('pending');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('user_goal_reminder_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->string('entity_type', 20);
            $table->unsignedBigInteger('entity_id');
            $table->string('reminder_kind', 20);
            $table->date('sent_on');
            $table->timestamps();
        });

        Schema::create('user_notifications', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->string('type');
            $table->string('title');
            $table->text('body')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('correos_log', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_auth_id')->nullable();
            $table->string('correo');
            $table->string('asunto');
            $table->string('template', 100)->nullable();
            $table->longText('mensaje_html')->nullable();
            $table->text('mensaje_text')->nullable();
            $table->string('estado', 20)->default('fallido');
            $table->text('error')->nullable();
            $table->longText('meta')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }
}
