<?php

namespace Tests\Feature\Admin;

use App\Exceptions\HostingerMailException;
use App\Models\UserAuth;
use App\Models\UserMailbox;
use App\Services\Mailbox\HostingerMailGateway;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class UserMailboxControllerTest extends TestCase
{
    public function test_admin_can_see_unconfigured_mailbox(): void
    {
        $this->createSchema();
        $this->mockGateway();

        $admin = $this->createUser('admin@impulsa.test', 'impulsa_administrador');
        $user = $this->createUser('maria@test.com', 'impulsa_cliente');

        $this->actingAs($admin)
            ->getJson("/api/v1/admin/users/{$user->id}/mailbox")
            ->assertOk()
            ->assertJson([
                'configured' => false,
                'email' => null,
                'enabled' => false,
            ]);
    }

    public function test_admin_can_configure_mailbox_after_successful_connection_test(): void
    {
        $this->createSchema();
        $gateway = $this->mockGateway();
        $gateway->shouldReceive('testConnection')
            ->once()
            ->with('oficina@impulsagroup.com', 'hostinger-secret')
            ->andReturnNull();

        $admin = $this->createUser('admin@impulsa.test', 'impulsa_administrador');
        $user = $this->createUser('maria@test.com', 'impulsa_cliente');

        $response = $this->actingAs($admin)
            ->putJson("/api/v1/admin/users/{$user->id}/mailbox", [
                'email' => 'oficina@impulsagroup.com',
                'password' => 'hostinger-secret',
            ]);

        $response->assertOk()
            ->assertJson([
                'configured' => true,
                'email' => 'oficina@impulsagroup.com',
                'enabled' => true,
            ])
            ->assertJsonMissingPath('password')
            ->assertJsonMissingPath('password_encrypted');

        $mailbox = UserMailbox::query()->where('user_auth_id', $user->id)->first();

        $this->assertNotNull($mailbox);
        $this->assertSame('oficina@impulsagroup.com', $mailbox->email);
        $this->assertTrue($mailbox->enabled);
        $this->assertSame('hostinger-secret', Crypt::decryptString($mailbox->password_encrypted));
        $this->assertNotSame('hostinger-secret', $mailbox->password_encrypted);
    }

    public function test_admin_cannot_save_mailbox_when_connection_fails(): void
    {
        $this->createSchema();
        $gateway = $this->mockGateway();
        $gateway->shouldReceive('testConnection')
            ->once()
            ->andThrow(new HostingerMailException('No pudimos autenticar el correo por IMAP. Revisá usuario y contraseña de Hostinger.'));

        $admin = $this->createUser('admin@impulsa.test', 'impulsa_administrador');
        $user = $this->createUser('maria@test.com', 'impulsa_cliente');

        $this->actingAs($admin)
            ->putJson("/api/v1/admin/users/{$user->id}/mailbox", [
                'email' => 'oficina@impulsagroup.com',
                'password' => 'wrong',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        $this->assertSame(0, UserMailbox::query()->count());
    }

    public function test_admin_can_update_email_without_sending_password_again(): void
    {
        $this->createSchema();
        $gateway = $this->mockGateway();
        $gateway->shouldReceive('testConnection')
            ->once()
            ->with('nuevo@impulsagroup.com', 'kept-secret')
            ->andReturnNull();

        $admin = $this->createUser('admin@impulsa.test', 'impulsa_administrador');
        $user = $this->createUser('maria@test.com', 'impulsa_cliente');

        $mailbox = new UserMailbox([
            'user_auth_id' => $user->id,
            'email' => 'viejo@impulsagroup.com',
            'enabled' => true,
        ]);
        $mailbox->setPlainPassword('kept-secret');
        $mailbox->save();

        $this->actingAs($admin)
            ->putJson("/api/v1/admin/users/{$user->id}/mailbox", [
                'email' => 'nuevo@impulsagroup.com',
            ])
            ->assertOk()
            ->assertJsonPath('email', 'nuevo@impulsagroup.com');

        $mailbox->refresh();
        $this->assertSame('kept-secret', $mailbox->getPlainPassword());
    }

    public function test_admin_can_disable_mailbox(): void
    {
        $this->createSchema();
        $this->mockGateway();

        $admin = $this->createUser('admin@impulsa.test', 'impulsa_administrador');
        $user = $this->createUser('maria@test.com', 'impulsa_cliente');

        $mailbox = new UserMailbox([
            'user_auth_id' => $user->id,
            'email' => 'oficina@impulsagroup.com',
            'enabled' => true,
        ]);
        $mailbox->setPlainPassword('secret');
        $mailbox->save();

        $this->actingAs($admin)
            ->deleteJson("/api/v1/admin/users/{$user->id}/mailbox")
            ->assertOk()
            ->assertJson([
                'configured' => false,
                'enabled' => false,
            ]);

        $this->assertNull(UserMailbox::query()->where('user_auth_id', $user->id)->first());
    }

    public function test_non_admin_cannot_configure_mailbox(): void
    {
        $this->createSchema();
        $this->mockGateway();

        $user = $this->createUser('maria@test.com', 'impulsa_cliente');

        $this->actingAs($user)
            ->putJson("/api/v1/admin/users/{$user->id}/mailbox", [
                'email' => 'oficina@impulsagroup.com',
                'password' => 'secret',
            ])
            ->assertForbidden();
    }

    private function mockGateway(): HostingerMailGateway
    {
        $gateway = Mockery::mock(HostingerMailGateway::class);
        $this->app->instance(HostingerMailGateway::class, $gateway);

        return $gateway;
    }

    private function createUser(string $email, string $role): UserAuth
    {
        return UserAuth::query()->create([
            'correo' => $email,
            'password' => 'secret',
            'rol' => $role,
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('user_mailboxes');
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

        Schema::create('user_mailboxes', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id')->unique();
            $table->string('email');
            $table->text('password_encrypted');
            $table->boolean('enabled')->default(true);
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
