<?php

class EmprendedorCimientosModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerDrawerData(int $userId): array
    {
        if ($userId <= 0) {
            return [];
        }

        return [
            'usuario' => $this->obtenerUsuario($userId),
            'cimientos' => [
                'mision' => $this->obtenerModulo(
                    'emprendedor_mision',
                    'mision_estructura',
                    'Mision',
                    'Definicion estrategica del negocio.',
                    $userId
                ),
                'vision' => $this->obtenerModulo(
                    'emprendedor_vision',
                    'vision_estructura',
                    'Vision',
                    'Proyeccion y transformacion buscada a futuro.',
                    $userId
                ),
                'buyer_persona' => $this->obtenerModulo(
                    'emprendedor_buyer_persona',
                    'buyer_persona_estructura',
                    'Buyer persona',
                    'Perfil del cliente ideal para orientar comunicacion y oferta.',
                    $userId
                ),
            ],
        ];
    }

    private function obtenerUsuario(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ua.id,
                    ua.correo,
                    ua.rol,
                    ui.nombre,
                    ui.apellido,
                    ui.apodo,
                    lpr.nombre_emprendimiento
             FROM user_auth ua
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             LEFT JOIN landing_page_request lpr ON lpr.user_auth_id = ua.id
             WHERE ua.id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        $nombreCompleto = trim((string) ($usuario['nombre'] ?? '') . ' ' . (string) ($usuario['apellido'] ?? ''));
        $apodo = trim((string) ($usuario['apodo'] ?? ''));

        $usuario['nombre_visible'] = $nombreCompleto !== ''
            ? $nombreCompleto
            : ($apodo !== '' ? $apodo : (string) ($usuario['correo'] ?? 'Usuario'));

        return $usuario;
    }

    private function obtenerModulo(
        string $tabla,
        string $campoResultado,
        string $titulo,
        string $descripcion,
        int $userId
    ): array {
        $sql = sprintf(
            'SELECT %s AS resultado, completado
             FROM %s
             WHERE user_auth_id = :user_id
             LIMIT 1',
            $campoResultado,
            $tabla
        );

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        $resultado = trim((string) ($fila['resultado'] ?? ''));
        $completado = (int) ($fila['completado'] ?? 0) === 1;
        $tieneAvance = $fila !== null && $resultado !== '';

        if ($completado) {
            $estado = 'finalizado';
            $estadoLabel = 'Finalizado';
            $estadoClase = 'im-chip--exito';
        } elseif ($tieneAvance) {
            $estado = 'en_progreso';
            $estadoLabel = 'En progreso';
            $estadoClase = 'im-chip--alerta';
        } else {
            $estado = 'pendiente';
            $estadoLabel = 'Pendiente';
            $estadoClase = 'im-chip';
        }

        return [
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'estado' => $estado,
            'estado_label' => $estadoLabel,
            'estado_clase' => $estadoClase,
            'completado' => $completado ? 1 : 0,
            'contenido' => $completado ? $resultado : '',
        ];
    }
}
