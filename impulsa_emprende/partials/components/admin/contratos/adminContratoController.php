<?php

require_once __DIR__ . '/adminContratoModel.php';

$adminContratoModel = new AdminContratoModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion_proyecto'] ?? '') === 'contrato_guardar') {
    $projectId = (int) ($_POST['project_id'] ?? 0);
    $estado = 'error';
    $mensaje = 'No pudimos guardar el contrato.';

    try {
        if ($projectId <= 0 || !$adminContratoModel->existeProyecto($projectId)) {
            throw new RuntimeException('El proyecto seleccionado no es valido.');
        }

        $nombre = trim((string) ($_POST['contract_name'] ?? ''));
        $texto = trim((string) ($_POST['contract_text'] ?? ''));

        if ($nombre === '') {
            throw new RuntimeException('Ingresa un nombre para el contrato.');
        }
        if ($texto === '') {
            throw new RuntimeException('Ingresa el contenido del contrato.');
        }

        $adminContratoModel->guardarContrato([
            'project_id' => $projectId,
            'contract_name' => $nombre,
            'contract_text' => $texto,
            'contract_html' => nl2br(htmlspecialchars($texto, ENT_QUOTES, 'UTF-8')),
            'admin_user_id' => (int) ($usuario['id'] ?? 0),
        ]);

        $estado = 'ok';
        $mensaje = 'Contrato guardado correctamente.';
    } catch (Throwable $e) {
        $estado = 'error';
        $mensaje = $e->getMessage();
    }

    $_SESSION['admin_proyectos_estado'] = ['estado' => $estado, 'mensaje' => $mensaje];
    header('Location: /impulsa_emprende/controller/admin/adminProyectosController.php');
    exit;
}

$contratosPorProyecto = $adminContratoModel->obtenerContratosPorProyecto();
