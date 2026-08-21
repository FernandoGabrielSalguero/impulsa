<?php

namespace Tests\Feature\Admin;

use App\Models\UserAuth;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    public function test_admin_dashboard_stats_include_core_kpis(): void
    {
        $admin = $this->createAdmin();
        UserAuth::query()->create([
            'correo' => 'emp@test.com',
            'password' => 'secret',
            'rol' => 'impulsa_emprendedor',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        $response = $this->actingAs($admin)->getJson('/api/v1/admin/dashboard/stats');

        $response->assertOk()
            ->assertJsonPath('total_users', 2)
            ->assertJsonPath('logins.last_7_days', 0)
            ->assertJsonPath('projects.active', 0)
            ->assertJsonPath('tasks.open', 0)
            ->assertJsonPath('consultas.pending', 0)
            ->assertJsonPath('web_requests.pending', 0)
            ->assertJsonPath('marketing.mrr', 0)
            ->assertJsonPath('website_subscriptions.active', 0)
            ->assertJsonPath('goals.total', 0)
            ->assertJsonPath('emails.sent', 0)
            ->assertJsonPath('ai_usage.success', 0)
            ->assertJsonPath('content.blogs_active', 0)
            ->assertJsonStructure([
                'total_users',
                'users_by_role' => [
                    ['rol', 'label', 'count'],
                ],
                'logins' => ['last_7_days', 'last_30_days', 'series_7_days'],
                'projects' => ['active', 'total', 'by_status'],
                'tasks' => ['open', 'overdue', 'total', 'by_status'],
                'consultas' => ['pending', 'total'],
                'web_requests' => ['pending', 'internal_pending', 'external_pending'],
                'marketing' => ['mrr', 'active_subscriptions'],
                'website_subscriptions' => ['active', 'total'],
                'goals' => ['in_progress', 'completed', 'overdue', 'total'],
                'emails' => ['sent', 'failed'],
                'ai_usage' => ['success', 'failed'],
                'content' => ['blogs_active', 'academia_active', 'products_active'],
            ]);
    }

    private function createAdmin(): UserAuth
    {
        return UserAuth::query()->create([
            'correo' => 'admin@test.com',
            'password' => 'secret',
            'rol' => 'impulsa_administrador',
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('user_auth');

        Schema::create('user_auth', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('correo')->unique();
            $table->string('password');
            $table->string('rol');
            $table->string('verification_token', 100)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('usuario_tipo')->default('externo');
            $table->timestamps();
        });

        Schema::create('personal_access_tokens', function (Blueprint $table): void {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamps();
        });
    }
}
