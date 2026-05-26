<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/admin/adminSolicitudesPaginaWebSolicitudesModel.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$solicitudesPaginaWebModel = new AdminSolicitudesPaginaWebSolicitudesModel($pdo);
$solicitudesPaginaWeb = $solicitudesPaginaWebModel->obtenerSolicitudes();

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Usuario';
}

require __DIR__ . '/../../view/admin/adminSolicitudesPaginaWebSolicitudesView.php';
