<?php

class PaginaWebViewModel
{
    private array $camposRequeridos = [
        'nombre_emprendimiento',
        'fecha_inicio',
        'descripcion',
        'cantidad_colaboradores',
        'nombre_fundador',
        'telefono_contacto',
    ];

    private array $camposBooleanos = [
        'dominio_registrado',
        'hosting_propio',
        'vende_productos',
        'vende_servicios',
        'ya_factura',
        'espacio_fisico',
    ];

    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerSolicitud(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, user_auth_id, nombre_emprendimiento, fecha_inicio, descripcion,
                    dominio_registrado, hosting_propio, cantidad_colaboradores, nombre_fundador,
                    vende_productos, vende_servicios, ya_factura, espacio_fisico,
                    rubro_categoria_id, rubro_subcategoria_id, pais, provincia, localidad,
                    calle, numero, telefono_contacto, completado, created_at, updated_at
             FROM landing_page_request
             WHERE user_auth_id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerCategorias(): array
    {
        return $this->pdo
            ->query('SELECT id, nombre FROM rubro_emprendedor_categoria ORDER BY nombre')
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSubcategorias(): array
    {
        return $this->pdo
            ->query('SELECT id, nombre FROM rubro_emprendedor_subcategoria ORDER BY nombre')
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardar(int $userId, array $data): array
    {
        if ($this->obtenerSolicitud($userId)) {
            throw new RuntimeException('Ya existe una solicitud de pagina web para este usuario.');
        }

        $datos = [
            'nombre_emprendimiento' => $this->limpiar($data['nombre_emprendimiento'] ?? ''),
            'fecha_inicio' => $this->limpiar($data['fecha_inicio'] ?? ''),
            'descripcion' => $this->limpiar($data['descripcion'] ?? ''),
            'cantidad_colaboradores' => max(1, (int) ($data['cantidad_colaboradores'] ?? 1)),
            'nombre_fundador' => $this->limpiar($data['nombre_fundador'] ?? ''),
            'rubro_categoria_id' => $this->enteroOpcional($data['rubro_categoria_id'] ?? null),
            'rubro_subcategoria_id' => $this->enteroOpcional($data['rubro_subcategoria_id'] ?? null),
            'pais' => $this->limpiarOpcional($data['pais'] ?? ''),
            'provincia' => $this->limpiarOpcional($data['provincia'] ?? ''),
            'localidad' => $this->limpiarOpcional($data['localidad'] ?? ''),
            'calle' => $this->limpiarOpcional($data['calle'] ?? ''),
            'numero' => $this->limpiarOpcional($data['numero'] ?? ''),
            'telefono_contacto' => $this->limpiar($data['telefono_contacto'] ?? ''),
        ];

        foreach ($this->camposBooleanos as $campo) {
            $datos[$campo] = isset($data[$campo]) ? 1 : 0;
        }

        foreach ($this->camposRequeridos as $campo) {
            if ((string) ($datos[$campo] ?? '') === '') {
                throw new InvalidArgumentException('Faltan campos obligatorios.');
            }
        }

        $datos['completado'] = 1;

        $stmt = $this->pdo->prepare(
            'INSERT INTO landing_page_request
                (user_auth_id, nombre_emprendimiento, fecha_inicio, descripcion,
                 dominio_registrado, hosting_propio, cantidad_colaboradores, nombre_fundador,
                 vende_productos, vende_servicios, ya_factura, espacio_fisico,
                 rubro_categoria_id, rubro_subcategoria_id, pais, provincia, localidad,
                 calle, numero, telefono_contacto, completado, created_at, updated_at)
             VALUES
                (:user_id, :nombre_emprendimiento, :fecha_inicio, :descripcion,
                 :dominio_registrado, :hosting_propio, :cantidad_colaboradores, :nombre_fundador,
                 :vende_productos, :vende_servicios, :ya_factura, :espacio_fisico,
                 :rubro_categoria_id, :rubro_subcategoria_id, :pais, :provincia, :localidad,
                 :calle, :numero, :telefono_contacto, :completado, NOW(), NOW())'
        );
        $stmt->execute([
            'user_id' => $userId,
            'nombre_emprendimiento' => $datos['nombre_emprendimiento'],
            'fecha_inicio' => $datos['fecha_inicio'],
            'descripcion' => $datos['descripcion'],
            'dominio_registrado' => $datos['dominio_registrado'],
            'hosting_propio' => $datos['hosting_propio'],
            'cantidad_colaboradores' => $datos['cantidad_colaboradores'],
            'nombre_fundador' => $datos['nombre_fundador'],
            'vende_productos' => $datos['vende_productos'],
            'vende_servicios' => $datos['vende_servicios'],
            'ya_factura' => $datos['ya_factura'],
            'espacio_fisico' => $datos['espacio_fisico'],
            'rubro_categoria_id' => $datos['rubro_categoria_id'],
            'rubro_subcategoria_id' => $datos['rubro_subcategoria_id'],
            'pais' => $datos['pais'],
            'provincia' => $datos['provincia'],
            'localidad' => $datos['localidad'],
            'calle' => $datos['calle'],
            'numero' => $datos['numero'],
            'telefono_contacto' => $datos['telefono_contacto'],
            'completado' => $datos['completado'],
        ]);

        return $this->obtenerSolicitud($userId);
    }

    public function camposUsados(): array
    {
        return [
            'nombre_emprendimiento',
            'fecha_inicio',
            'descripcion',
            'dominio_registrado',
            'hosting_propio',
            'cantidad_colaboradores',
            'nombre_fundador',
            'vende_productos',
            'vende_servicios',
            'ya_factura',
            'espacio_fisico',
            'rubro_categoria_id',
            'rubro_subcategoria_id',
            'pais',
            'provincia',
            'localidad',
            'calle',
            'numero',
            'telefono_contacto',
        ];
    }

    private function limpiar(mixed $valor): string
    {
        return trim((string) $valor);
    }

    private function limpiarOpcional(mixed $valor): ?string
    {
        $valor = $this->limpiar($valor);

        return $valor === '' ? null : $valor;
    }

    private function enteroOpcional(mixed $valor): ?int
    {
        $valor = (int) $valor;

        return $valor > 0 ? $valor : null;
    }
}
