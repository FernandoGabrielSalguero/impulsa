<?php

require_once __DIR__ . '/../config.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function authRedirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function authUsuarioActual(): ?array
{
    if (empty($_SESSION['usuario_id'])) {
        return null;
    }

    return [
        'id' => (int) $_SESSION['usuario_id'],
        'correo' => $_SESSION['usuario'] ?? '',
        'rol' => $_SESSION['rol'] ?? '',
    ];
}

function authRequiereLogin(): array
{
    $usuario = authUsuarioActual();
    if (!$usuario) {
        authRedirect('/auth/login.php');
    }

    return $usuario;
}

function authRequiereRol(string $rol): array
{
    $usuario = authRequiereLogin();
    if (($usuario['rol'] ?? '') !== $rol) {
        authRedirect('/auth/login.php?estado=sin_permiso');
    }

    return $usuario;
}

function authDashboardPorRol(string $rol): ?string
{
    $dashboards = [
        'impulsa_administrador' => '/impulsa_emprende/controller/admin/dashboard.php',
        'impulsa_usuario' => '/impulsa_emprende/controller/user/UserDashboardController.php',
    ];

    return $dashboards[$rol] ?? null;
}

function authRedirigirPorRol(string $rol): void
{
    $dashboard = authDashboardPorRol($rol);
    if ($dashboard !== null) {
        authRedirect($dashboard);
    }

    authRedirect('/auth/login.php?estado=rol_pendiente');
}

function authSanitizarCorreo(?string $correo): string
{
    return strtolower(trim((string) $correo));
}

function authMensajeEstado(?string $estado): string
{
    $mensajes = [
        'registrado' => 'Registro creado. Te enviamos un correo para verificar tu dirección.',
        'correo_verificado' => 'Correo verificado correctamente. Ya podés ingresar.',
        'verificacion_invalida' => 'El enlace de verificación no es válido o ya fue utilizado.',
        'verificacion_error' => 'No pudimos verificar el correo en este momento.',
        'rol_pendiente' => 'Tu usuario existe, pero el dashboard de tu rol todavía no está habilitado.',
        'sin_permiso' => 'No tenés permisos para acceder a ese panel.',
        'logout' => 'Sesión cerrada correctamente.',
    ];

    return $mensajes[$estado ?? ''] ?? '';
}
