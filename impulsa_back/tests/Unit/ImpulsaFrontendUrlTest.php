<?php

namespace Tests\Unit;

use App\Support\ImpulsaFrontendUrl;
use Tests\TestCase;

class ImpulsaFrontendUrlTest extends TestCase
{
    public function test_production_url_includes_impulsa_front_prefix(): void
    {
        config([
            'impulsa.frontend_url' => 'https://impulsagroup.com',
            'impulsa.frontend_app_path' => 'impulsa_front',
        ]);

        $this->assertSame(
            'https://impulsagroup.com/impulsa_front/emprendedor/contactos?ver=20',
            ImpulsaFrontendUrl::to('emprendedor/contactos?ver=20'),
        );
    }

    public function test_local_url_does_not_include_impulsa_front_prefix(): void
    {
        config([
            'impulsa.frontend_url' => 'http://localhost:4200',
            'impulsa.frontend_app_path' => 'impulsa_front',
        ]);

        $this->assertSame(
            'http://localhost:4200/emprendedor/contactos?ver=20',
            ImpulsaFrontendUrl::to('emprendedor/contactos?ver=20'),
        );
    }

    public function test_does_not_duplicate_prefix_when_frontend_url_already_includes_it(): void
    {
        config([
            'impulsa.frontend_url' => 'https://impulsagroup.com/impulsa_front',
            'impulsa.frontend_app_path' => 'impulsa_front',
        ]);

        $this->assertSame(
            'https://impulsagroup.com/impulsa_front/emprendedor/contactos?ver=20',
            ImpulsaFrontendUrl::to('emprendedor/contactos?ver=20'),
        );
    }
}
