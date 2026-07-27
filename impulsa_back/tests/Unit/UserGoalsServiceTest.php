<?php

namespace Tests\Unit;

use App\Models\UserGoal;
use App\Models\UserGoalObjective;
use App\Services\Goals\UserGoalsService;
use App\Services\Mail\ImpulsaMailService;
use App\Services\Notifications\NotificationService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserGoalsServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    public function test_sync_goal_progress_marks_goal_completed_when_all_objectives_done(): void
    {
        $service = app(UserGoalsService::class);

        $goal = UserGoal::query()->create([
            'user_auth_id' => 1,
            'title' => 'Meta test',
            'status' => 'pending',
            'progress_percent' => 0,
        ]);

        UserGoalObjective::query()->create([
            'goal_id' => $goal->id,
            'title' => 'Obj 1',
            'status' => 'completed',
            'sort_order' => 1,
            'completed_at' => now(),
        ]);

        UserGoalObjective::query()->create([
            'goal_id' => $goal->id,
            'title' => 'Obj 2',
            'status' => 'completed',
            'sort_order' => 2,
            'completed_at' => now(),
        ]);

        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('syncGoalProgress');
        $method->setAccessible(true);
        $method->invoke($service, $goal->fresh(['objectives']));

        $goal->refresh();

        $this->assertSame('completed', $goal->status);
        $this->assertSame(100, $goal->progress_percent);
        $this->assertNotNull($goal->completed_at);
    }

    public function test_reopening_objective_moves_goal_back_to_in_progress(): void
    {
        $service = app(UserGoalsService::class);

        $goal = UserGoal::query()->create([
            'user_auth_id' => 1,
            'title' => 'Meta test',
            'status' => 'completed',
            'progress_percent' => 100,
            'completed_at' => now(),
        ]);

        UserGoalObjective::query()->create([
            'goal_id' => $goal->id,
            'title' => 'Obj 1',
            'status' => 'completed',
            'sort_order' => 1,
            'completed_at' => now(),
        ]);

        UserGoalObjective::query()->create([
            'goal_id' => $goal->id,
            'title' => 'Obj 2',
            'status' => 'in_progress',
            'sort_order' => 2,
        ]);

        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('syncGoalProgress');
        $method->setAccessible(true);
        $method->invoke($service, $goal->fresh(['objectives']));

        $goal->refresh();

        $this->assertSame('in_progress', $goal->status);
        $this->assertSame(50, $goal->progress_percent);
        $this->assertNull($goal->completed_at);
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('user_goal_objectives');
        Schema::dropIfExists('user_goals');

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

        $this->app->instance(NotificationService::class, \Mockery::mock(NotificationService::class));
        $this->app->instance(ImpulsaMailService::class, \Mockery::mock(ImpulsaMailService::class));
    }
}
