<?php

namespace Tests\Feature\Admin;

use App\Models\Project;
use App\Models\UserAuth;
use App\Models\UserContacto;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class ProjectProgressNotificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchema();
        Cache::flush();
    }

    public function test_updating_project_notifies_client_after_flush(): void
    {
        Mail::fake();

        $admin = $this->createAdmin();
        $client = $this->createClient('cliente@test.com', 'María', 'García');
        $project = $this->createProject($admin, $client);

        $response = $this->actingAs($admin)->putJson('/api/v1/admin/projects/' . $project->id, [
            'project_name' => 'Sitio web actualizado',
            'manager_user_id' => $admin->id,
            'status' => 'in_progress',
            'priority' => 'medium',
            'client_visible' => true,
        ]);

        $response->assertOk();
        Mail::assertNothingSent();
        $this->assertSame(0, (int) \DB::table('correos_log')->count());

        $flushResponse = $this->actingAs($admin)->postJson('/api/v1/admin/projects/' . $project->id . '/client-notification');

        $flushResponse->assertOk()->assertJsonPath('email_sent', true);

        $this->assertSame(1, (int) \DB::table('project_updates')->count());
        $this->assertSame(1, (int) \DB::table('correos_log')->count());
        $this->assertSame('project_progress_update', \DB::table('correos_log')->value('template'));
        $this->assertSame('cliente@test.com', \DB::table('correos_log')->value('correo'));

        Mail::assertSent(\App\Mail\ProjectProgressUpdateMail::class, 1);
    }

    public function test_multiple_updates_send_one_aggregated_email_on_flush(): void
    {
        Mail::fake();

        $admin = $this->createAdmin();
        $client = $this->createClient('cliente@test.com');
        $project = $this->createProject($admin, $client);
        $phaseId = (int) \DB::table('project_phases')->insertGetId([
            'project_id' => $project->id,
            'title' => 'Diseño',
            'description' => null,
            'duration_days' => null,
            'phase_order' => 1,
            'status' => 'in_progress',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $deliverableId = (int) \DB::table('project_deliverables')->insertGetId([
            'project_id' => $project->id,
            'phase_id' => $phaseId,
            'title' => 'Propuesta visual',
            'description' => null,
            'deliverable_type' => 'design',
            'status' => 'in_progress',
            'client_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)->putJson('/api/v1/admin/projects/' . $project->id, [
            'project_name' => 'Sitio web actualizado',
            'manager_user_id' => $admin->id,
            'status' => 'in_progress',
            'priority' => 'medium',
            'client_visible' => true,
        ])->assertOk();

        $this->actingAs($admin)->putJson(
            '/api/v1/admin/projects/' . $project->id . '/deliverables/' . $deliverableId,
            [
                'phase_id' => $phaseId,
                'title' => 'Propuesta visual',
                'deliverable_type' => 'design',
                'status' => 'delivered',
                'client_visible' => true,
            ],
        )->assertOk();

        Mail::assertNothingSent();

        $flushResponse = $this->actingAs($admin)->postJson('/api/v1/admin/projects/' . $project->id . '/client-notification');

        $flushResponse->assertOk()->assertJsonPath('email_sent', true);

        Mail::assertSent(\App\Mail\ProjectProgressUpdateMail::class, 1);
        $this->assertSame(1, (int) \DB::table('correos_log')->count());
        $this->assertSame('Actualizaciones en tu proyecto', \DB::table('project_updates')->value('title'));
    }

    public function test_updating_deliverable_status_queues_notification_until_flush(): void
    {
        Mail::fake();

        $admin = $this->createAdmin();
        $client = $this->createClient('cliente@test.com');
        $project = $this->createProject($admin, $client);
        $phaseId = (int) \DB::table('project_phases')->insertGetId([
            'project_id' => $project->id,
            'title' => 'Diseño',
            'description' => null,
            'duration_days' => null,
            'phase_order' => 1,
            'status' => 'in_progress',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $deliverableId = (int) \DB::table('project_deliverables')->insertGetId([
            'project_id' => $project->id,
            'phase_id' => $phaseId,
            'title' => 'Propuesta visual',
            'description' => null,
            'deliverable_type' => 'design',
            'status' => 'in_progress',
            'client_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)->putJson(
            '/api/v1/admin/projects/' . $project->id . '/deliverables/' . $deliverableId,
            [
                'phase_id' => $phaseId,
                'title' => 'Propuesta visual',
                'deliverable_type' => 'design',
                'status' => 'delivered',
                'client_visible' => true,
            ],
        );

        $response->assertOk();
        Mail::assertNothingSent();

        $this->actingAs($admin)
            ->postJson('/api/v1/admin/projects/' . $project->id . '/client-notification')
            ->assertOk()
            ->assertJsonPath('email_sent', true);

        Mail::assertSent(\App\Mail\ProjectProgressUpdateMail::class, 1);
        $this->assertSame(1, (int) \DB::table('correos_log')->count());
    }

    public function test_notification_is_skipped_when_project_is_not_client_visible(): void
    {
        Mail::fake();

        $admin = $this->createAdmin();
        $client = $this->createClient('cliente@test.com');
        $project = $this->createProject($admin, $client, clientVisible: false);

        $response = $this->actingAs($admin)->putJson('/api/v1/admin/projects/' . $project->id, [
            'project_name' => 'Proyecto interno',
            'manager_user_id' => $admin->id,
            'status' => 'in_progress',
            'priority' => 'medium',
            'client_visible' => false,
        ]);

        $response->assertOk();

        $this->actingAs($admin)
            ->postJson('/api/v1/admin/projects/' . $project->id . '/client-notification')
            ->assertOk()
            ->assertJsonPath('email_sent', null);

        Mail::assertNothingSent();
        $this->assertSame(0, (int) \DB::table('correos_log')->count());
    }

    private function createAdmin(): UserAuth
    {
        $admin = UserAuth::query()->create([
            'correo' => 'admin@test.com',
            'password' => 'secret',
            'rol' => 'impulsa_administrador',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        UserInfo::query()->create([
            'user_auth_id' => $admin->id,
            'nombre' => 'Admin',
        ]);

        return $admin;
    }

    private function createClient(string $correo, ?string $nombre = null, ?string $apellido = null): UserAuth
    {
        $client = UserAuth::query()->create([
            'correo' => $correo,
            'password' => 'secret',
            'rol' => 'impulsa_cliente',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        UserContacto::query()->create([
            'user_auth_id' => $client->id,
            'correo' => $correo,
            'check_correo' => true,
            'permison_correo' => true,
            'permison_whatsapp' => true,
        ]);

        if ($nombre !== null || $apellido !== null) {
            UserInfo::query()->create([
                'user_auth_id' => $client->id,
                'nombre' => $nombre,
                'apellido' => $apellido,
            ]);
        }

        return $client;
    }

    private function createProject(UserAuth $admin, UserAuth $client, bool $clientVisible = true): Project
    {
        return Project::query()->create([
            'source_type' => 'admin_manual',
            'project_name' => 'Sitio web inicial',
            'project_type' => 'website',
            'client_user_id' => $client->id,
            'manager_user_id' => $admin->id,
            'client_name' => 'María García',
            'client_email' => $client->correo,
            'status' => 'planned',
            'priority' => 'medium',
            'progress_percent' => 0,
            'client_visible' => $clientVisible,
        ]);
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('correos_log');
        Schema::dropIfExists('project_contracts');
        Schema::dropIfExists('project_updates');
        Schema::dropIfExists('project_deliverable_tasks');
        Schema::dropIfExists('project_deliverables');
        Schema::dropIfExists('project_phases');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('user_contacto');
        Schema::dropIfExists('user_info');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('user_auth');

        Schema::create('user_auth', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('correo')->unique();
            $table->string('password');
            $table->string('rol');
            $table->string('verification_token', 100)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('usuario_tipo')->default('externo');
            $table->timestamps();
        });

        Schema::create('user_info', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_auth_id')->unique();
            $table->string('nombre', 100)->nullable();
            $table->string('apellido', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('user_contacto', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_auth_id')->unique();
            $table->string('correo');
            $table->boolean('check_correo')->default(false);
            $table->boolean('permison_correo')->default(true);
            $table->boolean('permison_whatsapp')->default(true);
            $table->timestamps();
        });

        Schema::create('personal_access_tokens', function (Blueprint $table): void {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
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
            $table->date('due_date')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->boolean('client_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('project_deliverable_tasks', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('deliverable_id');
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
