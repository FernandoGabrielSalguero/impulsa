<?php

declare(strict_types=1);

class AdminAPIconfigurationModel
{
    public function __construct(private PDO $pdo)
    {
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
                       COALESCE(v.total_visits, 0) AS total_visits,
                       COALESCE(f.total_contacts, 0) AS total_contacts
                FROM api_integrations ai
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

    public function crearIntegracion(string $projectName, string $allowedDomain, string $publicKey, string $secretKeyHash): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO api_integrations
             (project_name, allowed_domain, public_key, secret_key_hash, status)
             VALUES
             (:project_name, :allowed_domain, :public_key, :secret_key_hash, :status)'
        );
        $stmt->execute([
            ':project_name' => $projectName,
            ':allowed_domain' => $allowedDomain,
            ':public_key' => $publicKey,
            ':secret_key_hash' => $secretKeyHash,
            ':status' => 'active',
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function actualizarIntegracion(int $id, string $projectName, string $allowedDomain): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE api_integrations
             SET project_name = :project_name,
                 allowed_domain = :allowed_domain
             WHERE id = :id'
        );
        $stmt->execute([
            ':id' => $id,
            ':project_name' => $projectName,
            ':allowed_domain' => $allowedDomain,
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
            'SELECT *
             FROM api_integrations
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($fila) ? $fila : null;
    }
}
