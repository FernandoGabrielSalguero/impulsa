<?php

require_once __DIR__ . '/PerfilModel.php';

$perfilModel = new PerfilModel($pdo);
$perfilMensaje = '';
$perfilError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['perfil_accion'] ?? '') === 'guardar_perfil') {
    try {
        $perfilModel->guardarPerfil((int) $usuario['id'], $_POST, $_FILES['avatar'] ?? null);
        $perfilMensaje = 'Perfil actualizado correctamente.';
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $perfilError = 'No pudimos guardar el perfil en este momento.';
    }
}

$perfilDatos = $perfilModel->obtenerPerfil((int) $usuario['id'], $usuarioCorreo);
$perfilCompleto = $perfilModel->estaCompleto($perfilDatos);
$perfilAvatarUrl = $perfilModel->avatarUrl($perfilDatos['avatar_path'] ?? null);
$perfilSesionDebug = [
    'usuario_id' => (int) $usuario['id'],
    'usuario' => $usuarioCorreo,
    'rol' => (string) ($usuario['rol'] ?? ''),
    'perfil_completo' => $perfilCompleto,
];
