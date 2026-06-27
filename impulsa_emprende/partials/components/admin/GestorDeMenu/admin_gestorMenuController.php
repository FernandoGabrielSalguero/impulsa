<?php

declare(strict_types=1);

require_once __DIR__ . '/admin_gestorMenuModel.php';

function adminGestorMenuProcesarGuardado(PDO $pdo, array $post): array
{
    $usuarioId = filter_var($post['usuario_id'] ?? null, FILTER_VALIDATE_INT);
    if (!$usuarioId || $usuarioId <= 0) {
        return ['ok' => false, 'estado' => 'menu_usuario_id_invalido'];
    }

    $menuKeys = $post['menu_items'] ?? [];
    if (!is_array($menuKeys)) {
        $menuKeys = [];
    }

    $paginaInicio = trim((string) ($post['pagina_inicio'] ?? 'dashboard'));

    return adminGestorMenuGuardarConfiguracion($pdo, (int) $usuarioId, $menuKeys, $paginaInicio);
}

function adminGestorMenuAsegurarAccesoSeccion(PDO $pdo, array $usuario, string $menuKey): void
{
    $usuarioId = (int) ($usuario['id'] ?? 0);
    $rol = (string) ($usuario['rol'] ?? '');
    if ($usuarioId <= 0 || !adminGestorMenuEsRolConfigurable($rol)) {
        return;
    }

    $config = adminGestorMenuObtenerConfiguracionUsuario($pdo, $usuarioId, $rol);
    $visible = array_flip($config['visible_keys'] ?? []);
    if (isset($visible[$menuKey])) {
        return;
    }

    $destino = (string) ($config['start_href'] ?? '');
    if ($destino === '') {
        $destino = adminGestorMenuHrefPorClave($rol, 'dashboard') ?? '/';
    }

    header('Location: ' . $destino);
    exit;
}
