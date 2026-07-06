<?php

namespace Tests\Feature\PublicEmbed;

use Tests\TestCase;

class PublicEmbedApiTest extends TestCase
{
    public function test_bootstrap_requires_public_key(): void
    {
        $response = $this->getJson('/api/v1/public/bootstrap');

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'public_key requerida.',
            'code' => 'public_key_required',
        ]);
    }

    public function test_blog_requires_public_key(): void
    {
        $response = $this->getJson('/api/v1/public/blog');

        $response->assertStatus(401);
    }

    public function test_products_requires_public_key(): void
    {
        $response = $this->getJson('/api/v1/public/products');

        $response->assertStatus(401);
    }

    public function test_contact_submission_requires_public_key(): void
    {
        $response = $this->postJson('/api/v1/public/contact-submissions', [
            'contact_nombre' => 'Test',
        ]);

        $response->assertStatus(401);
    }

    public function test_chatbot_requires_public_key(): void
    {
        $response = $this->getJson('/api/v1/public/chatbot');

        $response->assertStatus(401);
    }

    public function test_page_visit_requires_public_key(): void
    {
        $response = $this->postJson('/api/v1/public/page-visit', [
            'page' => '/index.html',
        ]);

        $response->assertStatus(401);
    }

    public function test_subscription_status_requires_public_key(): void
    {
        $response = $this->getJson('/api/v1/public/subscription-status');

        $response->assertStatus(401);
    }

    public function test_impulsa_script_endpoint_exists(): void
    {
        $response = $this->get('/api/v1/public/impulsa.js');

        $this->assertContains($response->getStatusCode(), [200, 503]);
        $response->assertHeader('Content-Type', 'application/javascript; charset=UTF-8');
    }

    public function test_legacy_blog_api_returns_gone(): void
    {
        $response = $this->postJson('/api/blog_api/index.php', [
            'action' => 'list',
            'public_key' => 'pk_test',
        ]);

        $response->assertStatus(410);
        $response->assertJsonFragment(['code' => 'deprecated']);
    }

    public function test_legacy_visit_tracker_script_returns_gone(): void
    {
        $response = $this->get('/api/visit-tracker.js');

        $response->assertStatus(410);
        $response->assertHeader('Deprecation', 'true');
    }

    public function test_public_api_options_preflight_is_allowed_in_testing(): void
    {
        $response = $this->call(
            'OPTIONS',
            '/api/v1/public/bootstrap',
            [],
            [],
            [],
            [
                'HTTP_ORIGIN' => 'https://www.example.test',
                'HTTP_ACCESS_CONTROL_REQUEST_METHOD' => 'GET',
            ],
        );

        $response->assertStatus(204);
        $response->assertHeader('Access-Control-Allow-Origin', 'https://www.example.test');
    }
}
