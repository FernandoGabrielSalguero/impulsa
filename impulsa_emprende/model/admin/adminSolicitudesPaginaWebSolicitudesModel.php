<?php

class AdminSolicitudesPaginaWebSolicitudesModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerSolicitudes(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT lpr.id,
                    lpr.user_auth_id,
                    lpr.nombre_emprendimiento,
                    lpr.fecha_inicio,
                    lpr.descripcion,
                    lpr.dominio_registrado,
                    lpr.hosting_propio,
                    lpr.cantidad_colaboradores,
                    lpr.nombre_fundador,
                    lpr.vende_productos,
                    lpr.vende_servicios,
                    lpr.ya_factura,
                    lpr.espacio_fisico,
                    lpr.pais,
                    lpr.provincia,
                    lpr.localidad,
                    lpr.calle,
                    lpr.numero,
                    lpr.telefono_contacto,
                    lpr.completado,
                    lpr.created_at,
                    lpr.updated_at,
                    ua.correo AS usuario_correo,
                    ui.nombre AS usuario_nombre,
                    ui.apellido AS usuario_apellido,
                    ui.apodo AS usuario_apodo,
                    rec.nombre AS rubro_categoria,
                    res.nombre AS rubro_subcategoria,
                    ua.id AS cliente_user_id,
                    p.id AS proyecto_id,
                    p.status AS proyecto_estado
             FROM landing_page_request lpr
             INNER JOIN user_auth ua ON ua.id = lpr.user_auth_id
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             LEFT JOIN rubro_emprendedor_categoria rec ON rec.id = lpr.rubro_categoria_id
             LEFT JOIN rubro_emprendedor_subcategoria res ON res.id = lpr.rubro_subcategoria_id
             LEFT JOIN projects p ON p.source_type = :source_type AND p.source_id = lpr.id
             ORDER BY lpr.created_at DESC, lpr.id DESC'
        );
        $stmt->execute(['source_type' => 'landing_page_request']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSolicitudPorId(int $solicitudId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT lpr.id,
                    lpr.user_auth_id,
                    lpr.nombre_emprendimiento,
                    lpr.fecha_inicio,
                    lpr.descripcion,
                    lpr.dominio_registrado,
                    lpr.hosting_propio,
                    lpr.cantidad_colaboradores,
                    lpr.nombre_fundador,
                    lpr.vende_productos,
                    lpr.vende_servicios,
                    lpr.ya_factura,
                    lpr.espacio_fisico,
                    lpr.pais,
                    lpr.provincia,
                    lpr.localidad,
                    lpr.calle,
                    lpr.numero,
                    lpr.telefono_contacto,
                    lpr.completado,
                    lpr.created_at,
                    lpr.updated_at,
                    ua.correo AS usuario_correo,
                    ui.nombre AS usuario_nombre,
                    ui.apellido AS usuario_apellido,
                    ui.apodo AS usuario_apodo,
                    rec.nombre AS rubro_categoria,
                    res.nombre AS rubro_subcategoria,
                    ua.id AS cliente_user_id,
                    p.id AS proyecto_id,
                    p.status AS proyecto_estado
             FROM landing_page_request lpr
             INNER JOIN user_auth ua ON ua.id = lpr.user_auth_id
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             LEFT JOIN rubro_emprendedor_categoria rec ON rec.id = lpr.rubro_categoria_id
             LEFT JOIN rubro_emprendedor_subcategoria res ON res.id = lpr.rubro_subcategoria_id
             LEFT JOIN projects p ON p.source_type = :source_type AND p.source_id = lpr.id
             WHERE lpr.id = :id
             LIMIT 1'
        );
        $stmt->execute([
            'source_type' => 'landing_page_request',
            'id' => $solicitudId,
        ]);
        $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

        return $solicitud ?: null;
    }

    public function obtenerSolicitudesExternas(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT lpre.id,
                    lpre.nombre,
                    lpre.nombre_proyecto,
                    lpre.correo,
                    lpre.whatsapp,
                    q1_nombre_comercial,
                    q2_actividad,
                    q3_objetivo,
                    q4_publico,
                    q5_accion_principal,
                    q6_propuestas_destacar,
                    q7_diferencial,
                    q8_secciones,
                    q9_textos,
                    q10_contacto,
                    q11_material_marca,
                    q12_estilo_visual,
                    q13_referencias,
                    q14_recursos_visuales,
                    q15_imagenes_apoyo,
                    q16_dominio_hosting,
                    q17_correos_corporativos,
                    q18_requerimientos_adicionales,
                    lpre.form_source,
                    lpre.ip_address,
                    lpre.user_agent,
                    lpre.created_at,
                    ua.id AS cliente_user_id,
                    ua.rol AS cliente_rol,
                    ua.email_verified_at AS cliente_email_verified_at,
                    p.id AS proyecto_id,
                    p.status AS proyecto_estado
             FROM landing_page_requests_external lpre
             LEFT JOIN user_auth ua ON ua.correo = lpre.correo
             LEFT JOIN projects p ON p.source_type = :source_type AND p.source_id = lpre.id
             ORDER BY lpre.created_at DESC, lpre.id DESC'
        );
        $stmt->execute(['source_type' => 'landing_page_requests_external']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSolicitudExternaPorId(int $solicitudId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, nombre, nombre_proyecto, correo, whatsapp,
                    q1_nombre_comercial, q2_actividad, q3_objetivo, q4_publico,
                    q5_accion_principal, q6_propuestas_destacar, q7_diferencial,
                    q8_secciones, q9_textos, q10_contacto, q11_material_marca,
                    q12_estilo_visual, q13_referencias, q14_recursos_visuales,
                    q15_imagenes_apoyo, q16_dominio_hosting, q17_correos_corporativos,
                    q18_requerimientos_adicionales, form_source, created_at
             FROM landing_page_requests_external
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $solicitudId]);
        $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

        return $solicitud ?: null;
    }

    public function obtenerUsuarioPorCorreo(string $correo): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, correo, rol, email_verified_at
             FROM user_auth
             WHERE correo = :correo
             LIMIT 1'
        );
        $stmt->execute(['correo' => $correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        return $usuario ?: null;
    }

    public function crearClienteDesdeSolicitud(array $solicitud, string $passwordPlano): array
    {
        $correo = strtolower(trim((string) ($solicitud['correo'] ?? '')));
        if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'estado' => 'sin_correo', 'mensaje' => 'La solicitud no tiene un correo valido.'];
        }

        $usuarioExistente = $this->obtenerUsuarioPorCorreo($correo);
        if ($usuarioExistente) {
            return [
                'ok' => false,
                'estado' => 'correo_existente',
                'mensaje' => 'Ya existe un usuario registrado con ese correo.',
                'usuario' => $usuarioExistente,
            ];
        }

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO user_auth (correo, password, rol, verification_token, email_verified_at, created_at, updated_at)
                 VALUES (:correo, :password, :rol, NULL, NOW(), NOW(), NOW())'
            );
            $stmt->execute([
                'correo' => $correo,
                'password' => password_hash($passwordPlano, PASSWORD_DEFAULT),
                'rol' => 'impulsa_cliente',
            ]);

            $userId = (int) $this->pdo->lastInsertId();
            $stmt = $this->pdo->prepare(
                'INSERT INTO user_contacto (user_auth_id, correo, check_correo, permison_correo, whatsapp, check_whatsapp, permison_whatsapp, created_at, updated_at)
                 VALUES (:user_auth_id, :correo, 1, 1, :whatsapp, :check_whatsapp, 1, NOW(), NOW())'
            );
            $whatsapp = trim((string) ($solicitud['whatsapp'] ?? ''));
            $stmt->execute([
                'user_auth_id' => $userId,
                'correo' => $correo,
                'whatsapp' => $whatsapp !== '' ? $whatsapp : null,
                'check_whatsapp' => $whatsapp !== '' ? 1 : 0,
            ]);

            $nombre = trim((string) ($solicitud['nombre'] ?? ''));
            if ($nombre !== '') {
                $stmt = $this->pdo->prepare(
                    'INSERT INTO user_info (user_auth_id, nombre, apodo, created_at, updated_at)
                     VALUES (:user_auth_id, :nombre, :apodo, NOW(), NOW())'
                );
                $stmt->execute([
                    'user_auth_id' => $userId,
                    'nombre' => $nombre,
                    'apodo' => $nombre,
                ]);
            }

            $this->pdo->commit();

            return [
                'ok' => true,
                'estado' => 'usuario_creado',
                'mensaje' => 'Usuario cliente creado correctamente.',
                'usuario' => ['id' => $userId, 'correo' => $correo, 'rol' => 'impulsa_cliente'],
            ];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return ['ok' => false, 'estado' => 'error_usuario', 'mensaje' => 'No se pudo crear el usuario: ' . $e->getMessage()];
        }
    }

    public function obtenerProyectoPorSolicitudExterna(int $solicitudId): ?array
    {
        return $this->obtenerProyectoPorFuente('landing_page_requests_external', $solicitudId);
    }

    public function obtenerProyectoPorSolicitudInterna(int $solicitudId): ?array
    {
        return $this->obtenerProyectoPorFuente('landing_page_request', $solicitudId);
    }

    public function crearProyectoDesdeSolicitudInterna(array $solicitud, int $managerUserId): array
    {
        $clienteUserId = (int) ($solicitud['user_auth_id'] ?? 0);
        if ($clienteUserId <= 0) {
            return ['ok' => false, 'estado' => 'proyecto_sin_usuario', 'mensaje' => 'La solicitud no tiene un usuario registrado valido asociado.'];
        }

        return $this->crearProyectoDesdeSolicitudBase(
            $solicitud,
            'landing_page_request',
            $clienteUserId,
            $managerUserId,
            trim((string) ($solicitud['nombre_emprendimiento'] ?? '')) ?: 'Pagina web Impulsa Emprende',
            $this->obtenerNombreSolicitanteInterno($solicitud) ?: 'Usuario registrado',
            strtolower(trim((string) ($solicitud['usuario_correo'] ?? ''))),
            trim((string) ($solicitud['telefono_contacto'] ?? '')) ?: null,
            trim((string) ($solicitud['descripcion'] ?? '')),
            $this->armarResumenAlcanceInterno($solicitud),
            'El proyecto fue creado desde la solicitud de Impulsa Emprende y quedo visible para el cliente.'
        );
    }

    private function obtenerProyectoPorFuente(string $sourceType, int $solicitudId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, project_name, client_user_id
             FROM projects
             WHERE source_type = :source_type AND source_id = :source_id
             LIMIT 1'
        );
        $stmt->execute([
            'source_type' => 'landing_page_requests_external',
            'source_id' => $solicitudId,
        ]);
        $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);

        return $proyecto ?: null;
    }

    public function crearProyectoDesdeSolicitudExterna(array $solicitud, int $clienteUserId, int $managerUserId): array
    {
        return $this->crearProyectoDesdeSolicitudBase(
            $solicitud,
            'landing_page_requests_external',
            $clienteUserId,
            $managerUserId,
            trim((string) ($solicitud['nombre_proyecto'] ?? '')) ?: 'Pagina web Impulsa',
            trim((string) ($solicitud['nombre'] ?? '')) ?: 'Cliente externo',
            strtolower(trim((string) ($solicitud['correo'] ?? ''))),
            trim((string) ($solicitud['whatsapp'] ?? '')) ?: null,
            trim((string) ($solicitud['q3_objetivo'] ?? '')),
            $this->armarResumenAlcance($solicitud),
            'El proyecto fue creado desde la solicitud externa y quedo visible para el cliente.'
        );
    }

    private function crearProyectoDesdeSolicitudBase(
        array $solicitud,
        string $sourceType,
        int $clienteUserId,
        int $managerUserId,
        string $projectName,
        string $clientName,
        string $clientEmail,
        ?string $clientWhatsapp,
        string $summary,
        string $scopeSummary,
        string $mensajeActualizacion
    ): array
    {
        $solicitudId = (int) ($solicitud['id'] ?? 0);
        if ($solicitudId <= 0) {
            return ['ok' => false, 'estado' => 'solicitud_invalida', 'mensaje' => 'Solicitud invalida.'];
        }

        $proyectoExistente = $this->obtenerProyectoPorFuente($sourceType, $solicitudId);
        if ($proyectoExistente) {
            return [
                'ok' => false,
                'estado' => 'proyecto_existente',
                'mensaje' => 'Ya existe un proyecto creado para esta solicitud.',
                'proyecto' => $proyectoExistente,
            ];
        }

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO projects
                    (source_type, source_id, project_name, project_type, client_user_id, manager_user_id,
                     client_name, client_email, client_whatsapp, summary, scope_summary, status, priority,
                     progress_percent, client_visible, created_at, updated_at)
                 VALUES
                    (:source_type, :source_id, :project_name, 'website', :client_user_id, :manager_user_id,
                     :client_name, :client_email, :client_whatsapp, :summary, :scope_summary, 'planned', 'medium',
                     0, 1, NOW(), NOW())"
            );
            $stmt->execute([
                'source_type' => $sourceType,
                'source_id' => $solicitudId,
                'project_name' => $projectName,
                'client_user_id' => $clienteUserId,
                'manager_user_id' => $managerUserId,
                'client_name' => $clientName,
                'client_email' => $clientEmail,
                'client_whatsapp' => $clientWhatsapp,
                'summary' => $summary,
                'scope_summary' => $scopeSummary,
            ]);

            $projectId = (int) $this->pdo->lastInsertId();
            $this->crearFasesIniciales($projectId);
            $this->crearEntregablesIniciales($projectId);

            $stmt = $this->pdo->prepare(
                'INSERT INTO project_updates (project_id, phase_id, created_by, title, message, progress_delta, visible_to_client, created_at)
                 VALUES (:project_id, NULL, :created_by, :title, :message, NULL, 1, NOW())'
            );
            $stmt->execute([
                'project_id' => $projectId,
                'created_by' => $managerUserId,
                'title' => 'Proyecto creado',
                'message' => $mensajeActualizacion,
            ]);

            $this->pdo->commit();

            return [
                'ok' => true,
                'estado' => 'proyecto_creado',
                'mensaje' => 'Proyecto creado correctamente.',
                'proyecto' => ['id' => $projectId],
            ];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return ['ok' => false, 'estado' => 'error_proyecto', 'mensaje' => 'No se pudo crear el proyecto: ' . $e->getMessage()];
        }
    }

    private function armarResumenAlcance(array $solicitud): string
    {
        return trim(implode("\n\n", array_filter([
            'Actividad: ' . trim((string) ($solicitud['q2_actividad'] ?? '')),
            'Publico: ' . trim((string) ($solicitud['q4_publico'] ?? '')),
            'Accion principal: ' . trim((string) ($solicitud['q5_accion_principal'] ?? '')),
            'Secciones: ' . trim((string) ($solicitud['q8_secciones'] ?? '')),
            'Dominio y hosting: ' . trim((string) ($solicitud['q16_dominio_hosting'] ?? '')),
            'Requerimientos adicionales: ' . trim((string) ($solicitud['q18_requerimientos_adicionales'] ?? '')),
        ], static fn (string $valor): bool => trim(substr($valor, (int) strpos($valor, ':') + 1)) !== '')));
    }

    private function armarResumenAlcanceInterno(array $solicitud): string
    {
        return trim(implode("\n\n", array_filter([
            'Descripcion: ' . trim((string) ($solicitud['descripcion'] ?? '')),
            'Rubro: ' . trim((string) ($solicitud['rubro_categoria'] ?? '')),
            'Subrubro: ' . trim((string) ($solicitud['rubro_subcategoria'] ?? '')),
            'Fecha de inicio: ' . trim((string) ($solicitud['fecha_inicio'] ?? '')),
            'Cantidad de colaboradores: ' . trim((string) ($solicitud['cantidad_colaboradores'] ?? '')),
            'Dominio registrado: ' . ((int) ($solicitud['dominio_registrado'] ?? 0) === 1 ? 'Si' : 'No'),
            'Hosting propio: ' . ((int) ($solicitud['hosting_propio'] ?? 0) === 1 ? 'Si' : 'No'),
        ], static fn (string $valor): bool => trim(substr($valor, (int) strpos($valor, ':') + 1)) !== '')));
    }

    private function obtenerNombreSolicitanteInterno(array $solicitud): string
    {
        $nombreCompleto = trim((string) ($solicitud['usuario_nombre'] ?? '') . ' ' . (string) ($solicitud['usuario_apellido'] ?? ''));
        if ($nombreCompleto !== '') {
            return $nombreCompleto;
        }

        return trim((string) ($solicitud['usuario_apodo'] ?? ''));
    }

    private function crearFasesIniciales(int $projectId): void
    {
        $fases = [
            ['Relevamiento y alcance', 'Revision de objetivos, contenido, referencias y criterios de exito.', 1],
            ['Diseno y contenidos', 'Definicion visual, estructura de secciones y textos principales.', 2],
            ['Desarrollo y publicacion', 'Construccion, pruebas, ajustes finales y puesta online.', 3],
        ];

        $stmt = $this->pdo->prepare(
            "INSERT INTO project_phases (project_id, title, description, duration_days, phase_order, status, created_at, updated_at)
             VALUES (:project_id, :title, :description, NULL, :phase_order, 'pending', NOW(), NOW())"
        );

        foreach ($fases as [$title, $description, $order]) {
            $stmt->execute([
                'project_id' => $projectId,
                'title' => $title,
                'description' => $description,
                'phase_order' => $order,
            ]);
        }
    }

    private function crearEntregablesIniciales(int $projectId): void
    {
        $entregables = [
            ['Documento de alcance', 'Resumen inicial de objetivos, secciones y materiales necesarios.', 'document'],
            ['Propuesta visual', 'Base visual y criterio de marca para la pagina web.', 'design'],
            ['Pagina web publicada', 'Entrega de la pagina construida y publicada.', 'deployment'],
        ];

        $stmt = $this->pdo->prepare(
            "INSERT INTO project_deliverables
                (project_id, phase_id, title, description, deliverable_type, status, client_visible, created_at, updated_at)
             VALUES
                (:project_id, NULL, :title, :description, :deliverable_type, 'pending', 1, NOW(), NOW())"
        );

        foreach ($entregables as [$title, $description, $type]) {
            $stmt->execute([
                'project_id' => $projectId,
                'title' => $title,
                'description' => $description,
                'deliverable_type' => $type,
            ]);
        }
    }
}
