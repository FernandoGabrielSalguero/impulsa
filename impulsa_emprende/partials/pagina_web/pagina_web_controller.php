<?php

require_once __DIR__ . '/pagina_web_view_Model.php';
require_once __DIR__ . '/../../mail/Mail.php';

$paginaWebPartialModel = new PaginaWebViewModel($pdo);
$paginaWebSolicitud = $paginaWebPartialModel->obtenerSolicitud((int) $usuario['id']);
$paginaWebCategorias = $paginaWebPartialModel->obtenerCategorias();
$paginaWebSubcategorias = $paginaWebPartialModel->obtenerSubcategorias();
$paginaWebUbicaciones = $paginaWebPartialModel->obtenerUbicaciones();
$paginaWebCamposUsados = $paginaWebPartialModel->camposUsados();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['pagina_web_accion'] ?? '') === 'guardar_pagina_web') {
    if (empty($paginaWebDefinicionCompleta)) {
        $_SESSION['pagina_web_snackbar'] = [
            'mensaje' => 'Primero completa mision, vision y buyer persona.',
            'estado' => 'error',
        ];
    } else {
        try {
            $paginaWebSolicitudGuardada = $paginaWebPartialModel->guardar((int) $usuario['id'], $_POST);
            $buscarNombrePorId = static function (array $items, mixed $id): string {
                foreach ($items as $item) {
                    if ((int) ($item['id'] ?? 0) === (int) $id) {
                        return (string) ($item['nombre'] ?? '');
                    }
                }

                return '';
            };

            \SVE\Mail\Mailer::enviarSolicitudPaginaWeb($paginaWebSolicitudGuardada + [
                'user_auth_id' => (int) $usuario['id'],
                'correo' => (string) ($usuario['correo'] ?? ''),
                'nombre' => (string) ($paginaWebSolicitudGuardada['nombre_fundador'] ?? ''),
                'rubro_categoria' => $buscarNombrePorId($paginaWebCategorias, $paginaWebSolicitudGuardada['rubro_categoria_id'] ?? null),
                'rubro_subcategoria' => $buscarNombrePorId($paginaWebSubcategorias, $paginaWebSolicitudGuardada['rubro_subcategoria_id'] ?? null),
            ]);

            $_SESSION['pagina_web_snackbar'] = [
                'mensaje' => 'Solicitud de pagina web enviada correctamente.',
                'estado' => 'exito',
            ];
        } catch (Throwable $e) {
            $_SESSION['pagina_web_snackbar'] = [
                'mensaje' => $paginaWebSolicitud
                    ? 'Ya existe una solicitud de pagina web para tu usuario.'
                    : ($e instanceof InvalidArgumentException ? $e->getMessage() : 'No pudimos guardar la solicitud en este momento.'),
                'estado' => 'error',
            ];
        }
    }

    header('Location: ' . strtok($_SERVER['REQUEST_URI'] ?? '', '?'));
    exit;
}
