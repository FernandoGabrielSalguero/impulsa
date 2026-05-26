<?php

class UserPaginaWebModel
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

    private function estaCompleto(string $tabla, int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT completado FROM {$tabla} WHERE user_auth_id = :user_id LIMIT 1"
        );
        $stmt->execute(['user_id' => $userId]);

        return (int) ($stmt->fetchColumn() ?: 0) === 1;
    }
}
