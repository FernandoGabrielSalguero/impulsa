<?php

declare(strict_types=1);

class AdminIngresosModel
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Registra un ingreso de usuario al sistema.
     */
    public function registrarIngreso(int $userAuthId, string $nombreUsuario, string $rol): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO user_ingresos (user_auth_id, nombre_usuario, rol, fecha_ingreso, hora_ingreso, created_at)
             VALUES (:user_auth_id, :nombre_usuario, :rol, CURDATE(), CURTIME(), NOW())'
        );

        return $stmt->execute([
            'user_auth_id' => $userAuthId,
            'nombre_usuario' => $nombreUsuario,
            'rol' => $rol,
        ]);
    }

    /**
     * Obtiene el listado completo de ingresos ordenados por fecha descendente.
     */
    public function obtenerIngresos(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ui.id,
                    ui.user_auth_id,
                    ui.nombre_usuario,
                    ui.rol,
                    ui.fecha_ingreso,
                    ui.hora_ingreso,
                    ui.created_at
             FROM user_ingresos ui
             ORDER BY ui.fecha_ingreso DESC, ui.hora_ingreso DESC, ui.id DESC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene el nombre visible de un usuario a partir de su user_auth_id.
     */
    public function obtenerNombreUsuario(int $userAuthId): string
    {
        $stmt = $this->pdo->prepare(
            'SELECT COALESCE(NULLIF(CONCAT_WS(" ", ui.nombre, ui.apellido), ""), NULLIF(ui.apodo, ""), ua.correo) AS nombre_visible
             FROM user_auth ua
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             WHERE ua.id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $userAuthId]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (string) ($resultado['nombre_visible'] ?? 'Usuario');
    }
}
