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

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
