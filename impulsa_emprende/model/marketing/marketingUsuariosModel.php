<?php

declare(strict_types=1);

class MarketingUsuariosModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerUsuariosExternos(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT ua.id,
                    ua.correo AS correo_login,
                    ua.rol,
                    ui.nombre,
                    ui.apellido,
                    ui.apodo,
                    uc.correo AS correo_contacto,
                    uc.whatsapp,
                    uc.permison_correo,
                    uc.permison_whatsapp,
                    (
                        SELECT p.project_name
                        FROM projects p
                        WHERE p.client_user_id = ua.id
                        ORDER BY p.updated_at DESC, p.id DESC
                        LIMIT 1
                    ) AS project_name
             FROM user_auth ua
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             LEFT JOIN user_contacto uc ON uc.user_auth_id = ua.id
             WHERE ua.usuario_tipo = 'externo'
             ORDER BY COALESCE(ui.nombre, ui.apodo, ua.correo) ASC, ua.id ASC"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
