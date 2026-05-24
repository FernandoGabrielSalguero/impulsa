<?php

class MisionModel
{
    private array $campos = [
        'a_quien_ayudo',
        'que_problema_resuelvo',
        'como_lo_resuelvo',
    ];

    public function __construct(private PDO $pdo)
    {
    }

    public function obtener(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a_quien_ayudo, que_problema_resuelvo, como_lo_resuelvo, mision_estructura, completado
             FROM emprendedor_mision
             WHERE user_auth_id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: $this->datosVacios();
    }

    public function guardar(int $userId, array $data): array
    {
        $existentes = $this->obtener($userId);
        $datos = [];
        foreach ($this->campos as $campo) {
            $datos[$campo] = array_key_exists($campo, $data)
                ? $this->limpiar($data[$campo])
                : (string) ($existentes[$campo] ?? '');
        }

        $datos['mision_estructura'] = $this->crearEstructura($datos);
        $datos['completado'] = $this->estaCompleto($datos) ? 1 : 0;

        $stmt = $this->pdo->prepare(
            'INSERT INTO emprendedor_mision
                (user_auth_id, a_quien_ayudo, que_problema_resuelvo, como_lo_resuelvo, mision_estructura, completado, created_at, updated_at)
             VALUES
                (:user_id, :a_quien_ayudo, :que_problema_resuelvo, :como_lo_resuelvo, :mision_estructura, :completado, NOW(), NOW())
             ON DUPLICATE KEY UPDATE
                a_quien_ayudo = VALUES(a_quien_ayudo),
                que_problema_resuelvo = VALUES(que_problema_resuelvo),
                como_lo_resuelvo = VALUES(como_lo_resuelvo),
                mision_estructura = VALUES(mision_estructura),
                completado = VALUES(completado),
                updated_at = NOW()'
        );
        $stmt->execute([
            'user_id' => $userId,
            'a_quien_ayudo' => $datos['a_quien_ayudo'],
            'que_problema_resuelvo' => $datos['que_problema_resuelvo'],
            'como_lo_resuelvo' => $datos['como_lo_resuelvo'],
            'mision_estructura' => $datos['mision_estructura'],
            'completado' => $datos['completado'],
        ]);

        return $datos;
    }

    public function estaCompleto(array $datos): bool
    {
        foreach ($this->campos as $campo) {
            if (trim((string) ($datos[$campo] ?? '')) === '') {
                return false;
            }
        }

        return true;
    }

    public function campos(): array
    {
        return $this->campos;
    }

    private function datosVacios(): array
    {
        return [
            'a_quien_ayudo' => '',
            'que_problema_resuelvo' => '',
            'como_lo_resuelvo' => '',
            'mision_estructura' => '',
            'completado' => 0,
        ];
    }

    private function crearEstructura(array $datos): string
    {
        return trim(sprintf(
            'Ayudo a %s a resolver %s mediante %s.',
            $datos['a_quien_ayudo'],
            $datos['que_problema_resuelvo'],
            $datos['como_lo_resuelvo']
        ));
    }

    private function limpiar(mixed $valor): string
    {
        return trim((string) $valor);
    }
}
