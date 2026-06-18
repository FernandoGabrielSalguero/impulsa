<?php

class AdminsTareasModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerTareas(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT at.*,
                    ur.correo AS responsable_correo,
                    ucr.correo AS creador_correo,
                    uir.nombre AS responsable_nombre,
                    uir.apellido AS responsable_apellido,
                    uir.apodo AS responsable_apodo,
                    uic.nombre AS creador_nombre,
                    uic.apellido AS creador_apellido,
                    uic.apodo AS creador_apodo
             FROM admin_tareas at
             INNER JOIN user_auth ur ON ur.id = at.responsable_user_id
             INNER JOIN user_auth ucr ON ucr.id = at.created_by_user_id
             LEFT JOIN user_info uir ON uir.user_auth_id = ur.id
             LEFT JOIN user_info uic ON uic.user_auth_id = ucr.id
             ORDER BY at.updated_at DESC, at.id DESC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTareaPorId(int $tareaId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
             FROM admin_tareas
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $tareaId]);
        $tarea = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($tarea) ? $tarea : null;
    }

    public function obtenerOpcionesUsuarios(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ua.id,
                    ua.correo,
                    ua.rol,
                    ui.nombre,
                    ui.apellido,
                    ui.apodo
             FROM user_auth ua
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             ORDER BY COALESCE(NULLIF(TRIM(CONCAT_WS(" ", ui.nombre, ui.apellido)), ""), NULLIF(TRIM(ui.apodo), ""), ua.correo) ASC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearTarea(array $data, int $createdByUserId): array
    {
        if ($createdByUserId <= 0) {
            return ['ok' => false, 'estado' => 'tarea_usuario_creador_invalido'];
        }

        try {
            $payload = $this->validarPayload($data);

            $stmt = $this->pdo->prepare(
                'INSERT INTO admin_tareas
                 (nombre_tarea, responsable_user_id, descripcion, fecha_entrega, prioridad_defcon, reporta_a, estado, created_by_user_id, completed_at)
                 VALUES
                 (:nombre_tarea, :responsable_user_id, :descripcion, :fecha_entrega, :prioridad_defcon, :reporta_a, :estado, :created_by_user_id, :completed_at)'
            );
            $stmt->execute([
                'nombre_tarea' => $payload['nombre_tarea'],
                'responsable_user_id' => $payload['responsable_user_id'],
                'descripcion' => $payload['descripcion'],
                'fecha_entrega' => $payload['fecha_entrega'],
                'prioridad_defcon' => $payload['prioridad_defcon'],
                'reporta_a' => $payload['reporta_a'],
                'estado' => $payload['estado'],
                'created_by_user_id' => $createdByUserId,
                'completed_at' => $payload['estado'] === 'completada' ? date('Y-m-d H:i:s') : null,
            ]);

            return ['ok' => true, 'estado' => 'tarea_creada'];
        } catch (Throwable $exception) {
            return ['ok' => false, 'estado' => $exception->getMessage() !== '' ? $exception->getMessage() : 'tarea_error_crear'];
        }
    }

    public function actualizarTarea(int $tareaId, array $data): array
    {
        if ($tareaId <= 0) {
            return ['ok' => false, 'estado' => 'tarea_id_invalido'];
        }

        $tareaActual = $this->obtenerTareaPorId($tareaId);
        if (!$tareaActual) {
            return ['ok' => false, 'estado' => 'tarea_no_encontrada'];
        }

        try {
            $payload = $this->validarPayload($data);
            $completedAt = null;

            if ($payload['estado'] === 'completada') {
                $completedAt = !empty($tareaActual['completed_at']) ? $tareaActual['completed_at'] : date('Y-m-d H:i:s');
            }

            $stmt = $this->pdo->prepare(
                'UPDATE admin_tareas
                 SET nombre_tarea = :nombre_tarea,
                     responsable_user_id = :responsable_user_id,
                     descripcion = :descripcion,
                     fecha_entrega = :fecha_entrega,
                     prioridad_defcon = :prioridad_defcon,
                     reporta_a = :reporta_a,
                     estado = :estado,
                     completed_at = :completed_at
                 WHERE id = :id'
            );
            $stmt->execute([
                'id' => $tareaId,
                'nombre_tarea' => $payload['nombre_tarea'],
                'responsable_user_id' => $payload['responsable_user_id'],
                'descripcion' => $payload['descripcion'],
                'fecha_entrega' => $payload['fecha_entrega'],
                'prioridad_defcon' => $payload['prioridad_defcon'],
                'reporta_a' => $payload['reporta_a'],
                'estado' => $payload['estado'],
                'completed_at' => $completedAt,
            ]);

            return ['ok' => true, 'estado' => 'tarea_actualizada'];
        } catch (Throwable $exception) {
            return ['ok' => false, 'estado' => $exception->getMessage() !== '' ? $exception->getMessage() : 'tarea_error_actualizar'];
        }
    }

    public function eliminarTarea(int $tareaId): array
    {
        if ($tareaId <= 0) {
            return ['ok' => false, 'estado' => 'tarea_id_invalido'];
        }

        $stmt = $this->pdo->prepare('DELETE FROM admin_tareas WHERE id = :id');
        $stmt->execute(['id' => $tareaId]);

        if ($stmt->rowCount() < 1) {
            return ['ok' => false, 'estado' => 'tarea_no_encontrada'];
        }

        return ['ok' => true, 'estado' => 'tarea_eliminada'];
    }

    private function validarPayload(array $data): array
    {
        $estadosValidos = ['pendiente', 'en_progreso', 'completada', 'cancelada'];
        $nombreTarea = trim((string) ($data['nombre_tarea'] ?? ''));
        $responsableUserId = filter_var($data['responsable_user_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $descripcion = trim((string) ($data['descripcion'] ?? ''));
        $fechaEntrega = trim((string) ($data['fecha_entrega'] ?? ''));
        $prioridadDefcon = filter_var($data['prioridad_defcon'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 5]]);
        $reportaA = trim((string) ($data['reporta_a'] ?? ''));
        $estado = trim((string) ($data['estado'] ?? 'pendiente'));

        if ($nombreTarea === '') {
            throw new RuntimeException('tarea_nombre_invalido');
        }
        if ((function_exists('mb_strlen') ? mb_strlen($nombreTarea, 'UTF-8') : strlen($nombreTarea)) > 180) {
            throw new RuntimeException('tarea_nombre_largo');
        }
        if ($responsableUserId === false) {
            throw new RuntimeException('tarea_responsable_invalido');
        }
        if (!$this->usuarioExiste((int) $responsableUserId)) {
            throw new RuntimeException('tarea_responsable_invalido');
        }
        if ($descripcion === '') {
            throw new RuntimeException('tarea_descripcion_invalida');
        }
        if (!$this->esFechaValida($fechaEntrega)) {
            throw new RuntimeException('tarea_fecha_invalida');
        }
        if ($prioridadDefcon === false) {
            throw new RuntimeException('tarea_prioridad_invalida');
        }
        if ($reportaA === '') {
            throw new RuntimeException('tarea_reporta_invalido');
        }
        if ((function_exists('mb_strlen') ? mb_strlen($reportaA, 'UTF-8') : strlen($reportaA)) > 180) {
            throw new RuntimeException('tarea_reporta_largo');
        }
        if (!in_array($estado, $estadosValidos, true)) {
            throw new RuntimeException('tarea_estado_invalido');
        }

        return [
            'nombre_tarea' => $nombreTarea,
            'responsable_user_id' => (int) $responsableUserId,
            'descripcion' => $descripcion,
            'fecha_entrega' => $fechaEntrega,
            'prioridad_defcon' => (int) $prioridadDefcon,
            'reporta_a' => $reportaA,
            'estado' => $estado,
        ];
    }

    private function usuarioExiste(int $userId): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM user_auth WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $userId]);

        return (bool) $stmt->fetchColumn();
    }

    private function esFechaValida(string $fecha): bool
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            return false;
        }

        [$anio, $mes, $dia] = array_map('intval', explode('-', $fecha));

        return checkdate($mes, $dia, $anio);
    }
}
