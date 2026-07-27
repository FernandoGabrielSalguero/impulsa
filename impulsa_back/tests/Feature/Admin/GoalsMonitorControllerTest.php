<?php

namespace Tests\Feature\Admin;

use App\Models\UserAuth;
use App\Models\UserInfo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GoalsMonitorControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    public function test_admin_can_get_summary_and_list_goals(): void
    {
        $admin = $this->createUser('admin@impulsa.test', 'impulsa_administrador');
        $emprendedor = $this->createUser('emp@test.com', 'impulsa_emprendedor', 'Ana', 'Emprendedora');
        $cliente = $this->createUser('cli@test.com', 'impulsa_cliente', 'Carlos', 'Cliente');

        $goalEmprendedorId = $this->createGoal($emprendedor->id, [
            'title' => 'Meta emprendedor',
            'status' => 'in_progress',
            'progress_percent' => 50,
            'due_date' => now()->addDays(5)->toDateString(),
        ]);
        $this->createObjective($goalEmprendedorId, ['title' => 'Obj 1', 'status' => 'completed']);
        $this->createObjective($goalEmprendedorId, ['title' => 'Obj 2', 'status' => 'pending']);

        $goalClienteId = $this->createGoal($cliente->id, [
            'title' => 'Meta cliente',
            'status' => 'completed',
            'progress_percent' => 100,
            'due_date' => now()->subDays(2)->toDateString(),
            'completed_at' => now(),
        ]);
        $this->createObjective($goalClienteId, ['title' => 'Obj cliente', 'status' => 'completed']);

        $adminUser = $this->createUser('other-admin-role@test.com', 'impulsa_administrador', 'Admin', 'Oculto');
        $this->createGoal($adminUser->id, [
            'title' => 'Meta admin oculta',
            'status' => 'pending',
        ]);

        $summary = $this->actingAs($admin)->getJson('/api/v1/admin/metas/summary');
        $summary->assertOk();
        $summary->assertJsonPath('data.total_goals', 2);
        $summary->assertJsonPath('data.total_objectives', 3);
        $summary->assertJsonPath('data.goals_completed', 1);
        $summary->assertJsonPath('data.goals_in_progress', 1);
        $summary->assertJsonPath('data.objectives_completed', 2);
        $summary->assertJsonPath('data.users_with_goals', 2);

        $list = $this->actingAs($admin)->getJson('/api/v1/admin/metas?per_page=20');
        $list->assertOk();
        $list->assertJsonPath('meta.total', 2);
        $list->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'goal' => ['title', 'status', 'progress_percent', 'is_overdue'],
                    'owner' => ['id', 'name', 'email', 'role', 'role_label'],
                    'objectives_total',
                    'objectives_completed',
                ],
            ],
            'meta' => ['current_page', 'last_page', 'total', 'per_page'],
        ]);
    }

    public function test_admin_can_get_goal_detail_with_objectives(): void
    {
        $admin = $this->createUser('admin2@impulsa.test', 'impulsa_administrador');
        $emprendedor = $this->createUser('emp2@test.com', 'impulsa_emprendedor', 'Laura', 'Meta');

        $goalId = $this->createGoal($emprendedor->id, [
            'title' => 'Lanzamiento',
            'description' => 'Plan comercial',
            'status' => 'in_progress',
            'progress_percent' => 50,
            'due_date' => now()->addDays(10)->toDateString(),
        ]);
        $this->createObjective($goalId, ['title' => 'Pricing', 'status' => 'completed']);
        $this->createObjective($goalId, ['title' => 'Landing', 'status' => 'pending']);

        $response = $this->actingAs($admin)->getJson('/api/v1/admin/metas/' . $goalId);
        $response->assertOk();
        $response->assertJsonPath('data.owner.email', 'emp2@test.com');
        $response->assertJsonPath('data.goal.title', 'Lanzamiento');
        $response->assertJsonPath('data.summary.total_objectives', 2);
        $response->assertJsonPath('data.summary.completed_objectives', 1);
        $response->assertJsonCount(2, 'data.objectives');
    }

    public function test_non_admin_roles_receive_forbidden(): void
    {
        $emprendedor = $this->createUser('emp3@test.com', 'impulsa_emprendedor');
        $cliente = $this->createUser('cli3@test.com', 'impulsa_cliente');

        $this->actingAs($emprendedor)->getJson('/api/v1/admin/metas/summary')->assertForbidden();
        $this->actingAs($emprendedor)->getJson('/api/v1/admin/metas')->assertForbidden();
        $this->actingAs($cliente)->getJson('/api/v1/admin/metas/options')->assertForbidden();
    }

    public function test_admin_cannot_access_goals_from_excluded_roles(): void
    {
        $admin = $this->createUser('admin3@impulsa.test', 'impulsa_administrador');
        $colaborador = $this->createUser('colab@test.com', 'impulsa_colaborador', 'Colab', 'User');

        $goalId = $this->createGoal($colaborador->id, [
            'title' => 'Meta colaborador',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)->getJson('/api/v1/admin/metas/' . $goalId)->assertNotFound();

        $list = $this->actingAs($admin)->getJson('/api/v1/admin/metas?q=colaborador');
        $list->assertOk();
        $list->assertJsonPath('meta.total', 0);
    }

    public function test_admin_can_filter_by_role_and_overdue(): void
    {
        $admin = $this->createUser('admin4@impulsa.test', 'impulsa_administrador');
        $emprendedor = $this->createUser('emp4@test.com', 'impulsa_emprendedor');
        $cliente = $this->createUser('cli4@test.com', 'impulsa_cliente');

        $this->createGoal($emprendedor->id, [
            'title' => 'Meta vencida',
            'status' => 'in_progress',
            'due_date' => now()->subDay()->toDateString(),
        ]);
        $this->createGoal($cliente->id, [
            'title' => 'Meta al día',
            'status' => 'pending',
            'due_date' => now()->addDays(3)->toDateString(),
        ]);

        $overdue = $this->actingAs($admin)->getJson('/api/v1/admin/metas?overdue=1');
        $overdue->assertOk();
        $overdue->assertJsonPath('meta.total', 1);
        $overdue->assertJsonPath('data.0.goal.title', 'Meta vencida');

        $clientOnly = $this->actingAs($admin)->getJson('/api/v1/admin/metas?role=impulsa_cliente');
        $clientOnly->assertOk();
        $clientOnly->assertJsonPath('meta.total', 1);
        $clientOnly->assertJsonPath('data.0.owner.role', 'impulsa_cliente');
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

    /** @param  array<string, mixed>  $attributes */
    private function createGoal(int $userId, array $attributes = []): int
    {
        return (int) DB::table('user_goals')->insertGetId(array_merge([
            'user_auth_id' => $userId,
            'title' => 'Meta test',
            'description' => null,
            'start_date' => null,
            'due_date' => null,
            'status' => 'pending',
            'progress_percent' => 0,
            'completed_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ], $attributes));
    }

    /** @param  array<string, mixed>  $attributes */
    private function createObjective(int $goalId, array $attributes = []): int
    {
        return (int) DB::table('user_goal_objectives')->insertGetId(array_merge([
            'goal_id' => $goalId,
            'title' => 'Objetivo test',
            'description' => null,
            'due_date' => null,
            'status' => 'pending',
            'sort_order' => 0,
            'completed_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ], $attributes));
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('user_goal_objectives');
        Schema::dropIfExists('user_goals');
        Schema::dropIfExists('user_info');
        Schema::dropIfExists('user_auth');
        Schema::dropIfExists('personal_access_tokens');

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

        Schema::create('personal_access_tokens', function (Blueprint $table): void {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
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
    }
}
