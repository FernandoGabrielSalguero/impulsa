<?php

namespace Tests\Feature\Admin;

use App\Models\UserAuth;
use App\Models\UserInfo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminFinanzasControllerTest extends TestCase
{
    public function test_admin_can_list_finance_users(): void
    {
        $this->createSchema();

        $admin = UserAuth::query()->create([
            'correo' => 'admin@impulsa.test',
            'password' => 'secret',
            'rol' => 'impulsa_administrador',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        $emprendedor = UserAuth::query()->create([
            'correo' => 'emprendedor@impulsa.test',
            'password' => 'secret',
            'rol' => 'impulsa_emprendedor',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        UserInfo::query()->create([
            'user_auth_id' => $emprendedor->id,
            'nombre' => 'Ana',
            'apellido' => 'Perez',
        ]);

        UserAuth::query()->create([
            'correo' => 'colaborador@impulsa.test',
            'password' => 'secret',
            'rol' => 'impulsa_colaborador',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        $response = $this->actingAs($admin)->getJson('/api/v1/admin/finanzas/users');

        $response->assertOk();
        $response->assertJsonPath('meta.total', 1);
        $response->assertJsonPath('data.0.correo', 'emprendedor@impulsa.test');
        $response->assertJsonPath('data.0.rol_label', 'Emprendedor');
    }

    public function test_non_admin_cannot_list_finance_users(): void
    {
        $this->createSchema();

        $emprendedor = UserAuth::query()->create([
            'correo' => 'emprendedor@impulsa.test',
            'password' => 'secret',
            'rol' => 'impulsa_emprendedor',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        $this->actingAs($emprendedor)
            ->getJson('/api/v1/admin/finanzas/users')
            ->assertForbidden();
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('user_info');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('user_auth');

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
