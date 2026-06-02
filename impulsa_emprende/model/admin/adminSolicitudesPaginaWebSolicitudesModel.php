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
                    res.nombre AS rubro_subcategoria
             FROM landing_page_request lpr
             INNER JOIN user_auth ua ON ua.id = lpr.user_auth_id
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             LEFT JOIN rubro_emprendedor_categoria rec ON rec.id = lpr.rubro_categoria_id
             LEFT JOIN rubro_emprendedor_subcategoria res ON res.id = lpr.rubro_subcategoria_id
             ORDER BY lpr.created_at DESC, lpr.id DESC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSolicitudesExternas(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id,
                    nombre,
                    nombre_proyecto,
                    correo,
                    whatsapp,
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
                    form_source,
                    ip_address,
                    user_agent,
                    created_at
             FROM landing_page_requests_external
             ORDER BY created_at DESC, id DESC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
