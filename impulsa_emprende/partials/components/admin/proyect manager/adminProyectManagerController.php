<?php

require_once __DIR__ . '/adminProyectManagerModel.php';

$adminProyectManagerModel = new AdminProyectManagerModel($pdo);
$pmEsAjax = static function (): bool {
    $accept = (string) ($_SERVER['HTTP_ACCEPT'] ?? '');
    $requestedWith = (string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');

    return str_contains($accept, 'application/json') || strtolower($requestedWith) === 'xmlhttprequest';
};
$pmProyectoEstados = ['draft', 'planned', 'in_progress', 'paused', 'in_review', 'completed', 'cancelled'];
$pmProyectoPrioridades = ['low', 'medium', 'high', 'urgent'];
$pmFaseEstados = ['pending', 'in_progress', 'blocked', 'done'];
$pmObjetivoEstados = ['pending', 'in_progress', 'ready_for_review', 'delivered'];
$pmObjetivoTipos = ['document', 'design', 'development', 'deployment', 'training', 'other'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && str_starts_with((string) ($_POST['accion_proyecto'] ?? ''), 'pm_')) {
    $accion = (string) ($_POST['accion_proyecto'] ?? '');
    $projectId = (int) ($_POST['project_id'] ?? 0);
    $estado = 'error';
    $mensaje = 'No pudimos procesar la accion del gestor.';

    try {
        if ($projectId <= 0 || !$adminProyectManagerModel->existeProyecto($projectId)) {
            throw new RuntimeException('El proyecto seleccionado no es valido.');
        }

        if ($accion === 'pm_actualizar_proyecto') {
            $nombre = trim((string) ($_POST['project_name'] ?? ''));
            $status = (string) ($_POST['status'] ?? 'planned');
            $priority = (string) ($_POST['priority'] ?? 'medium');
            $responsableId = (int) ($_POST['manager_user_id'] ?? 0);

            if ($nombre === '') {
                throw new RuntimeException('Ingresa un nombre para el proyecto.');
            }
            if (!in_array($status, $pmProyectoEstados, true)) {
                $status = 'planned';
            }
            if (!in_array($priority, $pmProyectoPrioridades, true)) {
                $priority = 'medium';
            }
            if ($responsableId <= 0 || !$adminProyectManagerModel->responsableExiste($responsableId)) {
                throw new RuntimeException('Selecciona un responsable valido para el proyecto.');
            }

            $adminProyectManagerModel->actualizarProyecto($projectId, [
                'project_name' => $nombre,
                'manager_user_id' => $responsableId,
                'summary' => trim((string) ($_POST['summary'] ?? '')),
                'scope_summary' => trim((string) ($_POST['scope_summary'] ?? '')),
                'status' => $status,
                'priority' => $priority,
                'start_date' => trim((string) ($_POST['start_date'] ?? '')),
                'client_visible' => isset($_POST['client_visible']) ? 1 : 0,
            ]);

            $mensaje = 'Datos del proyecto actualizados correctamente.';
            $estado = 'ok';
        } elseif ($accion === 'pm_crear_fase' || $accion === 'pm_editar_fase') {
            $phaseId = (int) ($_POST['phase_id'] ?? 0);
            $titulo = trim((string) ($_POST['title'] ?? ''));
            $status = (string) ($_POST['status'] ?? 'pending');

            if ($accion === 'pm_editar_fase' && !$adminProyectManagerModel->fasePerteneceAProyecto($phaseId, $projectId)) {
                throw new RuntimeException('La fase seleccionada no pertenece a este proyecto.');
            }
            if ($titulo === '') {
                throw new RuntimeException('Ingresa un titulo para la fase.');
            }
            if ($adminProyectManagerModel->existeFaseConTitulo($projectId, $titulo, $phaseId)) {
                throw new RuntimeException('Ya existe una fase con ese titulo para este proyecto.');
            }
            if (!in_array($status, $pmFaseEstados, true)) {
                $status = 'pending';
            }

            $datos = [
                'project_id' => $projectId,
                'title' => $titulo,
                'description' => trim((string) ($_POST['description'] ?? '')),
                'duration_days' => trim((string) ($_POST['duration_days'] ?? '')),
                'phase_order' => (int) ($_POST['phase_order'] ?? 1),
                'status' => $status,
            ];

            if ($accion === 'pm_editar_fase') {
                $adminProyectManagerModel->actualizarFase($phaseId, $datos);
                $mensaje = 'Fase actualizada correctamente.';
            } else {
                $adminProyectManagerModel->crearFase($datos);
                $mensaje = 'Fase creada correctamente.';
            }

            $estado = 'ok';
        } elseif ($accion === 'pm_eliminar_fase') {
            $phaseId = (int) ($_POST['phase_id'] ?? 0);

            if (!$adminProyectManagerModel->fasePerteneceAProyecto($phaseId, $projectId)) {
                throw new RuntimeException('La fase seleccionada no pertenece a este proyecto.');
            }

            $adminProyectManagerModel->eliminarFase($projectId, $phaseId);
            $mensaje = 'Fase eliminada correctamente.';
            $estado = 'ok';
        } elseif ($accion === 'pm_crear_objetivo' || $accion === 'pm_editar_objetivo') {
            $objectiveId = (int) ($_POST['objective_id'] ?? 0);
            $phaseId = (int) ($_POST['phase_id'] ?? 0);
            $titulo = trim((string) ($_POST['title'] ?? ''));
            $status = (string) ($_POST['status'] ?? 'pending');
            $type = (string) ($_POST['deliverable_type'] ?? 'other');

            if ($accion === 'pm_editar_objetivo' && !$adminProyectManagerModel->objetivoPerteneceAProyecto($objectiveId, $projectId)) {
                throw new RuntimeException('El objetivo seleccionado no pertenece a este proyecto.');
            }
            if ($titulo === '') {
                throw new RuntimeException('Ingresa un titulo para el objetivo.');
            }
            if (!$adminProyectManagerModel->fasePerteneceAProyecto($phaseId, $projectId)) {
                throw new RuntimeException('Selecciona una fase valida de este proyecto.');
            }
            if ($adminProyectManagerModel->existeObjetivoConTituloEnFase($phaseId, $titulo, $objectiveId)) {
                throw new RuntimeException('Ya existe un objetivo con ese titulo en esta fase.');
            }
            if (!in_array($status, $pmObjetivoEstados, true)) {
                $status = 'pending';
            }
            if (!in_array($type, $pmObjetivoTipos, true)) {
                $type = 'other';
            }

            $datos = [
                'project_id' => $projectId,
                'phase_id' => $phaseId,
                'title' => $titulo,
                'description' => trim((string) ($_POST['description'] ?? '')),
                'deliverable_type' => $type,
                'status' => $status,
                'due_date' => trim((string) ($_POST['due_date'] ?? '')),
                'client_visible' => isset($_POST['client_visible']) ? 1 : 0,
            ];

            if ($accion === 'pm_editar_objetivo') {
                $adminProyectManagerModel->actualizarObjetivo($objectiveId, $datos);
                $mensaje = 'Objetivo actualizado correctamente.';
            } else {
                $adminProyectManagerModel->crearObjetivo($datos);
                $mensaje = 'Objetivo creado correctamente.';
            }

            $estado = 'ok';
        } elseif ($accion === 'pm_eliminar_objetivo') {
            $objectiveId = (int) ($_POST['objective_id'] ?? 0);

            if (!$adminProyectManagerModel->objetivoPerteneceAProyecto($objectiveId, $projectId)) {
                throw new RuntimeException('El objetivo seleccionado no pertenece a este proyecto.');
            }

            $adminProyectManagerModel->eliminarObjetivo($projectId, $objectiveId);
            $mensaje = 'Objetivo eliminado correctamente.';
            $estado = 'ok';
        }

        if ($estado === 'ok') {
            $adminProyectManagerModel->recalcularProyecto($projectId);
        }
    } catch (Throwable $e) {
        $estado = 'error';
        $mensaje = $e->getMessage();
    }

    if ($pmEsAjax()) {
        $proyectoActualizado = $projectId > 0 ? $adminProyectManagerModel->obtenerProyecto($projectId) : null;
        $fasesActualizadas = $projectId > 0 ? $adminProyectManagerModel->obtenerFasesProyecto($projectId) : [];
        $objetivosActualizados = $projectId > 0 ? $adminProyectManagerModel->obtenerObjetivosProyecto($projectId) : [];

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'ok' => $estado === 'ok',
            'estado' => $estado,
            'mensaje' => $mensaje,
            'project_id' => $projectId,
            'proyecto' => $proyectoActualizado,
            'fases' => $fasesActualizadas,
            'objetivos' => $objetivosActualizados,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    $_SESSION['admin_proyectos_estado'] = ['estado' => $estado, 'mensaje' => $mensaje];
    header('Location: /impulsa_emprende/controller/admin/adminProyectosController.php');
    exit;
}

$fasesPorProyecto = $adminProyectManagerModel->obtenerFasesPorProyecto();
$objetivosPorProyecto = $adminProyectManagerModel->obtenerObjetivosPorProyecto();
$pmResponsables = $adminProyectManagerModel->obtenerResponsables();
