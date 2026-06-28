<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/admin/adminIngresosModel.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$adminIngresosModel = new AdminIngresosModel($pdo);

$filtroNombre = trim((string) ($_GET['nombre'] ?? ''));
$filtroRol = trim((string) ($_GET['rol'] ?? ''));
$filtroFechaDesde = trim((string) ($_GET['fecha_desde'] ?? ''));
$filtroFechaHasta = trim((string) ($_GET['fecha_hasta'] ?? ''));

$ingresos = $adminIngresosModel->obtenerIngresos(
    $filtroNombre,
    $filtroRol,
    $filtroFechaDesde,
    $filtroFechaHasta
);
$totalIngresos = count($ingresos);

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Usuario';
}

require __DIR__ . '/../../view/admin/adminIngresosView.php';
