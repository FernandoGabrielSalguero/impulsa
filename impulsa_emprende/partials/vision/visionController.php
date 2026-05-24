<?php

require_once __DIR__ . '/visionModel.php';

$visionModel = new VisionModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['vision_accion'] ?? '') === 'guardar_vision') {
    try {
        $visionModel->guardar((int) $usuario['id'], $_POST);
        $_SESSION['definicion_snackbar'] = [
            'mensaje' => 'Vision guardada correctamente.',
            'estado' => 'exito',
        ];
    } catch (Throwable $e) {
        $_SESSION['definicion_snackbar'] = [
            'mensaje' => 'No pudimos guardar la vision en este momento.',
            'estado' => 'error',
        ];
    }

    header('Location: ' . strtok($_SERVER['REQUEST_URI'] ?? '', '?') . '#vision');
    exit;
}

$visionDatos = $visionModel->obtener((int) $usuario['id']);
$visionCompleta = $visionModel->estaCompleto($visionDatos);
