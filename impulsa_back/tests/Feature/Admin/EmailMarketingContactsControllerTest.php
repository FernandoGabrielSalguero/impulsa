<?php

namespace Tests\Feature\Admin;

use App\Models\UserAuth;
use App\Models\UserContacto;
use App\Models\UserInfo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EmailMarketingContactsControllerTest extends TestCase
{
    public function test_admin_can_list_marketing_contacts_with_opt_in_filter(): void
    {
        $this->createSchema();

        $admin = $this->createAdmin();
        $included = $this->createUser('incluido@test.com', true, true);
        $this->createUser('sin-permiso@test.com', true, false);
        $this->createUser('sin-verificar@test.com', false, true);

        $response = $this->actingAs($admin)->getJson('/api/v1/admin/email-marketing/contacts?per_page=20');

        $response->assertOk();
        $response->assertJsonPath('meta.total', 1);
        $response->assertJsonPath('data.0.id', $included->id);
        $response->assertJsonPath('data.0.correo', 'incluido@test.com');
    }

    public function test_admin_can_list_marketing_contacts_with_string_boolean_query_params(): void
    {
        $this->createSchema();

        $admin = $this->createAdmin();
        $included = $this->createUser('incluido@test.com', true, true);

        $response = $this->actingAs($admin)->getJson(
            '/api/v1/admin/email-marketing/contacts?only_opt_in=true&only_verified=true&page=1&per_page=15',
        );

        $response->assertOk();
        $response->assertJsonPath('meta.total', 1);
        $response->assertJsonPath('data.0.id', $included->id);
    }

    public function test_admin_can_export_marketing_contacts_csv(): void
    {
        $this->createSchema();

        $admin = $this->createAdmin();
        $user = $this->createUser('maria@test.com', true, true, 'María', 'García', 'impulsa_cliente');
        $this->createUser('bloqueado@test.com', true, false);

        $response = $this->actingAs($admin)->get(
            '/api/v1/admin/email-marketing/contacts/export?ids=' . $user->id,
        );

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();

        $this->assertStringContainsString('email,name,surname,note', $content);
        $this->assertStringContainsString('maria@test.com', $content);
        $this->assertStringContainsString('María', $content);
        $this->assertStringContainsString('García', $content);
        $this->assertStringContainsString('Cliente', $content);
        $this->assertStringNotContainsString('bloqueado@test.com', $content);
    }

    public function test_non_admin_cannot_export_marketing_contacts(): void
    {
        $this->createSchema();

        $user = UserAuth::query()->create([
            'correo' => 'cliente@test.com',
            'password' => 'secret',
            'rol' => 'impulsa_cliente',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        $this->actingAs($user)
            ->get('/api/v1/admin/email-marketing/contacts/export')
            ->assertForbidden();
    }

    private function createAdmin(): UserAuth
    {
        $admin = UserAuth::query()->create([
            'correo' => 'admin@impulsa.test',
            'password' => 'secret',
            'rol' => 'impulsa_administrador',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        UserContacto::query()->create([
            'user_auth_id' => $admin->id,
            'correo' => $admin->correo,
            'check_correo' => true,
            'permison_correo' => false,
            'permison_whatsapp' => true,
        ]);

        return $admin;
    }

    private function createUser(
        string $email,
        bool $verified,
        bool $permisonCorreo,
        ?string $nombre = null,
        ?string $apellido = null,
        string $rol = 'impulsa_cliente',
    ): UserAuth {
        $user = UserAuth::query()->create([
            'correo' => $email,
            'password' => 'secret',
            'rol' => $rol,
            'email_verified_at' => $verified ? now() : null,
            'usuario_tipo' => 'externo',
        ]);

        UserContacto::query()->create([
            'user_auth_id' => $user->id,
            'correo' => $email,
            'check_correo' => true,
            'permison_correo' => $permisonCorreo,
            'permison_whatsapp' => true,
        ]);

        if ($nombre !== null || $apellido !== null) {
            UserInfo::query()->create([
                'user_auth_id' => $user->id,
                'nombre' => $nombre,
                'apellido' => $apellido,
            ]);
        }

        return $user;
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('user_contacto');
        Schema::dropIfExists('user_info');
        Schema::dropIfExists('user_auth');
        Schema::dropIfExists('personal_access_tokens');

        Schema::create('user_auth', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('correo')->unique();
            $table->string('password');
            $table->string('rol');
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
            $table->string('correo')->nullable();
            $table->boolean('check_correo')->default(false);
            $table->boolean('permison_correo')->default(true);
            $table->string('whatsapp')->nullable();
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
    }
}
