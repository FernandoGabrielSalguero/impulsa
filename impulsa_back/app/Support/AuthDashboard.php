<?php

namespace App\Support;

class AuthDashboard
{
    public const ROLE_ADMIN = 'impulsa_administrador';

    public const ROLE_MARKETING = 'impulsa_marketing';

    public const ROLE_EMPRENDEDOR = 'impulsa_emprendedor';

    public const ROLE_CLIENTE = 'impulsa_cliente';

    private const ROUTES = [
        self::ROLE_ADMIN => '/admin',
        self::ROLE_MARKETING => '/marketing',
        self::ROLE_EMPRENDEDOR => '/emprendedor',
        self::ROLE_CLIENTE => '/cliente',
    ];

    private const REGISTER_PROFILES = [
        'emprendedor' => self::ROLE_EMPRENDEDOR,
        'cliente' => self::ROLE_CLIENTE,
    ];

    public static function routeForRole(string $role): ?string
    {
        return self::ROUTES[$role] ?? null;
    }

    public static function roleForProfile(string $profile): ?string
    {
        return self::REGISTER_PROFILES[$profile] ?? null;
    }
}
