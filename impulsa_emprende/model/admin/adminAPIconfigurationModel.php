<?php

declare(strict_types=1);

class AdminAPIconfigurationModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function sincronizarPropietariosIntegraciones(): void
    {
        $sql = "
            UPDATE api_integrations ai
            LEFT JOIN (
                SELECT project_name, MIN(client_user_id) AS client_user_id
                FROM projects
                WHERE client_user_id IS NOT NULL
                  AND TRIM(COALESCE(project_name, '')) <> ''
                GROUP BY project_name
            ) p ON p.project_name = ai.project_name
            LEFT JOIN (
                SELECT nombre_emprendimiento, MIN(user_auth_id) AS user_auth_id
                FROM landing_page_request
                WHERE user_auth_id IS NOT NULL
                  AND TRIM(COALESCE(nombre_emprendimiento, '')) <> ''
                GROUP BY nombre_emprendimiento
            ) lpr ON lpr.nombre_emprendimiento = ai.project_name
            SET ai.user_auth_id = COALESCE(p.client_user_id, lpr.user_auth_id)
            WHERE ai.user_auth_id IS NULL
              AND COALESCE(p.client_user_id, lpr.user_auth_id) IS NOT NULL
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    public function obtenerOpcionesProyectoSitio(): array
    {
        $sql = "
            SELECT nombre, origen, fecha_referencia
            FROM (
                SELECT DISTINCT
                    TRIM(p.project_name) AS nombre,
                    'Proyecto' AS origen,
                    p.updated_at AS fecha_referencia
                FROM projects p
                WHERE TRIM(COALESCE(p.project_name, '')) <> ''

                UNION ALL

                SELECT DISTINCT
                    TRIM(lpr.nombre_emprendimiento) AS nombre,
                    'Solicitud interna' AS origen,
                    lpr.updated_at AS fecha_referencia
                FROM landing_page_request lpr
                WHERE TRIM(COALESCE(lpr.nombre_emprendimiento, '')) <> ''

                UNION ALL

                SELECT DISTINCT
                    TRIM(lpre.nombre_proyecto) AS nombre,
                    'Solicitud externa' AS origen,
                    lpre.created_at AS fecha_referencia
                FROM landing_page_requests_external lpre
                WHERE TRIM(COALESCE(lpre.nombre_proyecto, '')) <> ''
            ) opciones
            ORDER BY nombre ASC, fecha_referencia DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $opciones = [];
        $vistos = [];

        foreach ($filas as $fila) {
            $nombre = trim((string) ($fila['nombre'] ?? ''));

            if ($nombre === '') {
                continue;
            }

            $clave = mb_strtolower($nombre, 'UTF-8');

            if (isset($vistos[$clave])) {
                continue;
            }

            $vistos[$clave] = true;
            $opciones[] = [
                'nombre' => $nombre,
                'origen' => (string) ($fila['origen'] ?? ''),
            ];
        }

        return $opciones;
    }

    public function obtenerIntegraciones(): array
    {
        $sql = 'SELECT ai.*,
                       ua.correo AS owner_auth_correo,
                       uc.correo AS owner_contacto_correo,
                       ui.nombre AS owner_nombre,
                       ui.apellido AS owner_apellido,
                       ui.apodo AS owner_apodo,
                       COALESCE(v.total_visits, 0) AS total_visits,
                       COALESCE(f.total_contacts, 0) AS total_contacts
                FROM api_integrations ai
                LEFT JOIN user_auth ua ON ua.id = ai.user_auth_id
                LEFT JOIN user_contacto uc ON uc.user_auth_id = ua.id
                LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
                LEFT JOIN (
                    SELECT api_integration_id, COUNT(*) AS total_visits
                    FROM visit_user_page
                    GROUP BY api_integration_id
                ) v ON v.api_integration_id = ai.id
                LEFT JOIN (
                    SELECT api_integration_id, COUNT(*) AS total_contacts
                    FROM forms_clients_contact
                    GROUP BY api_integration_id
                ) f ON f.api_integration_id = ai.id
                ORDER BY ai.updated_at DESC, ai.id DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuariosPropietarios(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ua.id,
                    ua.correo AS auth_correo,
                    ua.rol,
                    uc.correo AS contacto_correo,
                    ui.nombre,
                    ui.apellido,
                    ui.apodo
             FROM user_auth ua
             LEFT JOIN user_contacto uc ON uc.user_auth_id = ua.id
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             ORDER BY COALESCE(NULLIF(TRIM(ui.nombre), \'\'), NULLIF(TRIM(ui.apodo), \'\'), ua.correo) ASC, ua.id ASC'
        );
        $stmt->execute();

        return array_map(function (array $usuario): array {
            $nombre = trim((string) ($usuario['nombre'] ?? ''));
            $apellido = trim((string) ($usuario['apellido'] ?? ''));
            $apodo = trim((string) ($usuario['apodo'] ?? ''));
            $correoAuth = trim((string) ($usuario['auth_correo'] ?? ''));
            $correoContacto = trim((string) ($usuario['contacto_correo'] ?? ''));
            $nombreVisible = trim($nombre . ' ' . $apellido);

            if ($nombreVisible === '') {
                $nombreVisible = $apodo !== '' ? $apodo : $correoAuth;
            }

            return [
                'id' => (int) ($usuario['id'] ?? 0),
                'rol' => (string) ($usuario['rol'] ?? ''),
                'auth_correo' => $correoAuth,
                'contacto_correo' => $correoContacto,
                'display_name' => $nombreVisible,
                'display_email' => $correoContacto !== '' ? $correoContacto : $correoAuth,
            ];
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function resolverUsuarioPropietarioPorProyecto(string $projectName): ?int
    {
        $projectName = trim($projectName);
        if ($projectName === '') {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT MIN(client_user_id)
             FROM projects
             WHERE client_user_id IS NOT NULL
               AND project_name = :project_name'
        );
        $stmt->execute([':project_name' => $projectName]);
        $projectOwner = $stmt->fetchColumn();

        if ($projectOwner !== false && $projectOwner !== null) {
            return (int) $projectOwner;
        }

        $stmt = $this->pdo->prepare(
            'SELECT MIN(user_auth_id)
             FROM landing_page_request
             WHERE user_auth_id IS NOT NULL
               AND nombre_emprendimiento = :project_name'
        );
        $stmt->execute([':project_name' => $projectName]);
        $requestOwner = $stmt->fetchColumn();

        if ($requestOwner !== false && $requestOwner !== null) {
            return (int) $requestOwner;
        }

        return null;
    }

    public function crearIntegracion(string $projectName, string $allowedDomain, string $publicKey, string $secretKeyHash, ?int $userAuthId): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO api_integrations
             (project_name, allowed_domain, public_key, secret_key_hash, status, user_auth_id)
             VALUES
             (:project_name, :allowed_domain, :public_key, :secret_key_hash, :status, :user_auth_id)'
        );
        $stmt->execute([
            ':project_name' => $projectName,
            ':allowed_domain' => $allowedDomain,
            ':public_key' => $publicKey,
            ':secret_key_hash' => $secretKeyHash,
            ':status' => 'active',
            ':user_auth_id' => $userAuthId,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function actualizarIntegracion(int $id, string $projectName, string $allowedDomain, ?int $userAuthId): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE api_integrations
             SET project_name = :project_name,
                 allowed_domain = :allowed_domain,
                 user_auth_id = :user_auth_id
             WHERE id = :id'
        );
        $stmt->execute([
            ':id' => $id,
            ':project_name' => $projectName,
            ':allowed_domain' => $allowedDomain,
            ':user_auth_id' => $userAuthId,
        ]);
    }

    public function actualizarEstado(int $id, string $status): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE api_integrations
             SET status = :status
             WHERE id = :id'
        );
        $stmt->execute([
            ':id' => $id,
            ':status' => $status,
        ]);
    }

    public function actualizarPublicKey(int $id, string $publicKey): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE api_integrations
             SET public_key = :public_key
             WHERE id = :id'
        );
        $stmt->execute([
            ':id' => $id,
            ':public_key' => $publicKey,
        ]);
    }

    public function actualizarSecretKeyHash(int $id, string $secretKeyHash): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE api_integrations
             SET secret_key_hash = :secret_key_hash
             WHERE id = :id'
        );
        $stmt->execute([
            ':id' => $id,
            ':secret_key_hash' => $secretKeyHash,
        ]);
    }

    public function existePublicKey(string $publicKey, ?int $excludeId = null): bool
    {
        $sql = 'SELECT id FROM api_integrations WHERE public_key = :public_key';
        $params = [':public_key' => $publicKey];

        if ($excludeId !== null) {
            $sql .= ' AND id <> :exclude_id';
            $params[':exclude_id'] = $excludeId;
        }

        $sql .= ' LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (bool) $stmt->fetchColumn();
    }

    public function obtenerIntegracionPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ai.*,
                    ua.correo AS owner_auth_correo,
                    uc.correo AS owner_contacto_correo,
                    ui.nombre AS owner_nombre,
                    ui.apellido AS owner_apellido,
                    ui.apodo AS owner_apodo
             FROM api_integrations
             ai
             LEFT JOIN user_auth ua ON ua.id = ai.user_auth_id
             LEFT JOIN user_contacto uc ON uc.user_auth_id = ua.id
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             WHERE ai.id = :id
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($fila) ? $fila : null;
    }
}
