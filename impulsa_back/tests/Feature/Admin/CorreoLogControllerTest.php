<?php

namespace Tests\Feature\Admin;

use App\Models\CorreoLog;
use App\Models\UserAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class CorreoLogControllerTest extends TestCase
{
    public function test_admin_can_list_mail_logs(): void
    {
        $this->createSchema();

        $admin = UserAuth::query()->create([
            'correo' => 'admin@impulsa.test',
            'password' => 'secret',
            'rol' => 'impulsa_administrador',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        CorreoLog::query()->create([
            'correo' => 'cliente@impulsa.test',
            'asunto' => 'Bienvenido',
            'template' => 'new_user_cliente',
            'mensaje_html' => '<p>Hola</p>',
            'mensaje_text' => 'Hola',
            'estado' => 'enviado',
        ]);

        $response = $this->actingAs($admin)->getJson('/api/v1/admin/mail-logs?per_page=20');

        $response->assertOk();
        $response->assertJsonPath('meta.total', 1);
        $response->assertJsonPath('data.0.correo', 'cliente@impulsa.test');
        $response->assertJsonPath('data.0.template_label', 'Alta de usuario cliente');
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('correos_log');
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

        Schema::create('correos_log', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_auth_id')->nullable();
            $table->string('correo');
            $table->string('asunto');
            $table->string('template')->nullable();
            $table->longText('mensaje_html')->nullable();
            $table->text('mensaje_text')->nullable();
            $table->string('estado');
            $table->text('error')->nullable();
            $table->longText('meta')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }
}
