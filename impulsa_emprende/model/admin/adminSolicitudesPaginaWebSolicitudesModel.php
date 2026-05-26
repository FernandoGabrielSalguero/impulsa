<?php

class AdminSolicitudesPaginaWebSolicitudesModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerSolicitudes(): array
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
