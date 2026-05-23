<?php

require_once __DIR__ . '/PerfilModel.php';

$perfilModel = new PerfilModel($pdo);
$perfilSnackbar = $_SESSION['perfil_snackbar'] ?? null;
unset($_SESSION['perfil_snackbar']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['perfil_accion'] ?? '') === 'guardar_perfil') {
    try {
        $perfilModel->guardarPerfil((int) $usuario['id'], $_POST, $_FILES['avatar'] ?? null);
        $_SESSION['perfil_snackbar'] = [
            'mensaje' => 'Perfil actualizado correctamente.',
            'estado' => 'exito',
        ];
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $_SESSION['perfil_snackbar'] = [
            'mensaje' => 'No pudimos guardar el perfil en este momento.',
            'estado' => 'error',
        ];
    }

    header('Location: ' . strtok($_SERVER['REQUEST_URI'] ?? '', '?'));
    exit;
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
