<?php

class BuyerModel
{
    private array $campos = [
        'cliente_ideal',
        'edad_etapa_vida',
        'ocupacion_realidad_diaria',
        'problema_necesidad',
        'preocupacion_frustracion',
        'objetivo_mejora',
        'motivacion_busqueda',
        'freno_dudas',
        'criterio_eleccion',
        'busqueda_informacion',
        'decision_compra',
        'motivo_eleccion',
    ];

    public function __construct(private PDO $pdo)
    {
    }

    public function obtener(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT cliente_ideal, edad_etapa_vida, ocupacion_realidad_diaria, problema_necesidad,
                    preocupacion_frustracion, objetivo_mejora, motivacion_busqueda, freno_dudas,
                    criterio_eleccion, busqueda_informacion, decision_compra, motivo_eleccion,
                    buyer_persona_estructura, completado
             FROM emprendedor_buyer_persona
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

        $datos['buyer_persona_estructura'] = json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $datos['completado'] = $this->estaCompleto($datos) ? 1 : 0;

        $stmt = $this->pdo->prepare(
            'INSERT INTO emprendedor_buyer_persona
                (user_auth_id, cliente_ideal, edad_etapa_vida, ocupacion_realidad_diaria, problema_necesidad,
                 preocupacion_frustracion, objetivo_mejora, motivacion_busqueda, freno_dudas,
                 criterio_eleccion, busqueda_informacion, decision_compra, motivo_eleccion,
                 buyer_persona_estructura, completado, created_at, updated_at)
             VALUES
                (:user_id, :cliente_ideal, :edad_etapa_vida, :ocupacion_realidad_diaria, :problema_necesidad,
                 :preocupacion_frustracion, :objetivo_mejora, :motivacion_busqueda, :freno_dudas,
                 :criterio_eleccion, :busqueda_informacion, :decision_compra, :motivo_eleccion,
                 :buyer_persona_estructura, :completado, NOW(), NOW())
             ON DUPLICATE KEY UPDATE
                cliente_ideal = VALUES(cliente_ideal),
                edad_etapa_vida = VALUES(edad_etapa_vida),
                ocupacion_realidad_diaria = VALUES(ocupacion_realidad_diaria),
                problema_necesidad = VALUES(problema_necesidad),
                preocupacion_frustracion = VALUES(preocupacion_frustracion),
                objetivo_mejora = VALUES(objetivo_mejora),
                motivacion_busqueda = VALUES(motivacion_busqueda),
                freno_dudas = VALUES(freno_dudas),
                criterio_eleccion = VALUES(criterio_eleccion),
                busqueda_informacion = VALUES(busqueda_informacion),
                decision_compra = VALUES(decision_compra),
                motivo_eleccion = VALUES(motivo_eleccion),
                buyer_persona_estructura = VALUES(buyer_persona_estructura),
                completado = VALUES(completado),
                updated_at = NOW()'
        );
        $stmt->execute([
            'user_id' => $userId,
            'cliente_ideal' => $datos['cliente_ideal'],
            'edad_etapa_vida' => $datos['edad_etapa_vida'],
            'ocupacion_realidad_diaria' => $datos['ocupacion_realidad_diaria'],
            'problema_necesidad' => $datos['problema_necesidad'],
            'preocupacion_frustracion' => $datos['preocupacion_frustracion'],
            'objetivo_mejora' => $datos['objetivo_mejora'],
            'motivacion_busqueda' => $datos['motivacion_busqueda'],
            'freno_dudas' => $datos['freno_dudas'],
            'criterio_eleccion' => $datos['criterio_eleccion'],
            'busqueda_informacion' => $datos['busqueda_informacion'],
            'decision_compra' => $datos['decision_compra'],
            'motivo_eleccion' => $datos['motivo_eleccion'],
            'buyer_persona_estructura' => $datos['buyer_persona_estructura'],
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
        $datos = array_fill_keys($this->campos, '');
        $datos['buyer_persona_estructura'] = '';
        $datos['completado'] = 0;

        return $datos;
    }

    private function limpiar(mixed $valor): string
    {
        return trim((string) $valor);
    }
}
