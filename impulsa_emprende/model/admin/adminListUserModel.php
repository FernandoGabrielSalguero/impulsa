<?php

class AdminListUserModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerUsuarios(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ua.id,
                    ua.correo AS correo_login,
                    ua.rol,
                    ua.email_verified_at,
                    ua.created_at,
                    ua.updated_at,
                    ui.nombre,
                    ui.apellido,
                    ui.apodo,
                    ui.avatar_path,
                    uc.correo AS correo_contacto,
                    uc.whatsapp,
                    up.page AS pagina_inicio
             FROM user_auth ua
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             LEFT JOIN user_contacto uc ON uc.user_auth_id = ua.id
             LEFT JOIN user_params up ON up.user_auth_id = ua.id
             ORDER BY ua.created_at DESC, ua.id DESC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
