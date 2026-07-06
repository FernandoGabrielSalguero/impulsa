<?php

namespace Tests\Feature\PublicEmbed;

use App\Mail\PublicContactNotificationMail;
use App\Models\ApiIntegration;
use App\Models\UserAuth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class PublicContactNotificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createMinimalSchema();
    }

    public function test_contact_submission_sends_notification_to_verified_owner(): void
    {
        Mail::fake();

        $owner = UserAuth::query()->create([
            'correo' => 'emprendedor@test.com',
            'password' => 'secret',
            'rol' => 'impulsa_emprendedor',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        ApiIntegration::query()->create([
            'project_name' => 'Proyecto Demo',
            'allowed_domain' => 'example.test',
            'public_key' => 'pk_test_contact_notify',
            'secret_key_hash' => null,
            'status' => 'active',
            'user_auth_id' => $owner->id,
        ]);

        $response = $this->postJson('/api/v1/public/contact-submissions', [
            'contact_nombre' => 'Ana Cliente',
            'contact_email' => 'ana@example.test',
            'contact_whatsapp' => '5491111111111',
            'contact_description' => 'Quiero más información sobre sus servicios.',
            'page' => '/contacto.html',
        ], [
            'HTTP_X-Impulsa-Public-Key' => 'pk_test_contact_notify',
            'HTTP_ORIGIN' => 'https://www.example.test',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.ok', true);

        Mail::assertSent(PublicContactNotificationMail::class, function (PublicContactNotificationMail $mail) use ($owner): bool {
            return $mail->hasTo($owner->correo);
        });
    }

    public function test_contact_submission_skips_notification_when_owner_email_is_not_verified(): void
    {
        Mail::fake();

        $owner = UserAuth::query()->create([
            'correo' => 'sin-verificar@test.com',
            'password' => 'secret',
            'rol' => 'impulsa_emprendedor',
            'email_verified_at' => null,
            'usuario_tipo' => 'externo',
        ]);

        ApiIntegration::query()->create([
            'project_name' => 'Proyecto Demo',
            'allowed_domain' => 'example.test',
            'public_key' => 'pk_test_no_notify',
            'secret_key_hash' => null,
            'status' => 'active',
            'user_auth_id' => $owner->id,
        ]);

        $response = $this->postJson('/api/v1/public/contact-submissions', [
            'contact_nombre' => 'Ana Cliente',
            'contact_email' => 'ana@example.test',
        ], [
            'HTTP_X-Impulsa-Public-Key' => 'pk_test_no_notify',
            'HTTP_ORIGIN' => 'https://www.example.test',
        ]);

        $response->assertStatus(201);
        Mail::assertNothingSent();
    }

    private function createMinimalSchema(): void
    {
        Schema::dropIfExists('forms_clients_contact');
        Schema::dropIfExists('correos_log');
        Schema::dropIfExists('api_integrations');
        Schema::dropIfExists('user_auth');

        Schema::create('user_auth', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('correo')->unique();
            $table->string('password');
            $table->string('rol');
            $table->string('verification_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('usuario_tipo')->default('externo');
            $table->timestamps();
        });

        Schema::create('api_integrations', function (Blueprint $table): void {
            $table->id();
            $table->string('project_name');
            $table->string('allowed_domain');
            $table->string('public_key')->unique();
            $table->string('secret_key_hash')->nullable();
            $table->string('status')->default('active');
            $table->unsignedInteger('user_auth_id')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });

        Schema::create('forms_clients_contact', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('page');
            $table->unsignedBigInteger('api_integration_id')->nullable();
            $table->string('contact_nombre');
            $table->string('contact_whatsapp')->nullable();
            $table->string('contact_email')->nullable();
            $table->text('contact_description')->nullable();
            $table->string('contact_consultation', 1000)->nullable();
            $table->string('state')->default('recibido');
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
