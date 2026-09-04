<?php

namespace Tests\Feature\Mailbox;

use App\Models\UserAuth;
use App\Models\UserInfo;
use App\Models\UserMailbox;
use App\Services\Emprendedor\EmprendedorMenuService;
use App\Services\Mailbox\HostingerMailGateway;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class MailboxControllerTest extends TestCase
{
    public function test_user_without_mailbox_gets_not_found(): void
    {
        $this->createSchema();
        $this->mockGateway();

        $user = $this->createUser('maria@test.com', 'impulsa_cliente');

        $this->actingAs($user)
            ->getJson('/api/v1/mailbox')
            ->assertNotFound()
            ->assertJsonPath('message', 'No tenés un correo corporativo habilitado.');
    }

    public function test_user_can_read_own_mailbox_status_and_messages(): void
    {
        $this->createSchema();
        $gateway = $this->mockGateway();
        $user = $this->createUserWithMailbox();

        $gateway->shouldReceive('listMessages')
            ->once()
            ->andReturn([
                'data' => [
                    [
                        'uid' => 12,
                        'from' => 'Cliente <cliente@test.com>',
                        'from_email' => 'cliente@test.com',
                        'from_name' => 'Cliente',
                        'to' => 'oficina@impulsagroup.com',
                        'subject' => 'Hola',
                        'date' => '2026-09-03T15:00:00+00:00',
                        'seen' => false,
                        'preview' => 'Consulta',
                    ],
                ],
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 20,
                    'total' => 1,
                    'last_page' => 1,
                ],
            ]);

        $this->actingAs($user)
            ->getJson('/api/v1/mailbox')
            ->assertOk()
            ->assertJson([
                'configured' => true,
                'email' => 'oficina@impulsagroup.com',
                'enabled' => true,
            ]);

        $this->actingAs($user)
            ->getJson('/api/v1/mailbox/messages?folder=inbox')
            ->assertOk()
            ->assertJsonPath('data.0.uid', 12)
            ->assertJsonPath('data.0.subject', 'Hola');
    }

    public function test_user_can_send_message_through_gateway(): void
    {
        $this->createSchema();
        $gateway = $this->mockGateway();
        $user = $this->createUserWithMailbox();

        $gateway->shouldReceive('sendMessage')
            ->once()
            ->andReturnNull();

        $this->actingAs($user)
            ->post('/api/v1/mailbox/messages', [
                'to' => 'destino@test.com',
                'subject' => 'Asunto',
                'body' => '<p>Hola</p>',
            ])
            ->assertOk()
            ->assertJsonPath('message', 'Correo enviado correctamente.');
    }

    public function test_menu_includes_corporate_mail_only_when_enabled(): void
    {
        $this->createSchema();
        $this->mockGateway();

        $user = $this->createUser('ana@test.com', 'impulsa_emprendedor');
        UserInfo::query()->create([
            'user_auth_id' => $user->id,
            'nombre' => 'Ana',
        ]);

        $menuService = $this->app->make(EmprendedorMenuService::class);
        $withoutMailbox = $menuService->menuForUser($user->fresh(['menuViews', 'params', 'info', 'mailbox']));

        $this->assertFalse($withoutMailbox['has_corporate_mail']);
        $this->assertNotContains('correo_corporativo', array_column($withoutMailbox['menu_items'], 'key'));

        $mailbox = new UserMailbox([
            'user_auth_id' => $user->id,
            'email' => 'oficina@impulsagroup.com',
            'enabled' => true,
        ]);
        $mailbox->setPlainPassword('secret');
        $mailbox->save();

        $withMailbox = $menuService->menuForUser($user->fresh(['menuViews', 'params', 'info', 'mailbox']));

        $this->assertTrue($withMailbox['has_corporate_mail']);
        $this->assertContains('correo_corporativo', array_column($withMailbox['menu_items'], 'key'));
    }

    public function test_auth_me_exposes_corporate_mail_flag(): void
    {
        $this->createSchema();
        $this->mockGateway();
        $user = $this->createUserWithMailbox();

        $this->actingAs($user)
            ->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('data.has_corporate_mail', true);
    }

    private function mockGateway(): HostingerMailGateway
    {
        $gateway = Mockery::mock(HostingerMailGateway::class);
        $this->app->instance(HostingerMailGateway::class, $gateway);

        return $gateway;
    }

    private function createUserWithMailbox(): UserAuth
    {
        $user = $this->createUser('maria@test.com', 'impulsa_cliente');
        $mailbox = new UserMailbox([
            'user_auth_id' => $user->id,
            'email' => 'oficina@impulsagroup.com',
            'enabled' => true,
        ]);
        $mailbox->setPlainPassword('secret');
        $mailbox->save();

        return $user->fresh('mailbox');
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
        Schema::dropIfExists('user_menu_view');
        Schema::dropIfExists('user_params');
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
            $table->timestamps();
        });

        Schema::create('user_params', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_auth_id')->unique();
            $table->string('page')->nullable();
            $table->timestamps();
        });

        Schema::create('user_menu_view', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_auth_id');
            $table->string('menu_key');
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
