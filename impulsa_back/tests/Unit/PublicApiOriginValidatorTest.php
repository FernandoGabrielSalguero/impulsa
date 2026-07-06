<?php

namespace Tests\Unit;

use App\Support\PublicApiOriginValidator;
use Illuminate\Http\Request;
use Tests\TestCase;

class PublicApiOriginValidatorTest extends TestCase
{
    public function test_allows_subdomain_for_allowed_domain(): void
    {
        $request = Request::create('/', 'POST', [], [], [], [
            'HTTP_ORIGIN' => 'https://www.sisu-group.net',
        ]);

        $this->assertTrue(PublicApiOriginValidator::isOriginAllowed($request, 'sisu-group.net'));
    }

    public function test_rejects_unrelated_domain_in_production_environment(): void
    {
        app()['env'] = 'production';

        $request = Request::create('/', 'POST', [], [], [], [
            'HTTP_ORIGIN' => 'https://evil.example',
        ]);

        $this->assertFalse(PublicApiOriginValidator::isOriginAllowed($request, 'sisu-group.net'));
    }
}
