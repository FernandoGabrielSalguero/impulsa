<?php

namespace Tests\Feature\Profile;

use App\Models\UserAuth;
use App\Models\UserContacto;
use App\Models\UserInfo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    public function test_authenticated_user_can_view_profile(): void
    {
        $this->createSchema();
        $user = $this->createUser();

        $response = $this->actingAs($user)->getJson('/api/v1/auth/profile');

        $response->assertOk();
        $response->assertJsonPath('data.correo', 'perfil@test.com');
        $response->assertJsonPath('data.nombre', 'Ana');
        $response->assertJsonPath('data.permison_correo', true);
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $this->createSchema();
        $user = $this->createUser();

        $response = $this->actingAs($user)->putJson('/api/v1/auth/profile', [
            'nombre' => 'Ana María',
            'apellido' => 'López',
            'apodo' => 'Ani',
            'fecha_nacimiento' => '1995-06-15',
            'correo_contacto' => 'contacto@test.com',
            'whatsapp' => '+5491122334455',
            'permison_correo' => false,
            'permison_whatsapp' => true,
        ]);

        $response->assertOk();
        $response->assertJsonPath('profile.nombre', 'Ana María');
        $response->assertJsonPath('profile.apellido', 'López');
        $response->assertJsonPath('profile.permison_correo', false);

        $this->assertDatabaseHas('user_info', [
            'user_auth_id' => $user->id,
            'nombre' => 'Ana María',
            'apellido' => 'López',
        ]);

        $this->assertDatabaseHas('user_contacto', [
            'user_auth_id' => $user->id,
            'correo' => 'contacto@test.com',
            'permison_correo' => 0,
        ]);
    }

    public function test_authenticated_user_can_upload_avatar(): void
    {
        $this->createSchema();
        $user = $this->createUser();

        $file = UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->post('/api/v1/auth/profile', [
            'nombre' => 'Ana',
            'apellido' => 'Test',
            'apodo' => '',
            'correo_contacto' => 'perfil@test.com',
            'whatsapp' => '',
            'permison_correo' => '1',
            'permison_whatsapp' => '1',
            'avatar_file' => $file,
        ]);

        $response->assertOk();
        $response->assertJsonPath('profile.has_avatar', true);

        $avatarPath = UserInfo::query()->where('user_auth_id', $user->id)->value('avatar_path');
        $this->assertNotNull($avatarPath);
        $this->assertStringStartsWith('user-avatars/', $avatarPath);
    }

    public function test_guest_cannot_access_profile(): void
    {
        $this->createSchema();

        $this->getJson('/api/v1/auth/profile')->assertUnauthorized();
    }

    private function createUser(): UserAuth
    {
        $user = UserAuth::query()->create([
            'correo' => 'perfil@test.com',
            'password' => 'secret',
            'rol' => 'impulsa_emprendedor',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        UserInfo::query()->create([
            'user_auth_id' => $user->id,
            'nombre' => 'Ana',
            'apellido' => 'Test',
        ]);

        UserContacto::query()->create([
            'user_auth_id' => $user->id,
            'correo' => 'perfil@test.com',
            'check_correo' => true,
            'permison_correo' => true,
            'permison_whatsapp' => true,
        ]);

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
            $table->string('avatar_path', 255)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->timestamps();
        });

        Schema::create('user_contacto', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_auth_id')->unique();
            $table->string('correo')->nullable();
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
    }
}
