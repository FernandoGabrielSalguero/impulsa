<?php

require_once __DIR__ . '/buyerModel.php';

$buyerModel = new BuyerModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['buyer_accion'] ?? '') === 'guardar_buyer') {
    $definicionCompletaAntes = isset($definicionModel)
        && method_exists($definicionModel, 'definicionCompleta')
        && $definicionModel->definicionCompleta((int) $usuario['id']);

    try {
        $buyerModel->guardar((int) $usuario['id'], $_POST);
        $_SESSION['definicion_snackbar'] = [
            'mensaje' => 'Buyer persona guardado correctamente.',
            'estado' => 'exito',
        ];

        if (!$definicionCompletaAntes
            && isset($definicionModel)
            && method_exists($definicionModel, 'definicionCompleta')
            && $definicionModel->definicionCompleta((int) $usuario['id'])) {
            header('Location: /impulsa_emprende/controller/user/UserDashboardController.php');
            exit;
        }
    } catch (Throwable $e) {
        $_SESSION['definicion_snackbar'] = [
            'mensaje' => 'No pudimos guardar el buyer persona en este momento.',
            'estado' => 'error',
        ];
    }

    header('Location: ' . strtok($_SERVER['REQUEST_URI'] ?? '', '?') . '#buyer-persona');
    exit;
}

$buyerDatos = $buyerModel->obtener((int) $usuario['id']);
$buyerCompleto = $buyerModel->estaCompleto($buyerDatos);
