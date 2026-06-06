<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/admin/adminSolicitudesPaginaWebSolicitudesModel.php';
require_once __DIR__ . '/../../mail/Mail.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$solicitudesPaginaWebModel = new AdminSolicitudesPaginaWebSolicitudesModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = (string) ($_POST['accion_solicitud_externa'] ?? '');
    $solicitudId = (int) ($_POST['solicitud_externa_id'] ?? 0);
    $estado = 'accion_invalida';
    $mensaje = 'No pudimos procesar la accion solicitada.';

    if ($solicitudId > 0) {
        $solicitud = $solicitudesPaginaWebModel->obtenerSolicitudExternaPorId($solicitudId);

        if (!$solicitud) {
            $estado = 'solicitud_no_encontrada';
            $mensaje = 'No encontramos la solicitud seleccionada.';
        } elseif ($accion === 'alta_usuario') {
            $passwordPlano = bin2hex(random_bytes(6)) . 'A1!';
            $resultadoUsuario = $solicitudesPaginaWebModel->crearClienteDesdeSolicitud($solicitud, $passwordPlano);
            $estado = (string) ($resultadoUsuario['estado'] ?? 'error_usuario');
            $mensaje = (string) ($resultadoUsuario['mensaje'] ?? 'No se pudo crear el usuario.');

            if (($resultadoUsuario['ok'] ?? false) === true) {
                $resultadoCorreo = \SVE\Mail\Mailer::enviarNuevoUsuarioCliente([
                    'correo' => (string) ($solicitud['correo'] ?? ''),
                    'nombre' => (string) ($solicitud['nombre'] ?? ''),
                    'password' => $passwordPlano,
                    'link' => 'https://impulsagroup.com/ingreso.html',
                    'user_auth_id' => (int) ($resultadoUsuario['usuario']['id'] ?? 0),
                ]);

                if (($resultadoCorreo['ok'] ?? false) === true) {
                    $estado = 'usuario_creado';
                    $mensaje = 'Usuario cliente creado y correo enviado correctamente.';
                } else {
                    $estado = 'usuario_creado_correo_fallido';
                    $mensaje = 'Usuario cliente creado, pero fallo el envio del correo: ' . (string) ($resultadoCorreo['error'] ?? 'Error no informado.');
                }
            }
        } elseif ($accion === 'crear_proyecto') {
            $correo = authSanitizarCorreo($solicitud['correo'] ?? '');
            $cliente = $correo !== '' ? $solicitudesPaginaWebModel->obtenerUsuarioPorCorreo($correo) : null;

            if (!$cliente) {
                $estado = 'proyecto_sin_usuario';
                $mensaje = 'Antes de crear el proyecto tenes que generar o asociar el usuario cliente.';
            } elseif (($cliente['rol'] ?? '') !== 'impulsa_cliente') {
                $estado = 'usuario_rol_invalido';
                $mensaje = 'El correo ya existe, pero no corresponde al rol impulsa_cliente.';
            } else {
                $resultadoProyecto = $solicitudesPaginaWebModel->crearProyectoDesdeSolicitudExterna(
                    $solicitud,
                    (int) $cliente['id'],
                    (int) $usuario['id']
                );
                $estado = (string) ($resultadoProyecto['estado'] ?? 'error_proyecto');
                $mensaje = (string) ($resultadoProyecto['mensaje'] ?? 'No se pudo crear el proyecto.');
            }
        }
    }

    $_SESSION['solicitudes_web_estado'] = ['estado' => $estado, 'mensaje' => $mensaje];
    header('Location: /impulsa_emprende/controller/admin/adminSolicitudesPaginaWebSolicitudesController.php');
    exit;
}

$solicitudesPaginaWeb = $solicitudesPaginaWebModel->obtenerSolicitudes();
$solicitudesPaginaWebExternas = $solicitudesPaginaWebModel->obtenerSolicitudesExternas();
$mensajeEstadoSolicitudesWeb = $_SESSION['solicitudes_web_estado'] ?? null;
unset($_SESSION['solicitudes_web_estado']);

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
