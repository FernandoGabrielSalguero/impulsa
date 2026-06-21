<?php

declare(strict_types=1);

class EmprendedorMetricasModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerContexto(int $userId): array
    {
        return [
            'usuario' => $this->obtenerUsuario($userId),
            'integraciones' => $this->obtenerIntegracionesEmprendedor($userId),
        ];
    }

    private function obtenerUsuario(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ua.id, ua.correo AS auth_correo, ua.rol, ua.created_at,
                    ui.nombre, ui.apellido, ui.apodo, ui.avatar_path,
                    uc.correo AS contacto_correo, uc.whatsapp
             FROM user_auth ua
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             LEFT JOIN user_contacto uc ON uc.user_auth_id = ua.id
             WHERE ua.id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerIntegracionesEmprendedor(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT DISTINCT ai.id, ai.project_name, ai.allowed_domain, ai.public_key, ai.status,
                    p.id AS project_id, p.project_name AS project_name_source
             FROM projects p
             INNER JOIN api_integrations ai ON ai.project_name = p.project_name
             WHERE p.client_user_id = :user_id
               AND p.client_visible = 1
             ORDER BY ai.project_name ASC, ai.id ASC'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
