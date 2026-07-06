<?php

namespace Tests\Feature;

use Tests\TestCase;

class LegacyPublicApiTest extends TestCase
{
    public function test_visit_tracker_script_is_served(): void
    {
        $response = $this->get('/api/visit-tracker.js');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/javascript; charset=UTF-8');
        $this->assertStringContainsString('visit_user_page/index.php', $response->getContent());
        $this->assertStringContainsString('IMPULSA_API_CONFIG', $response->getContent());
    }

    public function test_blog_api_requires_public_key(): void
    {
        $response = $this->postJson('/api/blog_api/index.php', [
            'action' => 'list',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'message' => 'public_key requerida.',
        ]);
    }

    public function test_product_api_requires_public_key(): void
    {
        $response = $this->postJson('/api/producto_api/index.php', [
            'action' => 'list',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'message' => 'public_key requerida.',
        ]);
    }

    public function test_visit_api_requires_public_key(): void
    {
        $response = $this->postJson('/api/visit_user_page/index.php', [
            'page' => '/index.html',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'message' => 'public_key requerida.',
        ]);
    }

    public function test_blog_api_options_preflight_is_allowed_in_testing(): void
    {
        $response = $this->call(
            'OPTIONS',
            '/api/blog_api/index.php',
            [],
            [],
            [],
            [
                'HTTP_ORIGIN' => 'https://www.example.test',
                'HTTP_ACCESS_CONTROL_REQUEST_METHOD' => 'POST',
            ],
        );

        $response->assertStatus(204);
        $response->assertHeader('Access-Control-Allow-Origin', 'https://www.example.test');
    }
}
