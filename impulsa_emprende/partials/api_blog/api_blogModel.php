<?php

declare(strict_types=1);

final class ApiBlogModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function verificarConexion(): bool
    {
        $stmt = $this->pdo->query('SELECT 1');

        return $stmt !== false;
    }

    public function obtenerArchivoEditable(int $userId, int $itemId, string $column): ?array
    {
        return null;
    }
}
