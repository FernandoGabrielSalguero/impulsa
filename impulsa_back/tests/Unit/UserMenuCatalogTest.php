<?php

namespace Tests\Unit;

use App\Support\UserMenuCatalog;
use PHPUnit\Framework\TestCase;

class UserMenuCatalogTest extends TestCase
{
    public function test_each_configurable_role_has_its_own_menu_items(): void
    {
        $emprendedorKeys = UserMenuCatalog::keysForRole('impulsa_emprendedor');
        $clienteKeys = UserMenuCatalog::keysForRole('impulsa_cliente');
        $marketingKeys = UserMenuCatalog::keysForRole('impulsa_marketing');
        $colaboradorKeys = UserMenuCatalog::keysForRole('impulsa_colaborador');

        $this->assertNotSame([], $emprendedorKeys);
        $this->assertNotSame([], $clienteKeys);
        $this->assertNotSame([], $marketingKeys);
        $this->assertNotSame([], $colaboradorKeys);

        $this->assertContains('definicion', $emprendedorKeys);
        $this->assertContains('pagina_web', $emprendedorKeys);
        $this->assertNotContains('definicion', $clienteKeys);
        $this->assertNotContains('pagina_web', $clienteKeys);

        $this->assertContains('contactos', $emprendedorKeys);
        $this->assertContains('contactos', $clienteKeys);
        $this->assertContains('finanzas', $emprendedorKeys);
        $this->assertContains('finanzas', $clienteKeys);
        $this->assertContains('metas', $emprendedorKeys);
        $this->assertContains('metas', $clienteKeys);
        $this->assertNotContains('contactos', $marketingKeys);
        $this->assertNotContains('finanzas', $marketingKeys);

        $this->assertContains('constructor', $marketingKeys);
        $this->assertNotContains('constructor', $emprendedorKeys);
        $this->assertNotContains('constructor', $clienteKeys);

        $this->assertSame(['proyectos'], $colaboradorKeys);
        $this->assertSame(['proyectos'], UserMenuCatalog::normalizeSelection('impulsa_colaborador', []));
    }
}
