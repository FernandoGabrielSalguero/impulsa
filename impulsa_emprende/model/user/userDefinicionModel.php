<?php

class UserDefinicionModel
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
}
