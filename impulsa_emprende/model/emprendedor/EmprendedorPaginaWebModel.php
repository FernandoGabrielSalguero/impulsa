<?php

class EmprendedorPaginaWebModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerUsuario(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ua.id, ua.correo, ua.rol,
                    ui.nombre, ui.apellido, ui.apodo, ui.avatar_path
             FROM user_auth ua
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             WHERE ua.id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerEstadoDefinicion(int $userId): array
    {
        return [
            'mision' => $this->estaCompleto('emprendedor_mision', $userId),
            'vision' => $this->estaCompleto('emprendedor_vision', $userId),
            'buyer' => $this->estaCompleto('emprendedor_buyer_persona', $userId),
        ];
    }

    public function tieneDefinicionCompleta(int $userId): bool
    {
        $estado = $this->obtenerEstadoDefinicion($userId);

        return $estado['mision'] && $estado['vision'] && $estado['buyer'];
    }

    public function obtenerDominioAutorizado(int $userId): ?string
    {
        $stmt = $this->pdo->prepare(
            "SELECT ai.allowed_domain
             FROM projects p
             INNER JOIN api_integrations ai ON ai.project_name = p.project_name
             WHERE p.client_user_id = :user_id
               AND p.client_visible = 1
               AND p.project_type IN ('website', 'landing_page')
               AND ai.status = 'active'
               AND ai.allowed_domain <> ''
             ORDER BY p.updated_at DESC, ai.id DESC
             LIMIT 1"
        );
        $stmt->execute(['user_id' => $userId]);

        $dominio = $stmt->fetchColumn();
        if (!is_string($dominio)) {
            return null;
        }

        $dominio = trim($dominio);

        return $dominio !== '' ? $dominio : null;
    }

    private function estaCompleto(string $tabla, int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT completado FROM {$tabla} WHERE user_auth_id = :user_id LIMIT 1"
        );
        $stmt->execute(['user_id' => $userId]);

        return (int) ($stmt->fetchColumn() ?: 0) === 1;
    }
}
