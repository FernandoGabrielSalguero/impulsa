<?php

namespace Tests\Feature\Emprendedor;

use App\Models\UserAuth;
use App\Models\UserInfo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GoalsControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    public function test_user_can_create_goal_with_objectives_and_complete_them(): void
    {
        Mail::fake();

        $user = $this->createUser('emprendedor@test.com', 'impulsa_emprendedor');

        $createGoal = $this->actingAs($user)->postJson('/api/v1/emprendedor/metas', [
            'title' => 'Lanzar producto',
            'description' => 'Plan comercial Q3',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
        ]);

        $createGoal->assertCreated();
        $goalId = (int) $createGoal->json('data.id');

        $this->actingAs($user)->postJson('/api/v1/emprendedor/metas/' . $goalId . '/objectives', [
            'title' => 'Definir pricing',
        ])->assertCreated();

        $objectiveTwo = $this->actingAs($user)->postJson('/api/v1/emprendedor/metas/' . $goalId . '/objectives', [
            'title' => 'Publicar landing',
        ]);
        $objectiveTwo->assertCreated();
        $objectiveOneId = (int) DB::table('user_goal_objectives')->where('goal_id', $goalId)->orderBy('id')->value('id');
        $objectiveTwoId = (int) $objectiveTwo->json('data.id');

        $this->actingAs($user)->patchJson(
            '/api/v1/emprendedor/metas/' . $goalId . '/objectives/' . $objectiveOneId . '/status',
            ['status' => 'completed'],
        )->assertOk();

        $this->assertDatabaseHas('user_goals', [
            'id' => $goalId,
            'status' => 'in_progress',
            'progress_percent' => 50,
        ]);

        $this->assertDatabaseHas('user_notifications', [
            'user_auth_id' => $user->id,
            'type' => 'goals.objective_completed',
        ]);

        $this->actingAs($user)->patchJson(
            '/api/v1/emprendedor/metas/' . $goalId . '/objectives/' . $objectiveTwoId . '/status',
            ['status' => 'completed'],
        )->assertOk();

        $this->assertDatabaseHas('user_goals', [
            'id' => $goalId,
            'status' => 'completed',
            'progress_percent' => 100,
        ]);

        $this->assertDatabaseHas('user_notifications', [
            'user_auth_id' => $user->id,
            'type' => 'goals.goal_completed',
        ]);
    }

    public function test_user_cannot_access_other_users_goal(): void
    {
        $owner = $this->createUser('owner@test.com', 'impulsa_emprendedor');
        $other = $this->createUser('other@test.com', 'impulsa_emprendedor');

        $goalId = (int) DB::table('user_goals')->insertGetId([
            'user_auth_id' => $owner->id,
            'title' => 'Meta privada',
            'description' => null,
            'start_date' => null,
            'due_date' => null,
            'status' => 'pending',
            'progress_percent' => 0,
            'completed_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($other)->getJson('/api/v1/emprendedor/metas/' . $goalId)->assertNotFound();
    }

    public function test_cliente_can_use_same_goals_api(): void
    {
        $client = $this->createUser('cliente@test.com', 'impulsa_cliente');

        $response = $this->actingAs($client)->postJson('/api/v1/cliente/metas', [
            'title' => 'Meta cliente',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('user_goals', [
            'user_auth_id' => $client->id,
            'title' => 'Meta cliente',
        ]);
    }

    private function createUser(string $email, string $role, ?string $nombre = null, ?string $apellido = null): UserAuth
    {
        $user = UserAuth::query()->create([
            'correo' => $email,
            'password' => bcrypt('secret'),
            'rol' => $role,
            'email_verified_at' => now(),
        ]);

        UserInfo::query()->create([
            'user_auth_id' => $user->id,
            'nombre' => $nombre ?? 'Test',
            'apellido' => $apellido ?? 'User',
        ]);

        return $user;
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('user_goal_reminder_logs');
        Schema::dropIfExists('user_goal_objectives');
        Schema::dropIfExists('user_goals');
        Schema::dropIfExists('user_notifications');

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
