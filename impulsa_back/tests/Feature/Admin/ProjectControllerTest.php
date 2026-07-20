<?php

namespace Tests\Feature\Admin;

use App\Models\UserAuth;
use App\Models\UserContacto;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchema();
    }

    public function test_admin_can_create_project_with_existing_client(): void
    {
        Mail::fake();

        $admin = $this->createAdmin();
        $client = $this->createClient('cliente@test.com', 'María', 'García');

        $response = $this->actingAs($admin)->postJson('/api/v1/admin/projects', [
            'project_name' => 'Sitio web Mi Negocio',
            'manager_user_id' => $admin->id,
            'client_user_id' => $client->id,
            'summary' => 'Resumen inicial',
            'client_visible' => true,
        ]);

        $response->assertCreated();
        $response->assertJsonPath('client_created', false);
        $response->assertJsonPath('email_sent', null);
        $response->assertJsonPath('data.project.project_name', 'Sitio web Mi Negocio');
        $response->assertJsonPath('data.project.client_user_id', $client->id);

        $this->assertSame(1, (int) \DB::table('projects')->count());
        $this->assertSame(3, (int) \DB::table('project_phases')->count());
        $this->assertSame(3, (int) \DB::table('project_deliverables')->count());
        $this->assertSame(0, (int) \DB::table('correos_log')->count());

        Mail::assertNothingSent();
    }

    public function test_admin_can_create_project_with_new_client_and_log_welcome_email(): void
    {
        Mail::fake();

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->postJson('/api/v1/admin/projects', [
            'project_name' => 'Proyecto Beta',
            'manager_user_id' => $admin->id,
            'create_client' => [
                'correo' => 'nuevo@test.com',
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'whatsapp' => '+5491111111111',
            ],
            'client_visible' => true,
        ]);

        $response->assertCreated();
        $response->assertJsonPath('client_created', true);
        $response->assertJsonPath('email_sent', true);
        $response->assertJsonPath('data.project.client_email', 'nuevo@test.com');

        $this->assertSame(2, (int) \DB::table('user_auth')->count());
        $this->assertSame(1, (int) \DB::table('projects')->count());

        $client = UserAuth::query()->where('correo', 'nuevo@test.com')->first();
        $this->assertNotNull($client);
        $this->assertSame('impulsa_cliente', $client->rol);

        $this->assertSame(1, (int) \DB::table('correos_log')->count());
        $this->assertSame('new_user_cliente', \DB::table('correos_log')->value('template'));
        $this->assertSame('enviado', \DB::table('correos_log')->value('estado'));
        $this->assertSame('nuevo@test.com', \DB::table('correos_log')->value('correo'));

        Mail::assertSent(\App\Mail\NewUserClienteMail::class);
    }

    public function test_create_project_rejects_duplicate_client_email(): void
    {
        $admin = $this->createAdmin();
        $this->createClient('existente@test.com');

        $response = $this->actingAs($admin)->postJson('/api/v1/admin/projects', [
            'project_name' => 'Proyecto duplicado',
            'manager_user_id' => $admin->id,
            'create_client' => [
                'correo' => 'existente@test.com',
                'nombre' => 'Otro',
            ],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['create_client.correo']);
        $this->assertSame(0, (int) \DB::table('projects')->count());
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
            'apellido' => 'Impulsa',
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

    private function createSchema(): void
    {
        Schema::dropIfExists('correos_log');
        Schema::dropIfExists('project_contracts');
        Schema::dropIfExists('project_collaborators');
        Schema::dropIfExists('project_updates');
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

        Schema::create('user_contacto', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_auth_id')->unique();
            $table->string('correo');
            $table->boolean('check_correo')->default(false);
            $table->boolean('permison_correo')->default(true);
            $table->string('whatsapp', 30)->nullable();
            $table->boolean('check_whatsapp')->default(false);
            $table->boolean('permison_whatsapp')->default(true);
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
