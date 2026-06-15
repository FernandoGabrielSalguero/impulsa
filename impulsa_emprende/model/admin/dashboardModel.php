<?php

class DashboardModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerUsuariosPorRol(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT rol, COUNT(*) AS cantidad
             FROM user_auth
             GROUP BY rol
             ORDER BY rol ASC'
        );
        $stmt->execute();

        $conteos = [];
        foreach (($stmt->fetchAll(PDO::FETCH_ASSOC) ?: []) as $fila) {
            $conteos[(string) ($fila['rol'] ?? '')] = (int) ($fila['cantidad'] ?? 0);
        }

        $roles = [
            'impulsa_administrador',
            'impulsa_colaborador',
            'impulsa_emprendedor',
            'impulsa_usuario',
            'impulsa_marketing',
            'impulsa_cliente',
        ];

        return array_map(static fn (string $rol): array => [
            'rol' => $rol,
            'cantidad' => $conteos[$rol] ?? 0,
        ], $roles);
    }
}
