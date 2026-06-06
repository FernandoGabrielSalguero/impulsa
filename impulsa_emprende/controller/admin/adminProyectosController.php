<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/admin/adminProyectosModel.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$adminProyectosModel = new AdminProyectosModel($pdo);

$faseEstados = ['pending', 'in_progress', 'blocked', 'done'];
$objetivoEstados = ['pending', 'in_progress', 'ready_for_review', 'delivered'];
$objetivoTipos = ['document', 'design', 'development', 'deployment', 'training', 'other'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = (string) ($_POST['accion_proyecto'] ?? '');
    $projectId = (int) ($_POST['project_id'] ?? 0);
    $estado = 'error';
    $mensaje = 'No pudimos procesar la accion solicitada.';

    try {
        if ($projectId <= 0 || !$adminProyectosModel->existeProyecto($projectId)) {
            throw new RuntimeException('El proyecto seleccionado no es valido.');
        }

        if ($accion === 'crear_fase') {
            $titulo = trim((string) ($_POST['title'] ?? ''));
            $status = (string) ($_POST['status'] ?? 'pending');
            if ($titulo === '') {
                throw new RuntimeException('Ingresá un titulo para la fase.');
            }
            if ($adminProyectosModel->existeFaseConTitulo($projectId, $titulo)) {
                throw new RuntimeException('Ya existe una fase con ese titulo para este proyecto.');
            }
            if (!in_array($status, $faseEstados, true)) {
                $status = 'pending';
            }

            $adminProyectosModel->crearFase([
                'project_id' => $projectId,
                'title' => $titulo,
                'description' => trim((string) ($_POST['description'] ?? '')),
                'duration_days' => trim((string) ($_POST['duration_days'] ?? '')),
                'phase_order' => (int) ($_POST['phase_order'] ?? 1),
                'status' => $status,
                'due_date' => trim((string) ($_POST['due_date'] ?? '')),
            ]);
            $estado = 'ok';
            $mensaje = 'Fase creada correctamente.';
        } elseif ($accion === 'crear_objetivo') {
            $titulo = trim((string) ($_POST['title'] ?? ''));
            $phaseId = (int) ($_POST['phase_id'] ?? 0);
            $status = (string) ($_POST['status'] ?? 'pending');
            $type = (string) ($_POST['deliverable_type'] ?? 'other');

            if ($titulo === '') {
                throw new RuntimeException('Ingresá un titulo para el objetivo.');
            }
            if ($adminProyectosModel->existeObjetivoConTitulo($projectId, $titulo)) {
                throw new RuntimeException('Ya existe un objetivo con ese titulo para este proyecto.');
            }
            if (!$adminProyectosModel->fasePerteneceAProyecto($phaseId, $projectId)) {
                throw new RuntimeException('La fase seleccionada no pertenece a este proyecto.');
            }
            if (!in_array($status, $objetivoEstados, true)) {
                $status = 'pending';
            }
            if (!in_array($type, $objetivoTipos, true)) {
                $type = 'other';
            }

            $adminProyectosModel->crearObjetivo([
                'project_id' => $projectId,
                'phase_id' => $phaseId,
                'title' => $titulo,
                'description' => trim((string) ($_POST['description'] ?? '')),
                'deliverable_type' => $type,
                'status' => $status,
                'due_date' => trim((string) ($_POST['due_date'] ?? '')),
                'client_visible' => isset($_POST['client_visible']) ? 1 : 0,
            ]);
            $estado = 'ok';
            $mensaje = 'Objetivo creado correctamente.';
        } elseif ($accion === 'guardar_contrato') {
            $nombre = trim((string) ($_POST['contract_name'] ?? ''));
            $texto = trim((string) ($_POST['contract_text'] ?? ''));

            if ($nombre === '') {
                throw new RuntimeException('Ingresá un nombre para el contrato.');
            }
            if ($texto === '') {
                throw new RuntimeException('Ingresá el contenido del contrato.');
            }

            $html = nl2br(htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'));
            $adminProyectosModel->guardarContrato([
                'project_id' => $projectId,
                'contract_name' => $nombre,
                'contract_text' => $texto,
                'contract_html' => $html,
                'admin_user_id' => (int) $usuario['id'],
            ]);
            $estado = 'ok';
            $mensaje = 'Contrato guardado correctamente.';
        }
    } catch (Throwable $e) {
        $estado = 'error';
        $mensaje = $e->getMessage();
    }

    $_SESSION['admin_proyectos_estado'] = ['estado' => $estado, 'mensaje' => $mensaje];
    header('Location: /impulsa_emprende/controller/admin/adminProyectosController.php');
    exit;
}

$proyectos = $adminProyectosModel->obtenerProyectos();
$fasesPorProyecto = $adminProyectosModel->obtenerFasesPorProyecto();
$objetivosPorProyecto = $adminProyectosModel->obtenerObjetivosPorProyecto();
$contratosPorProyecto = $adminProyectosModel->obtenerContratosPorProyecto();
$mensajeEstadoProyectos = $_SESSION['admin_proyectos_estado'] ?? null;
unset($_SESSION['admin_proyectos_estado']);

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Usuario';
}

require __DIR__ . '/../../view/admin/adminProyectosView.php';
