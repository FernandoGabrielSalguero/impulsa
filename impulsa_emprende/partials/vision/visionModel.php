<?php

class VisionModel
{
    private array $campos = [
        'conversion_futura',
        'lugar_mercado',
        'impacto_generado',
    ];

    public function __construct(private PDO $pdo)
    {
    }

    public function obtener(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT conversion_futura, lugar_mercado, impacto_generado, vision_estructura, completado
             FROM emprendedor_vision
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

        $estructuraPost = $this->limpiar($data['vision_estructura'] ?? '');
        $datos['vision_estructura'] = $estructuraPost !== '' ? $estructuraPost : $this->crearEstructura($datos);
        $datos['completado'] = $this->estaCompleto($datos) ? 1 : 0;

        $stmt = $this->pdo->prepare(
            'INSERT INTO emprendedor_vision
                (user_auth_id, conversion_futura, lugar_mercado, impacto_generado, vision_estructura, completado, created_at, updated_at)
             VALUES
                (:user_id, :conversion_futura, :lugar_mercado, :impacto_generado, :vision_estructura, :completado, NOW(), NOW())
             ON DUPLICATE KEY UPDATE
                conversion_futura = VALUES(conversion_futura),
                lugar_mercado = VALUES(lugar_mercado),
                impacto_generado = VALUES(impacto_generado),
                vision_estructura = VALUES(vision_estructura),
                completado = VALUES(completado),
                updated_at = NOW()'
        );
        $stmt->execute([
            'user_id' => $userId,
            'conversion_futura' => $datos['conversion_futura'],
            'lugar_mercado' => $datos['lugar_mercado'],
            'impacto_generado' => $datos['impacto_generado'],
            'vision_estructura' => $datos['vision_estructura'],
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

    private function datosVacios(): array
    {
        return [
            'conversion_futura' => '',
            'lugar_mercado' => '',
            'impacto_generado' => '',
            'vision_estructura' => '',
            'completado' => 0,
        ];
    }

    private function crearEstructura(array $datos): string
    {
        return trim(sprintf(
            'En el futuro queremos convertirnos en %s, ocupar %s y generar %s.',
            $datos['conversion_futura'],
            $datos['lugar_mercado'],
            $datos['impacto_generado']
        ));
    }

    private function limpiar(mixed $valor): string
    {
        return trim((string) $valor);
    }
}
