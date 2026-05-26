<?php

require_once __DIR__ . '/pagina_web_view_Model.php';

$paginaWebPartialModel = new PaginaWebViewModel($pdo);
$paginaWebSolicitud = $paginaWebPartialModel->obtenerSolicitud((int) $usuario['id']);
$paginaWebCategorias = $paginaWebPartialModel->obtenerCategorias();
$paginaWebSubcategorias = $paginaWebPartialModel->obtenerSubcategorias();
$paginaWebCamposUsados = $paginaWebPartialModel->camposUsados();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['pagina_web_accion'] ?? '') === 'guardar_pagina_web') {
    if (empty($paginaWebDefinicionCompleta)) {
        $_SESSION['pagina_web_snackbar'] = [
            'mensaje' => 'Primero completa mision, vision y buyer persona.',
            'estado' => 'error',
        ];
    } else {
        try {
            $paginaWebPartialModel->guardar((int) $usuario['id'], $_POST);
            $_SESSION['pagina_web_snackbar'] = [
                'mensaje' => 'Solicitud de pagina web enviada correctamente.',
                'estado' => 'exito',
            ];
        } catch (Throwable $e) {
            $_SESSION['pagina_web_snackbar'] = [
                'mensaje' => $paginaWebSolicitud
                    ? 'Ya existe una solicitud de pagina web para tu usuario.'
                    : 'No pudimos guardar la solicitud en este momento.',
                'estado' => 'error',
            ];
        }
    }

    header('Location: ' . strtok($_SERVER['REQUEST_URI'] ?? '', '?'));
    exit;
}
