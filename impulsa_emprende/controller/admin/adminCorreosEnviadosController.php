<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/admin/adminCorreosEnviadosModel.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$adminCorreosEnviadosModel = new AdminCorreosEnviadosModel($pdo);

$filtros = [
    'correo' => trim((string) ($_GET['correo'] ?? '')),
    'asunto' => trim((string) ($_GET['asunto'] ?? '')),
];

$paginaActual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, [
    'options' => ['default' => 1, 'min_range' => 1],
]);
$porPagina = 20;
$totalCorreos = 0;
$totalPaginas = 1;
$correos = [];
$errorCargaCorreos = null;

$normalizarSaltos = static function (string $texto): string {
    $texto = preg_replace("/\r\n?/", "\n", $texto) ?? $texto;
    $texto = preg_replace("/\n{3,}/", "\n\n", $texto) ?? $texto;

    return trim($texto);
};

$htmlALecturaSegura = static function (?string $html) use ($normalizarSaltos): string {
    $html = trim((string) $html);
    if ($html === '') {
        return '';
    }

    $html = preg_replace('/<(br|\/p|\/div|\/li|\/tr|\/h[1-6])\b[^>]*>/i', "\n", $html) ?? $html;
    $html = preg_replace('/<(p|div|li|tr|h[1-6])\b[^>]*>/i', '', $html) ?? $html;
    $texto = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');

    return $normalizarSaltos($texto);
};

$nombreUsuarioRelacionado = static function (array $correo): string {
    $nombreCompleto = trim((string) ($correo['usuario_nombre'] ?? '') . ' ' . (string) ($correo['usuario_apellido'] ?? ''));
    if ($nombreCompleto !== '') {
        return $nombreCompleto;
    }

    $apodo = trim((string) ($correo['usuario_apodo'] ?? ''));
    if ($apodo !== '') {
        return $apodo;
    }

    $usuarioCorreoRelacionado = trim((string) ($correo['usuario_correo'] ?? ''));

    return $usuarioCorreoRelacionado !== '' ? $usuarioCorreoRelacionado : '-';
};

$decodificarMeta = static function (?string $meta) use ($normalizarSaltos): string {
    $meta = trim((string) $meta);
    if ($meta === '') {
        return '';
    }

    $decodificado = json_decode($meta, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $json = json_encode($decodificado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $json === false ? $normalizarSaltos($meta) : $json;
    }

    return $normalizarSaltos($meta);
};

try {
    $totalCorreos = $adminCorreosEnviadosModel->contarCorreos($filtros);
    $totalPaginas = max(1, (int) ceil($totalCorreos / $porPagina));
    $paginaActual = min($paginaActual, $totalPaginas);
    $offset = ($paginaActual - 1) * $porPagina;

    $correos = array_map(static function (array $correo) use ($normalizarSaltos, $htmlALecturaSegura, $nombreUsuarioRelacionado, $decodificarMeta): array {
        $mensajeText = $normalizarSaltos((string) ($correo['mensaje_text'] ?? ''));
        $mensajeHtmlPlano = $htmlALecturaSegura($correo['mensaje_html'] ?? null);
        $contenidoLegible = $mensajeText !== '' ? $mensajeText : $mensajeHtmlPlano;

        if ($contenidoLegible === '') {
            $contenidoLegible = 'No hay contenido disponible para este correo.';
        }

        $correo['usuario_relacionado'] = $nombreUsuarioRelacionado($correo);
        $correo['contenido_legible'] = $contenidoLegible;
        $correo['meta_legible'] = $decodificarMeta($correo['meta'] ?? null);

        return $correo;
    }, $adminCorreosEnviadosModel->obtenerCorreos($filtros, $porPagina, $offset));
} catch (Throwable $exception) {
    $errorCargaCorreos = 'No pudimos cargar el historial de correos enviados.';
}

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Usuario';
}

require __DIR__ . '/../../view/admin/adminCorreosEnviadosView.php';
