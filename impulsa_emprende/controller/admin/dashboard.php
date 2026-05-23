<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);
$usuarioAvatarUrl = null;

try {
    $stmt = $pdo->prepare('SELECT avatar_path FROM user_info WHERE user_auth_id = :user_auth_id LIMIT 1');
    $stmt->execute(['user_auth_id' => (int) $usuario['id']]);
    $avatarPath = (string) ($stmt->fetchColumn() ?: '');

    if ($avatarPath !== '') {
        $avatarNormalizado = str_replace('\\', '/', $avatarPath);

        if (!str_contains($avatarNormalizado, '/')) {
            $avatarNormalizado = 'impulsa_emprende/assets/images/avatar/' . $avatarNormalizado;
        }

        $usuarioAvatarUrl = obtenerAvatarUrl($avatarNormalizado);
    }
} catch (Throwable $e) {
    $usuarioAvatarUrl = null;
}

require __DIR__ . '/../../view/admin/dashboard.php';
