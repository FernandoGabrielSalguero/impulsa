<?php

namespace Tests\Feature\Colaborador;

use App\Models\UserAuth;
use App\Models\UserInfo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ColaboradorProjectControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchema();
    }

    public function test_colaborador_lists_only_assigned_projects(): void
    {
        $colaborador = $this->createColaborador('cola@test.com');
        $other = $this->createColaborador('otro@test.com');
        $admin = $this->createAdmin();
        $client = $this->createClient('cliente@test.com');

        $assignedId = $this->createProject($admin->id, $client->id, 'Proyecto asignado');
        $this->assignCollaborator($assignedId, $colaborador->id);

        $otherProjectId = $this->createProject($admin->id, $client->id, 'Proyecto de otro');
        $this->assignCollaborator($otherProjectId, $other->id);

        $response = $this->actingAs($colaborador)->getJson('/api/v1/colaborador/projects');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $assignedId);
        $response->assertJsonPath('data.0.project_name', 'Proyecto asignado');
    }

    public function test_colaborador_cannot_view_unassigned_project(): void
    {
        $colaborador = $this->createColaborador('cola@test.com');
        $admin = $this->createAdmin();
        $client = $this->createClient('cliente@test.com');
        $projectId = $this->createProject($admin->id, $client->id, 'Sin asignar');

        $response = $this->actingAs($colaborador)->getJson('/api/v1/colaborador/projects/' . $projectId);

        $response->assertNotFound();
    }

    public function test_colaborador_can_update_project_phase_and_deliverable_status(): void
    {
        $colaborador = $this->createColaborador('cola@test.com');
        $admin = $this->createAdmin();
        $client = $this->createClient('cliente@test.com');
        $projectId = $this->createProject($admin->id, $client->id, 'Proyecto estados');
        $this->assignCollaborator($projectId, $colaborador->id);

        $phaseId = (int) DB::table('project_phases')->insertGetId([
            'project_id' => $projectId,
            'title' => 'Fase 1',
            'description' => null,
            'duration_days' => null,
            'phase_order' => 1,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $deliverableId = (int) DB::table('project_deliverables')->insertGetId([
            'project_id' => $projectId,
            'phase_id' => $phaseId,
            'title' => 'Objetivo 1',
            'description' => null,
            'deliverable_type' => 'document',
            'status' => 'pending',
            'client_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $projectStatus = $this->actingAs($colaborador)->patchJson(
            '/api/v1/colaborador/projects/' . $projectId . '/status',
            ['status' => 'in_progress'],
        );
        $projectStatus->assertOk();
        $projectStatus->assertJsonPath('data.project.status', 'in_progress');

        $phaseStatus = $this->actingAs($colaborador)->patchJson(
            '/api/v1/colaborador/projects/' . $projectId . '/phases/' . $phaseId . '/status',
            ['status' => 'in_progress'],
        );
        $phaseStatus->assertOk();
        $phaseStatus->assertJsonPath('data.phases.0.status', 'in_progress');

        $deliverableStatus = $this->actingAs($colaborador)->patchJson(
            '/api/v1/colaborador/projects/' . $projectId . '/deliverables/' . $deliverableId . '/status',
            ['status' => 'ready_for_review'],
        );
        $deliverableStatus->assertOk();
        $deliverableStatus->assertJsonPath('data.deliverables.0.status', 'ready_for_review');
    }

    public function test_colaborador_cannot_set_deliverable_delivered_and_options_exclude_it(): void
    {
        $colaborador = $this->createColaborador('cola@test.com');
        $admin = $this->createAdmin();
        $client = $this->createClient('cliente@test.com');
        $projectId = $this->createProject($admin->id, $client->id, 'Sin entregado');
        $this->assignCollaborator($projectId, $colaborador->id);

        $phaseId = (int) DB::table('project_phases')->insertGetId([
            'project_id' => $projectId,
            'title' => 'Fase 1',
            'description' => null,
            'duration_days' => null,
            'phase_order' => 1,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $deliverableId = (int) DB::table('project_deliverables')->insertGetId([
            'project_id' => $projectId,
            'phase_id' => $phaseId,
            'title' => 'Objetivo 1',
            'description' => null,
            'deliverable_type' => 'document',
            'status' => 'pending',
            'client_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $options = $this->actingAs($colaborador)->getJson('/api/v1/colaborador/projects/options');
        $options->assertOk();
        $statuses = collect($options->json('deliverable_statuses'))->pluck('value')->all();
        $this->assertNotContains('delivered', $statuses);

        $response = $this->actingAs($colaborador)->patchJson(
            '/api/v1/colaborador/projects/' . $projectId . '/deliverables/' . $deliverableId . '/status',
            ['status' => 'delivered'],
        );
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['status']);
    }

    public function test_colaborador_can_comment_on_deliverable_and_unassigned_gets_404(): void
    {
        $colaborador = $this->createColaborador('cola@test.com');
        $other = $this->createColaborador('otro@test.com');
        $admin = $this->createAdmin();
        $client = $this->createClient('cliente@test.com');
        $projectId = $this->createProject($admin->id, $client->id, 'Con comentarios');
        $this->assignCollaborator($projectId, $colaborador->id);

        $phaseId = (int) DB::table('project_phases')->insertGetId([
            'project_id' => $projectId,
            'title' => 'Fase 1',
            'description' => null,
            'duration_days' => null,
            'phase_order' => 1,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $deliverableId = (int) DB::table('project_deliverables')->insertGetId([
            'project_id' => $projectId,
            'phase_id' => $phaseId,
            'title' => 'Objetivo 1',
            'description' => 'Detalle',
            'deliverable_type' => 'document',
            'status' => 'pending',
            'client_visible' => true,
            'assigned_user_id' => $colaborador->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $post = $this->actingAs($colaborador)->postJson(
            '/api/v1/colaborador/projects/' . $projectId . '/deliverables/' . $deliverableId . '/comments',
            ['message' => 'Avance listo para revisión'],
        );
        $post->assertCreated();
        $post->assertJsonPath('data.message', 'Avance listo para revisión');
        $post->assertJsonPath('data.is_mine', true);

        $list = $this->actingAs($colaborador)->getJson(
            '/api/v1/colaborador/projects/' . $projectId . '/deliverables/' . $deliverableId . '/comments',
        );
        $list->assertOk();
        $list->assertJsonCount(1, 'data');

        $adminList = $this->actingAs($admin)->getJson(
            '/api/v1/admin/projects/' . $projectId . '/deliverables/' . $deliverableId . '/comments',
        );
        $adminList->assertOk();
        $adminList->assertJsonCount(1, 'data');

        $adminPost = $this->actingAs($admin)->postJson(
            '/api/v1/admin/projects/' . $projectId . '/deliverables/' . $deliverableId . '/comments',
            ['message' => 'Respuesta del admin'],
        );
        $adminPost->assertCreated();
        $adminPost->assertJsonPath('data.message', 'Respuesta del admin');
        $adminPost->assertJsonPath('data.is_mine', true);

        $thread = $this->actingAs($colaborador)->getJson(
            '/api/v1/colaborador/projects/' . $projectId . '/deliverables/' . $deliverableId . '/comments',
        );
        $thread->assertOk();
        $thread->assertJsonCount(2, 'data');
        $thread->assertJsonPath('data.1.message', 'Respuesta del admin');
        $thread->assertJsonPath('data.1.is_mine', false);

        $detail = $this->actingAs($colaborador)->getJson('/api/v1/colaborador/projects/' . $projectId);
        $detail->assertOk();
        $detail->assertJsonPath('data.deliverables.0.assigned_to_me', true);
        $detail->assertJsonPath('data.deliverables.0.description', 'Detalle');

        $forbidden = $this->actingAs($other)->postJson(
            '/api/v1/colaborador/projects/' . $projectId . '/deliverables/' . $deliverableId . '/comments',
            ['message' => 'No debería'],
        );
        $forbidden->assertNotFound();
    }

    public function test_admin_rejects_invalid_assignee_and_accepts_project_collaborator(): void
    {
        $admin = $this->createAdmin();
        $colaborador = $this->createColaborador('cola@test.com');
        $outsider = $this->createColaborador('outsider@test.com');
        $client = $this->createClient('cliente@test.com');
        $projectId = $this->createProject($admin->id, $client->id, 'Asignacion');
        $this->assignCollaborator($projectId, $colaborador->id);

        $invalid = $this->actingAs($admin)->postJson('/api/v1/admin/projects/' . $projectId . '/phases', [
            'title' => 'Fase con assignee inválido',
            'description' => null,
            'duration_days' => null,
            'phase_order' => 1,
            'status' => 'pending',
            'assigned_user_id' => $outsider->id,
        ]);
        $invalid->assertStatus(422);
        $invalid->assertJsonValidationErrors(['assigned_user_id']);

        $valid = $this->actingAs($admin)->postJson('/api/v1/admin/projects/' . $projectId . '/phases', [
            'title' => 'Fase con assignee válido',
            'description' => null,
            'duration_days' => null,
            'phase_order' => 1,
            'status' => 'pending',
            'assigned_user_id' => $colaborador->id,
        ]);
        $valid->assertCreated();
        $valid->assertJsonPath('data.phases.0.assigned_user_id', $colaborador->id);

        $phaseId = (int) $valid->json('data.phases.0.id');

        $deliverableInvalid = $this->actingAs($admin)->postJson(
            '/api/v1/admin/projects/' . $projectId . '/deliverables',
            [
                'phase_id' => $phaseId,
                'title' => 'Obj inválido',
                'description' => null,
                'deliverable_type' => 'document',
                'status' => 'pending',
                'defcon' => 3,
                'due_date' => null,
                'client_visible' => true,
                'assigned_user_id' => $outsider->id,
            ],
        );
        $deliverableInvalid->assertStatus(422);
        $deliverableInvalid->assertJsonValidationErrors(['assigned_user_id']);

        $deliverableValid = $this->actingAs($admin)->postJson(
            '/api/v1/admin/projects/' . $projectId . '/deliverables',
            [
                'phase_id' => $phaseId,
                'title' => 'Obj válido',
                'description' => null,
                'deliverable_type' => 'document',
                'status' => 'pending',
                'defcon' => 2,
                'due_date' => null,
                'client_visible' => true,
                'assigned_user_id' => $colaborador->id,
            ],
        );
        $deliverableValid->assertCreated();
        $deliverableValid->assertJsonPath('data.deliverables.0.assigned_user_id', $colaborador->id);
    }

    public function test_colaborador_status_update_rejects_invalid_status(): void
    {
        $colaborador = $this->createColaborador('cola@test.com');
        $admin = $this->createAdmin();
        $client = $this->createClient('cliente@test.com');
        $projectId = $this->createProject($admin->id, $client->id, 'Proyecto invalid');
        $this->assignCollaborator($projectId, $colaborador->id);

        $response = $this->actingAs($colaborador)->patchJson(
            '/api/v1/colaborador/projects/' . $projectId . '/status',
            ['status' => 'no_existe'],
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['status']);
    }

    public function test_admin_can_assign_collaborators_and_auto_include_manager(): void
    {
        $admin = $this->createAdmin();
        $managerColaborador = $this->createColaborador('manager@test.com', 'Ana', 'Manager');
        $extraColaborador = $this->createColaborador('extra@test.com', 'Luis', 'Extra');
        $client = $this->createClient('cliente@test.com');

        $response = $this->actingAs($admin)->postJson('/api/v1/admin/projects', [
            'project_name' => 'Con equipo',
            'manager_user_id' => $managerColaborador->id,
            'client_user_id' => $client->id,
            'collaborator_user_ids' => [$extraColaborador->id],
            'client_visible' => true,
        ]);

        $response->assertCreated();
        $projectId = (int) $response->json('data.project.id');
        $collaboratorIds = collect($response->json('data.collaborators'))->pluck('id')->sort()->values()->all();

        $this->assertSame(
            collect([$managerColaborador->id, $extraColaborador->id])->sort()->values()->all(),
            $collaboratorIds,
        );

        $this->assertDatabaseHas('project_collaborators', [
            'project_id' => $projectId,
            'user_auth_id' => $managerColaborador->id,
        ]);
        $this->assertDatabaseHas('project_collaborators', [
            'project_id' => $projectId,
            'user_auth_id' => $extraColaborador->id,
        ]);
    }

    public function test_colaborador_menu_and_login_redirect(): void
    {
        $colaborador = $this->createColaborador('cola@test.com');

        $menu = $this->actingAs($colaborador)->getJson('/api/v1/colaborador/menu');
        $menu->assertOk();
        $menu->assertJsonPath('data.menu_items.0.key', 'proyectos');

        $login = $this->postJson('/api/v1/auth/login', [
            'correo' => 'cola@test.com',
            'password' => 'secret',
        ]);
        $login->assertOk();
        $login->assertJsonPath('user.redirect_to', '/colaborador');
    }

    private function createAdmin(): UserAuth
    {
        return $this->createUser('admin@test.com', 'impulsa_administrador', 'Admin', 'Impulsa');
    }

    private function createColaborador(string $correo, string $nombre = 'Cola', string $apellido = 'Borador'): UserAuth
    {
        return $this->createUser($correo, 'impulsa_colaborador', $nombre, $apellido);
    }

    private function createClient(string $correo): UserAuth
    {
        return $this->createUser($correo, 'impulsa_cliente', 'Cliente', 'Test');
    }

    private function createUser(string $correo, string $rol, string $nombre, string $apellido): UserAuth
    {
        $user = UserAuth::query()->create([
            'correo' => $correo,
            'password' => 'secret',
            'rol' => $rol,
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        UserInfo::query()->create([
            'user_auth_id' => $user->id,
            'nombre' => $nombre,
            'apellido' => $apellido,
        ]);

        return $user;
    }

    private function createProject(int $managerId, int $clientId, string $name): int
    {
        return (int) DB::table('projects')->insertGetId([
            'source_type' => 'admin_manual',
            'project_name' => $name,
            'project_type' => 'website',
            'client_user_id' => $clientId,
            'manager_user_id' => $managerId,
            'client_name' => 'Cliente Test',
            'client_email' => 'cliente@test.com',
            'status' => 'planned',
            'priority' => 'medium',
            'progress_percent' => 0,
            'client_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function assignCollaborator(int $projectId, int $userAuthId): void
    {
        DB::table('project_collaborators')->insert([
            'project_id' => $projectId,
            'user_auth_id' => $userAuthId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('project_deliverable_comments');
        Schema::dropIfExists('project_collaborators');
        Schema::dropIfExists('project_contracts');
        Schema::dropIfExists('project_updates');
        Schema::dropIfExists('project_deliverables');
        Schema::dropIfExists('project_phases');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('user_menu_view');
        Schema::dropIfExists('user_params');
        Schema::dropIfExists('user_info');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('user_auth');

        Schema::create('user_auth', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('correo')->unique();
            $table->string('password');
            $table->string('rol');
            $table->string('verification_token', 100)->nullable();
            $table->string('password_reset_token', 100)->nullable();
            $table->timestamp('password_reset_token_expires_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('usuario_tipo')->default('externo');
            $table->timestamps();
        });

        Schema::create('user_info', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_auth_id')->unique();
            $table->string('nombre', 100)->nullable();
            $table->string('apellido', 100)->nullable();
            $table->string('apodo', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('user_params', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_auth_id')->unique();
            $table->string('page')->nullable();
            $table->timestamps();
        });

        Schema::create('user_menu_view', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_auth_id');
            $table->string('menu_key');
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

        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->string('source_type', 40)->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('project_name', 180);
            $table->string('project_type', 30)->default('website');
            $table->unsignedInteger('client_user_id')->nullable();
            $table->unsignedInteger('manager_user_id');
            $table->string('client_name', 150);
            $table->string('client_email', 190);
            $table->string('client_whatsapp', 80)->nullable();
            $table->text('summary')->nullable();
            $table->text('scope_summary')->nullable();
            $table->string('status', 30)->default('planned');
            $table->string('priority', 30)->default('medium');
            $table->date('start_date')->nullable();
            $table->date('target_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->boolean('client_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('project_phases', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('title', 180);
            $table->text('description')->nullable();
            $table->unsignedInteger('duration_days')->nullable();
            $table->unsignedInteger('phase_order')->default(1);
            $table->string('status', 30)->default('pending');
            $table->date('due_date')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->unsignedInteger('assigned_user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('project_deliverables', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('phase_id')->nullable();
            $table->string('title', 180);
            $table->text('description')->nullable();
            $table->string('deliverable_type', 30)->default('other');
            $table->string('status', 30)->default('pending');
            $table->unsignedTinyInteger('defcon')->default(5);
            $table->date('due_date')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->boolean('client_visible')->default(true);
            $table->unsignedInteger('assigned_user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('project_deliverable_comments', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('deliverable_id');
            $table->unsignedInteger('user_auth_id');
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('project_updates', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('phase_id')->nullable();
            $table->unsignedInteger('created_by');
            $table->string('title', 180);
            $table->text('message');
            $table->smallInteger('progress_delta')->nullable();
            $table->boolean('visible_to_client')->default(true);
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('project_contracts', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('contract_name');
            $table->longText('contract_html')->nullable();
            $table->text('contract_text')->nullable();
            $table->unsignedInteger('version_number')->default(1);
            $table->boolean('is_signed')->default(false);
            $table->timestamp('signed_at')->nullable();
            $table->string('signer_full_name')->nullable();
            $table->unsignedInteger('created_by_user_id')->nullable();
            $table->unsignedInteger('updated_by_user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('project_collaborators', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedInteger('user_auth_id');
            $table->timestamps();
            $table->unique(['project_id', 'user_auth_id']);
        });

        Schema::create('user_notifications', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->string('type', 80);
            $table->string('title', 255);
            $table->text('body')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamps();
        });
    }
}
