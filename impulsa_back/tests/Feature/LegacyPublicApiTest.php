<?php

namespace Tests\Feature;

use Tests\TestCase;

class LegacyPublicApiTest extends TestCase
{
    public function test_visit_tracker_script_is_deprecated(): void
    {
        $response = $this->get('/api/visit-tracker.js');

        $response->assertStatus(410);
        $response->assertHeader('Content-Type', 'application/javascript; charset=UTF-8');
        $this->assertStringContainsString('deprecado', $response->getContent());
    }

    public function test_blog_api_returns_gone(): void
    {
        $response = $this->postJson('/api/blog_api/index.php', [
            'action' => 'list',
        ]);

        $response->assertStatus(410);
        $response->assertJson([
            'code' => 'deprecated',
        ]);
    }

    public function test_product_api_returns_gone(): void
    {
        $response = $this->postJson('/api/producto_api/index.php', [
            'action' => 'list',
        ]);

        $response->assertStatus(410);
    }

    public function test_visit_api_returns_gone(): void
    {
        $response = $this->postJson('/api/visit_user_page/index.php', [
            'page' => '/index.html',
        ]);

        $response->assertStatus(410);
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
