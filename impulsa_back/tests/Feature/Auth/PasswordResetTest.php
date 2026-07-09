<?php

namespace Tests\Feature\Auth;

use App\Mail\ResetPasswordMail;
use App\Models\UserAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchema();
    }

    public function test_forgot_password_returns_error_when_email_is_not_registered(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/v1/auth/forgot-password', [
            'correo' => 'noexiste@test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['correo']);
        Mail::assertNothingSent();
    }

    public function test_forgot_password_sends_email_for_registered_user(): void
    {
        Mail::fake();

        $user = $this->createUser('usuario@test.com');

        $response = $this->postJson('/api/v1/auth/forgot-password', [
            'correo' => 'usuario@test.com',
        ]);

        $response->assertOk();
        $response->assertJsonPath('message', 'Te enviamos un correo con instrucciones para restablecer tu contraseña.');

        $user->refresh();
        $this->assertNotNull($user->password_reset_token);
        $this->assertNotNull($user->password_reset_token_expires_at);

        Mail::assertSent(ResetPasswordMail::class, function (ResetPasswordMail $mail) use ($user): bool {
            return $mail->hasTo($user->correo);
        });
    }

    public function test_reset_password_updates_password_and_clears_token(): void
    {
        $user = $this->createUser('reset@test.com', 'secret-old');
        $token = bin2hex(random_bytes(32));

        $user->update([
            'password_reset_token' => $token,
            'password_reset_token_expires_at' => now()->addHour(),
        ]);

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'token' => $token,
            'password' => 'nueva-clave',
            'password_confirmation' => 'nueva-clave',
        ]);

        $response->assertOk();
        $response->assertJsonPath('message', 'Tu contraseña fue actualizada. Ya podés iniciar sesión.');

        $user->refresh();
        $this->assertNull($user->password_reset_token);
        $this->assertNull($user->password_reset_token_expires_at);
        $this->assertTrue(Hash::check('nueva-clave', $user->password));
        $this->assertFalse(Hash::check('secret-old', $user->password));
    }

    public function test_reset_password_rejects_expired_token(): void
    {
        $user = $this->createUser('expired@test.com');
        $token = bin2hex(random_bytes(32));

        $user->update([
            'password_reset_token' => $token,
            'password_reset_token_expires_at' => now()->subMinute(),
        ]);

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'token' => $token,
            'password' => 'nueva-clave',
            'password_confirmation' => 'nueva-clave',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['token']);
    }

    public function test_reset_password_rejects_invalid_token(): void
    {
        $this->createUser('valido@test.com');

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'token' => bin2hex(random_bytes(32)),
            'password' => 'nueva-clave',
            'password_confirmation' => 'nueva-clave',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['token']);
    }

    public function test_reset_password_token_can_only_be_used_once(): void
    {
        $user = $this->createUser('once@test.com');
        $token = bin2hex(random_bytes(32));

        $user->update([
            'password_reset_token' => $token,
            'password_reset_token_expires_at' => now()->addHour(),
        ]);

        $first = $this->postJson('/api/v1/auth/reset-password', [
            'token' => $token,
            'password' => 'nueva-clave',
            'password_confirmation' => 'nueva-clave',
        ]);

        $first->assertOk();

        $second = $this->postJson('/api/v1/auth/reset-password', [
            'token' => $token,
            'password' => 'otra-clave',
            'password_confirmation' => 'otra-clave',
        ]);

        $second->assertStatus(422);
        $second->assertJsonValidationErrors(['token']);
    }

    private function createUser(string $correo, string $password = 'secret'): UserAuth
    {
        return UserAuth::query()->create([
            'correo' => $correo,
            'password' => $password,
            'rol' => 'impulsa_emprendedor',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('correos_log');
        Schema::dropIfExists('user_info');
        Schema::dropIfExists('user_auth');

        Schema::create('user_auth', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('correo')->unique();
            $table->string('password');
            $table->string('rol');
            $table->string('verification_token')->nullable();
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
            $table->date('fecha_nacimiento')->nullable();
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

        Schema::create('correos_log', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_auth_id')->nullable();
            $table->string('correo');
            $table->string('asunto');
            $table->string('template');
            $table->text('mensaje_html')->nullable();
            $table->text('mensaje_text')->nullable();
            $table->string('estado');
            $table->text('error')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }
}
