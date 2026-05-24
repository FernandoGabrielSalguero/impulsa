<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/user/userDefinicionModel.php';

$usuario = authRequiereRol('impulsa_usuario');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$definicionModel = new UserDefinicionModel($pdo);
$definicionUsuario = $definicionModel->obtenerUsuario((int) $usuario['id']);

require __DIR__ . '/../../partials/mision/misionController.php';
require __DIR__ . '/../../partials/vision/visionController.php';
require __DIR__ . '/../../partials/buyerPerson/buyerController.php';
require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Cliente';
}

$definicionSnackbar = $_SESSION['definicion_snackbar'] ?? null;
unset($_SESSION['definicion_snackbar']);

require __DIR__ . '/../../view/user/userDefinicionView.php';
