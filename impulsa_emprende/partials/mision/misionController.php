<?php

require_once __DIR__ . '/misionModel.php';

$misionModel = new MisionModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['mision_accion'] ?? '') === 'guardar_mision') {
    try {
        $misionModel->guardar((int) $usuario['id'], $_POST);
        $_SESSION['definicion_snackbar'] = [
            'mensaje' => 'Mision guardada correctamente.',
            'estado' => 'exito',
        ];
    } catch (Throwable $e) {
        $_SESSION['definicion_snackbar'] = [
            'mensaje' => 'No pudimos guardar la mision en este momento.',
            'estado' => 'error',
        ];
    }

    header('Location: ' . strtok($_SERVER['REQUEST_URI'] ?? '', '?') . '#mision');
    exit;
}

$misionDatos = $misionModel->obtener((int) $usuario['id']);
$misionCompleta = $misionModel->estaCompleto($misionDatos);
