<?php

function marketingUsuarioPuedeGestionar(?string $rol): bool
{
    return in_array((string) $rol, ['impulsa_administrador', 'impulsa_marketing'], true);
}

function marketingUsuarioPuedeVerCliente(?string $rol): bool
{
    return in_array((string) $rol, ['impulsa_emprendedor', 'impulsa_cliente'], true);
}

function marketingRedireccionRol(string $rol): string
{
    return [
        'impulsa_administrador' => '/impulsa_emprende/controller/admin/adminMarketingController.php',
        'impulsa_marketing' => '/impulsa_emprende/controller/marketing/marketingDashboardController.php',
        'impulsa_emprendedor' => '/impulsa_emprende/controller/emprendedor/EmprendedorMarketingController.php',
        'impulsa_cliente' => '/impulsa_emprende/controller/client/ClienteMarketingController.php',
    ][$rol] ?? '/auth/login.php';
}

function marketingEstadoPlanEtiqueta(?string $estado): string
{
    return [
        'draft' => 'Borrador',
        'published' => 'Publicado',
        'paused' => 'Pausado',
        'archived' => 'Archivado',
    ][$estado ?? ''] ?? ucfirst((string) $estado);
}

function marketingEstadoSuscripcionEtiqueta(?string $estado): string
{
    return [
        'requested' => 'Solicitado',
        'meeting_scheduled' => 'Reunion agendada',
        'approved_manually' => 'Aprobado',
        'pending_payment' => 'Pago pendiente',
        'active' => 'Activo',
        'paused' => 'Pausado',
        'completed' => 'Completado',
        'cancelled' => 'Cancelado',
    ][$estado ?? ''] ?? ucfirst(str_replace('_', ' ', (string) $estado));
}

function marketingChipEstadoClase(?string $estado): string
{
    return [
        'published' => 'im-chip--exito',
        'active' => 'im-chip--exito',
        'completed' => 'im-chip--completado',
        'requested' => 'im-chip--alerta',
        'meeting_scheduled' => 'im-chip--alerta',
        'pending_payment' => 'im-chip--alerta',
        'paused' => 'im-chip--alerta',
        'cancelled' => 'im-chip--estado-cancelado',
        'archived' => 'im-chip--estado-cancelado',
    ][$estado ?? ''] ?? '';
}

function marketingFormatoMoneda(mixed $valor, ?string $moneda = 'ARS'): string
{
    return trim((string) ($moneda ?: 'ARS')) . ' ' . number_format((float) $valor, 2, ',', '.');
}

function marketingSlug(string $texto): string
{
    $texto = strtolower(trim($texto));
    $texto = preg_replace('/[^a-z0-9]+/i', '-', $texto) ?: 'plan';
    return trim($texto, '-') ?: 'plan';
}

function marketingJson(mixed $valor): string
{
    return htmlspecialchars(json_encode($valor, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8');
}

function marketingAyudaCampo(string $label, string $tooltip): string
{
    $labelSeguro = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $tooltipSeguro = htmlspecialchars($tooltip, ENT_QUOTES, 'UTF-8');
    $badge = '<span class="marketing-help-badge im-tooltip" data-tooltip="' . $tooltipSeguro . '" aria-label="' . $tooltipSeguro . '">?</span>';

    if ($labelSeguro === '') {
        return $badge;
    }

    return '<span class="marketing-field-label">' . $labelSeguro . $badge . '</span>';
}
