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
                    ua.email_verified_at,
                    ua.created_at,
                    ui.nombre,
                    ui.apellido,
                    ui.apodo,
                    uc.correo AS correo_contacto,
                    uc.whatsapp,
                    uc.permison_correo,
                    uc.permison_whatsapp,
                    lpr.nombre_emprendimiento,
                    lpr.descripcion AS landing_descripcion,
                    lpr.fecha_inicio,
                    lpr.vende_productos,
                    lpr.vende_servicios,
                    lpr.ya_factura,
                    lpr.completado AS landing_completado,
                    CASE WHEN em.user_auth_id IS NULL THEN 0 ELSE em.completado END AS has_mision,
                    CASE WHEN ev.user_auth_id IS NULL THEN 0 ELSE ev.completado END AS has_vision,
                    CASE WHEN ebp.user_auth_id IS NULL THEN 0 ELSE ebp.completado END AS has_buyer_persona,
                    (
                        SELECT p.project_name
                        FROM projects p
                        WHERE p.client_user_id = ua.id
                        ORDER BY p.updated_at DESC, p.id DESC
                        LIMIT 1
                    ) AS project_name,
                    (
                        SELECT p.project_type
                        FROM projects p
                        WHERE p.client_user_id = ua.id
                        ORDER BY p.updated_at DESC, p.id DESC
                        LIMIT 1
                    ) AS project_type,
                    (
                        SELECT p.status
                        FROM projects p
                        WHERE p.client_user_id = ua.id
                        ORDER BY p.updated_at DESC, p.id DESC
                        LIMIT 1
                    ) AS project_status
             FROM user_auth ua
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             LEFT JOIN user_contacto uc ON uc.user_auth_id = ua.id
             LEFT JOIN landing_page_request lpr ON lpr.user_auth_id = ua.id
             LEFT JOIN emprendedor_mision em ON em.user_auth_id = ua.id
             LEFT JOIN emprendedor_vision ev ON ev.user_auth_id = ua.id
             LEFT JOIN emprendedor_buyer_persona ebp ON ebp.user_auth_id = ua.id
             WHERE ua.usuario_tipo = 'externo'
             ORDER BY COALESCE(ui.nombre, ui.apodo, ua.correo) ASC, ua.id ASC"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
