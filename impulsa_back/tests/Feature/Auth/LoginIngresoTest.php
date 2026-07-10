<?php

namespace Tests\Feature\Auth;

use App\Models\UserAuth;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class LoginIngresoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchema();
    }

    public function test_successful_login_records_user_ingreso(): void
    {
        $user = UserAuth::query()->create([
            'correo' => 'fernando@test.com',
            'password' => 'secret',
            'rol' => 'impulsa_administrador',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        UserInfo::query()->create([
            'user_auth_id' => $user->id,
            'nombre' => 'Fernando Gabriel',
            'apellido' => 'Salguero',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'correo' => 'fernando@test.com',
            'password' => 'secret',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['token', 'user']);

        $this->assertSame(1, DB::table('user_ingresos')->count());

        $ingreso = DB::table('user_ingresos')->first();

        $this->assertSame($user->id, (int) $ingreso->user_auth_id);
        $this->assertSame('Fernando Gabriel Salguero', $ingreso->nombre_usuario);
        $this->assertSame('impulsa_administrador', $ingreso->rol);
        $this->assertNotEmpty($ingreso->fecha_ingreso);
        $this->assertNotEmpty($ingreso->hora_ingreso);
    }

    public function test_failed_login_does_not_record_user_ingreso(): void
    {
        UserAuth::query()->create([
            'correo' => 'fernando@test.com',
            'password' => 'secret',
            'rol' => 'impulsa_administrador',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'correo' => 'fernando@test.com',
            'password' => 'incorrecta',
        ]);

        $response->assertStatus(422);
        $this->assertSame(0, DB::table('user_ingresos')->count());
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('user_ingresos');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('user_info');
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

        Schema::create('user_ingresos', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->string('nombre_usuario');
            $table->string('rol', 50);
            $table->date('fecha_ingreso');
            $table->time('hora_ingreso');
            $table->timestamp('created_at')->nullable();
        });
    }
}
