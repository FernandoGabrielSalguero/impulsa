<?php

declare(strict_types=1);

function adminGestorMenuCatalogoPorRol(): array
{
    return [
        'impulsa_emprendedor' => [
            ['key' => 'dashboard', 'href' => '/impulsa_emprende/controller/emprendedor/EmprendedorDashboardController.php', 'icon' => 'dashboard', 'label' => 'Dashboard'],
            ['key' => 'definicion', 'href' => '/impulsa_emprende/controller/emprendedor/emprendedorDefinicionController.php', 'icon' => 'psychology', 'label' => 'Definicion'],
            ['key' => 'pagina_web', 'href' => '/impulsa_emprende/controller/emprendedor/EmprendedorPaginaWebController.php', 'icon' => 'web', 'label' => 'Pagina web'],
            ['key' => 'metricas', 'href' => '/impulsa_emprende/controller/emprendedor/EmprendedorMetricasController.php', 'icon' => 'monitoring', 'label' => 'Metricas'],
            ['key' => 'marketing', 'href' => '/impulsa_emprende/controller/emprendedor/EmprendedorMarketingController.php', 'icon' => 'campaign', 'label' => 'Marketing'],
            ['key' => 'chatbot', 'href' => '/impulsa_emprende/controller/emprendedor/EmprendedorChatbotController.php', 'icon' => 'forum', 'label' => 'Chatbot'],
            ['key' => 'blog', 'href' => '/impulsa_emprende/controller/emprendedor/EmprendedorBlogController.php', 'icon' => 'article', 'label' => 'Blog'],
            ['key' => 'productos', 'href' => '/impulsa_emprende/controller/emprendedor/EmprendedorProductController.php', 'icon' => 'inventory_2', 'label' => 'Productos'],
        ],
        'impulsa_cliente' => [
            ['key' => 'dashboard', 'href' => '/impulsa_emprende/controller/client/ClienteDashboardController.php', 'icon' => 'dashboard', 'label' => 'Dashboard'],
            ['key' => 'metricas', 'href' => '/impulsa_emprende/controller/client/ClienteMetricasController.php', 'icon' => 'monitoring', 'label' => 'Metricas'],
            ['key' => 'marketing', 'href' => '/impulsa_emprende/controller/client/ClienteMarketingController.php', 'icon' => 'campaign', 'label' => 'Marketing'],
            ['key' => 'chatbot', 'href' => '/impulsa_emprende/controller/client/ClienteChatbotController.php', 'icon' => 'forum', 'label' => 'Chatbot'],
            ['key' => 'blog', 'href' => '/impulsa_emprende/controller/client/ClienteBlogController.php', 'icon' => 'article', 'label' => 'Blog'],
            ['key' => 'productos', 'href' => '/impulsa_emprende/controller/client/ClienteProductController.php', 'icon' => 'inventory_2', 'label' => 'Productos'],
        ],
    ];
}

function adminGestorMenuEtiquetasRol(): array
{
    return [
        'impulsa_emprendedor' => 'Emprendedor',
        'impulsa_cliente' => 'Cliente',
    ];
}

function adminGestorMenuEsRolConfigurable(string $rol): bool
{
    return array_key_exists($rol, adminGestorMenuCatalogoPorRol());
}

function adminGestorMenuCatalogoBase(string $rol): array
{
    return adminGestorMenuCatalogoPorRol()[$rol] ?? [];
}

function adminGestorMenuClavesBase(string $rol): array
{
    return array_map(
        static fn (array $item): string => (string) ($item['key'] ?? ''),
        adminGestorMenuCatalogoBase($rol)
    );
}

function adminGestorMenuNormalizarSeleccion(string $rol, array $menuKeys): array
{
    $catalogo = adminGestorMenuCatalogoBase($rol);
    if ($catalogo === []) {
        return [];
    }

    $permitidas = array_flip(adminGestorMenuClavesBase($rol));
    $seleccion = ['dashboard' => true];
    foreach ($menuKeys as $menuKey) {
        $menuKey = trim((string) $menuKey);
        if ($menuKey !== '' && isset($permitidas[$menuKey])) {
            $seleccion[$menuKey] = true;
        }
    }

    $normalizadas = [];
    foreach ($catalogo as $item) {
        $key = (string) ($item['key'] ?? '');
        if ($key !== '' && isset($seleccion[$key])) {
            $normalizadas[] = $key;
        }
    }

    return $normalizadas === [] ? ['dashboard'] : $normalizadas;
}

function adminGestorMenuHrefPorClave(string $rol, string $menuKey): ?string
{
    foreach (adminGestorMenuCatalogoBase($rol) as $item) {
        if (($item['key'] ?? null) === $menuKey) {
            return (string) ($item['href'] ?? '');
        }
    }

    return null;
}

function adminGestorMenuClavePorHref(string $rol, ?string $href): ?string
{
    $href = trim((string) $href);
    if ($href === '') {
        return null;
    }

    foreach (adminGestorMenuCatalogoBase($rol) as $item) {
        if (($item['href'] ?? null) === $href) {
            return (string) ($item['key'] ?? '');
        }
    }

    return null;
}

function adminGestorMenuObtenerRolUsuario(PDO $pdo, int $usuarioId): ?string
{
    if ($usuarioId <= 0) {
        return null;
    }

    $stmt = $pdo->prepare('SELECT rol FROM user_auth WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $usuarioId]);
    $rol = $stmt->fetchColumn();

    return is_string($rol) && $rol !== '' ? $rol : null;
}

function adminGestorMenuObtenerPaginaInicioGuardada(PDO $pdo, int $usuarioId): ?string
{
    $stmt = $pdo->prepare('SELECT page FROM user_params WHERE user_auth_id = :id LIMIT 1');
    $stmt->execute(['id' => $usuarioId]);
    $pagina = $stmt->fetchColumn();

    return is_string($pagina) && trim($pagina) !== '' ? trim($pagina) : null;
}

function adminGestorMenuObtenerClavesConfiguradas(PDO $pdo, int $usuarioId): array
{
    $stmt = $pdo->prepare('SELECT menu_key FROM user_menu_view WHERE user_auth_id = :id ORDER BY id ASC');
    $stmt->execute(['id' => $usuarioId]);

    return array_values(array_filter(array_map(
        static fn (mixed $value): string => trim((string) $value),
        $stmt->fetchAll(PDO::FETCH_COLUMN)
    )));
}

function adminGestorMenuResolverPaginaInicio(string $rol, array $visibleKeys, ?string $storedPage = null, ?string $selectedKey = null): array
{
    $visibleKeys = adminGestorMenuNormalizarSeleccion($rol, $visibleKeys);
    $allowed = array_flip($visibleKeys);

    $resolvedKey = null;
    $selectedKey = trim((string) $selectedKey);
    if ($selectedKey !== '' && isset($allowed[$selectedKey])) {
        $resolvedKey = $selectedKey;
    }

    if ($resolvedKey === null) {
        $storedKey = adminGestorMenuClavePorHref($rol, $storedPage);
        if ($storedKey !== null && isset($allowed[$storedKey])) {
            $resolvedKey = $storedKey;
        }
    }

    if ($resolvedKey === null && isset($allowed['dashboard'])) {
        $resolvedKey = 'dashboard';
    }

    if ($resolvedKey === null) {
        $resolvedKey = $visibleKeys[0] ?? 'dashboard';
    }

    return [
        'key' => $resolvedKey,
        'href' => adminGestorMenuHrefPorClave($rol, $resolvedKey) ?? adminGestorMenuHrefPorClave($rol, 'dashboard') ?? '',
    ];
}

function adminGestorMenuFiltrarItemsVisibles(string $rol, array $visibleKeys): array
{
    $allowed = array_flip(adminGestorMenuNormalizarSeleccion($rol, $visibleKeys));

    return array_values(array_filter(
        adminGestorMenuCatalogoBase($rol),
        static fn (array $item): bool => isset($allowed[(string) ($item['key'] ?? '')])
    ));
}

function adminGestorMenuObtenerConfiguracionUsuario(PDO $pdo, int $usuarioId, string $rol): array
{
    $catalogo = adminGestorMenuCatalogoBase($rol);
    if ($usuarioId <= 0 || $catalogo === []) {
        return [
            'configured' => false,
            'visible_keys' => [],
            'visible_items' => [],
            'start_key' => 'dashboard',
            'start_href' => '',
        ];
    }

    $configKeys = adminGestorMenuObtenerClavesConfiguradas($pdo, $usuarioId);
    $configured = $configKeys !== [];
    $visibleKeys = $configured ? adminGestorMenuNormalizarSeleccion($rol, $configKeys) : adminGestorMenuClavesBase($rol);
    $paginaInicio = adminGestorMenuResolverPaginaInicio(
        $rol,
        $visibleKeys,
        adminGestorMenuObtenerPaginaInicioGuardada($pdo, $usuarioId)
    );

    return [
        'configured' => $configured,
        'visible_keys' => $visibleKeys,
        'visible_items' => adminGestorMenuFiltrarItemsVisibles($rol, $visibleKeys),
        'start_key' => $paginaInicio['key'],
        'start_href' => $paginaInicio['href'],
    ];
}

function adminGestorMenuGuardarConfiguracion(PDO $pdo, int $usuarioId, array $menuKeys, ?string $startKey = null): array
{
    $rol = adminGestorMenuObtenerRolUsuario($pdo, $usuarioId);
    if ($rol === null) {
        return ['ok' => false, 'estado' => 'menu_usuario_id_invalido'];
    }

    if (!adminGestorMenuEsRolConfigurable($rol)) {
        return ['ok' => false, 'estado' => 'menu_usuario_rol_invalido'];
    }

    $visibleKeys = adminGestorMenuNormalizarSeleccion($rol, $menuKeys);
    $paginaInicio = adminGestorMenuResolverPaginaInicio(
        $rol,
        $visibleKeys,
        adminGestorMenuObtenerPaginaInicioGuardada($pdo, $usuarioId),
        $startKey
    );

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('DELETE FROM user_menu_view WHERE user_auth_id = :user_auth_id');
        $stmt->execute(['user_auth_id' => $usuarioId]);

        $stmtInsert = $pdo->prepare(
            'INSERT INTO user_menu_view (user_auth_id, menu_key, created_at, updated_at)
             VALUES (:user_auth_id, :menu_key, NOW(), NOW())'
        );
        foreach ($visibleKeys as $menuKey) {
            $stmtInsert->execute([
                'user_auth_id' => $usuarioId,
                'menu_key' => $menuKey,
            ]);
        }

        $stmt = $pdo->prepare('SELECT user_auth_id FROM user_params WHERE user_auth_id = :id LIMIT 1');
        $stmt->execute(['id' => $usuarioId]);
        if ($stmt->fetchColumn()) {
            $stmt = $pdo->prepare(
                'UPDATE user_params
                 SET page = :page,
                     updated_at = NOW()
                 WHERE user_auth_id = :user_auth_id'
            );
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO user_params (user_auth_id, page, created_at, updated_at)
                 VALUES (:user_auth_id, :page, NOW(), NOW())'
            );
        }
        $stmt->execute([
            'user_auth_id' => $usuarioId,
            'page' => $paginaInicio['href'],
        ]);

        $pdo->commit();

        return ['ok' => true, 'estado' => 'menu_usuario_actualizado'];
    } catch (Throwable) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return ['ok' => false, 'estado' => 'menu_usuario_error_guardar'];
    }
}
