<?php

namespace Tests\Feature\Notifications;

use App\Models\UserAuth;
use App\Models\UserInfo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    public function test_comment_notifies_project_collaborators_but_not_actor_or_client(): void
    {
        $admin = $this->createUser('admin@test.com', 'impulsa_administrador');
        $otherAdmin = $this->createUser('admin2@test.com', 'impulsa_administrador');
        $managerCola = $this->createUser('manager@test.com', 'impulsa_colaborador', 'Manager', 'Cola');
        $actor = $this->createUser('actor@test.com', 'impulsa_colaborador', 'Actor', 'Uno');
        $peer = $this->createUser('peer@test.com', 'impulsa_colaborador', 'Peer', 'Dos');
        $client = $this->createUser('cliente@test.com', 'impulsa_cliente', 'Cliente', 'Test');
        // Manager del proyecto es colaborador: el admin igual debe enterarse del comentario.
        $projectId = $this->createProject($managerCola->id, $client->id, 'Proyecto notif');
        $this->assignCollaborator($projectId, $managerCola->id);
        $this->assignCollaborator($projectId, $actor->id);
        $this->assignCollaborator($projectId, $peer->id);

        $phaseId = (int) DB::table('project_phases')->insertGetId([
            'project_id' => $projectId,
            'title' => 'Fase',
            'phase_order' => 1,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $deliverableId = (int) DB::table('project_deliverables')->insertGetId([
            'project_id' => $projectId,
            'phase_id' => $phaseId,
            'title' => 'Objetivo',
            'deliverable_type' => 'document',
            'status' => 'pending',
            'client_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($actor)->postJson(
            '/api/v1/colaborador/projects/' . $projectId . '/deliverables/' . $deliverableId . '/comments',
            ['message' => 'Hola equipo'],
        );
        $response->assertCreated();

        $this->assertDatabaseHas('user_notifications', [
            'user_auth_id' => $peer->id,
            'type' => 'project.comment_created',
        ]);
        $this->assertDatabaseHas('user_notifications', [
            'user_auth_id' => $managerCola->id,
            'type' => 'project.comment_created',
        ]);
        $this->assertDatabaseHas('user_notifications', [
            'user_auth_id' => $admin->id,
            'type' => 'project.comment_created',
        ]);
        $this->assertDatabaseHas('user_notifications', [
            'user_auth_id' => $otherAdmin->id,
            'type' => 'project.comment_created',
        ]);
        $this->assertDatabaseMissing('user_notifications', [
            'user_auth_id' => $actor->id,
            'type' => 'project.comment_created',
        ]);
        $this->assertDatabaseMissing('user_notifications', [
            'user_auth_id' => $client->id,
            'type' => 'project.comment_created',
        ]);
    }

    public function test_phase_creation_notifies_collaborator_and_client_when_visible(): void
    {
        $admin = $this->createUser('admin@test.com', 'impulsa_administrador');
        $colaborador = $this->createUser('cola@test.com', 'impulsa_colaborador');
        $client = $this->createUser('cliente@test.com', 'impulsa_cliente');
        $projectId = $this->createProject($admin->id, $client->id, 'Proyecto fase', true);
        $this->assignCollaborator($projectId, $colaborador->id);

        $response = $this->actingAs($admin)->postJson('/api/v1/admin/projects/' . $projectId . '/phases', [
            'title' => 'Nueva fase',
            'description' => null,
            'duration_days' => null,
            'phase_order' => 1,
            'status' => 'pending',
        ]);
        $response->assertCreated();

        $this->assertDatabaseHas('user_notifications', [
            'user_auth_id' => $colaborador->id,
            'type' => 'project.phase_created',
        ]);
        $this->assertDatabaseHas('user_notifications', [
            'user_auth_id' => $client->id,
            'type' => 'project.client_update',
        ]);
        $this->assertDatabaseMissing('user_notifications', [
            'user_auth_id' => $admin->id,
            'type' => 'project.phase_created',
        ]);
    }

    public function test_unread_count_mark_read_and_dismiss(): void
    {
        $user = $this->createUser('user@test.com', 'impulsa_colaborador');

        $id = (int) DB::table('user_notifications')->insertGetId([
            'user_auth_id' => $user->id,
            'type' => 'project.comment_created',
            'title' => 'Test',
            'body' => 'Cuerpo editable',
            'payload' => json_encode(['project_id' => 1]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $count = $this->actingAs($user)->getJson('/api/v1/notifications/unread-count');
        $count->assertOk();
        $count->assertJsonPath('unread_count', 1);

        $list = $this->actingAs($user)->getJson('/api/v1/notifications');
        $list->assertOk();
        $list->assertJsonPath('data.0.title', 'Test');
        $list->assertJsonPath('data.0.body', 'Cuerpo editable');

        $read = $this->actingAs($user)->patchJson('/api/v1/notifications/' . $id . '/read');
        $read->assertOk();
        $read->assertJsonPath('unread_count', 0);

        $dismiss = $this->actingAs($user)->deleteJson('/api/v1/notifications/' . $id);
        $dismiss->assertOk();

        $listAfter = $this->actingAs($user)->getJson('/api/v1/notifications');
        $listAfter->assertJsonCount(0, 'data');
    }

    private function createUser(
        string $correo,
        string $rol,
        string $nombre = 'Nombre',
        string $apellido = 'Apellido',
    ): UserAuth {
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

    private function createProject(int $managerId, int $clientId, string $name, bool $clientVisible = true): int
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
            'client_visible' => $clientVisible,
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

        Schema::create('project_deliverable_comments', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('deliverable_id');
            $table->unsignedInteger('user_auth_id');
            $table->text('message');
            $table->timestamps();
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
