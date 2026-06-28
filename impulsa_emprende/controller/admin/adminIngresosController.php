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
$filtroFecha = trim((string) ($_GET['fecha'] ?? ''));

$formatearHoraAjustada = static function (string $hora): string {
    $hora = trim($hora);
    if ($hora === '') {
        return '';
    }

    $partes = explode(':', $hora);
    if (count($partes) < 2) {
        return $hora;
    }

    $horas = (int) $partes[0];
    $minutos = str_pad((string) ((int) $partes[1]), 2, '0', STR_PAD_LEFT);
    $horasAjustadas = ($horas - 3 + 24) % 24;

    return str_pad((string) $horasAjustadas, 2, '0', STR_PAD_LEFT) . ':' . $minutos;
};

// Respuesta AJAX: devuelve JSON con los ingresos filtrados
if (($_GET['ajax'] ?? '') === 'ingresos') {
    header('Content-Type: application/json; charset=UTF-8');

    $ingresos = $adminIngresosModel->obtenerIngresos(
        $filtroNombre,
        $filtroRol,
        $filtroFecha
    );
    $totalIngresos = count($ingresos);

    $formatearRol = static function (string $rol): string {
        return ucwords(str_replace('_', ' ', $rol));
    };
    $formatearFecha = static function (string $fecha): string {
        return date('d/m/Y', strtotime($fecha));
    };
    $h = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');

    echo json_encode([
        'ok' => true,
        'total' => $totalIngresos,
        'ingresos' => array_map(static function (array $ingreso) use ($h, $formatearRol, $formatearFecha, $formatearHoraAjustada): array {
            return [
                'nombre_usuario' => $h($ingreso['nombre_usuario'] ?? ''),
                'rol' => $h($formatearRol($ingreso['rol'] ?? '')),
                'fecha_ingreso' => $h($formatearFecha($ingreso['fecha_ingreso'] ?? '')),
                'hora_ingreso' => $h($formatearHoraAjustada($ingreso['hora_ingreso'] ?? '')),
                'created_at' => $h($formatearFecha($ingreso['created_at'] ?? '')),
            ];
        }, $ingresos),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$ingresos = $adminIngresosModel->obtenerIngresos(
    $filtroNombre,
    $filtroRol,
    $filtroFecha
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
