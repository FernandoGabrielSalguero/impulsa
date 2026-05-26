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
            ->query(
                'SELECT rs.id, rs.nombre, rr.categoria_id
                 FROM rubro_emprendedor_subcategoria rs
                 INNER JOIN rubro_emprendedor_relaciones rr ON rr.subcategoria_id = rs.id
                 ORDER BY rs.nombre'
            )
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUbicaciones(): array
    {
        $path = __DIR__ . '/../../assets/provincias/localidades.json';
        if (!is_file($path)) {
            return [];
        }

        $json = file_get_contents($path);
        if ($json === false) {
            return [];
        }

        $ubicaciones = json_decode($json, true);
        if (!is_array($ubicaciones)) {
            return [];
        }

        return $this->normalizarUbicaciones($ubicaciones);
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
        $datos['espacio_fisico'] = $this->normalizarBooleanoSelect($data['espacio_fisico'] ?? null);

        foreach ($this->camposRequeridos as $campo) {
            if ((string) ($datos[$campo] ?? '') === '') {
                throw new InvalidArgumentException('Faltan campos obligatorios.');
            }
        }
        $this->validarFecha($datos['fecha_inicio']);
        $this->validarRubro($datos['rubro_categoria_id']);
        $this->validarRubroSubcategoria($datos['rubro_categoria_id'], $datos['rubro_subcategoria_id']);
        $this->validarUbicacion($datos['pais'], $datos['provincia'], $datos['localidad']);
        $this->validarEspacioFisico($datos);

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

    private function normalizarBooleanoSelect(mixed $valor): int
    {
        if ($valor === '1' || $valor === 1) {
            return 1;
        }

        if ($valor === '0' || $valor === 0) {
            return 0;
        }

        throw new InvalidArgumentException('Indica si tenes espacio fisico.');
    }

    private function validarFecha(string $fecha): void
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $fecha);
        if (!$date || $date->format('Y-m-d') !== $fecha) {
            throw new InvalidArgumentException('La fecha de inicio no es valida.');
        }
    }

    private function validarRubroSubcategoria(?int $categoriaId, ?int $subcategoriaId): void
    {
        if ($subcategoriaId === null) {
            return;
        }

        if ($categoriaId === null) {
            throw new InvalidArgumentException('Selecciona un rubro antes de elegir subcategoria.');
        }

        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*)
             FROM rubro_emprendedor_relaciones
             WHERE categoria_id = :categoria_id
               AND subcategoria_id = :subcategoria_id'
        );
        $stmt->execute([
            'categoria_id' => $categoriaId,
            'subcategoria_id' => $subcategoriaId,
        ]);

        if ((int) $stmt->fetchColumn() !== 1) {
            throw new InvalidArgumentException('La subcategoria no pertenece al rubro seleccionado.');
        }
    }

    private function validarRubro(?int $categoriaId): void
    {
        if ($categoriaId === null) {
            return;
        }

        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM rubro_emprendedor_categoria WHERE id = :categoria_id'
        );
        $stmt->execute(['categoria_id' => $categoriaId]);

        if ((int) $stmt->fetchColumn() !== 1) {
            throw new InvalidArgumentException('El rubro seleccionado no es valido.');
        }
    }

    private function validarUbicacion(?string $pais, ?string $provincia, ?string $localidad): void
    {
        $ubicaciones = $this->obtenerUbicaciones();

        if ($pais === null) {
            if ($provincia !== null || $localidad !== null) {
                throw new InvalidArgumentException('Selecciona un pais antes de elegir provincia o localidad.');
            }
            return;
        }

        if (!isset($ubicaciones[$pais])) {
            throw new InvalidArgumentException('El pais seleccionado no esta disponible.');
        }

        if ($provincia === null) {
            if ($localidad !== null) {
                throw new InvalidArgumentException('Selecciona una provincia antes de elegir localidad.');
            }
            return;
        }

        if (!isset($ubicaciones[$pais][$provincia])) {
            throw new InvalidArgumentException('La provincia no pertenece al pais seleccionado.');
        }

        if ($localidad !== null && !in_array($localidad, $ubicaciones[$pais][$provincia], true)) {
            throw new InvalidArgumentException('La localidad no pertenece a la provincia seleccionada.');
        }
    }

    private function validarEspacioFisico(array &$datos): void
    {
        if ((int) $datos['espacio_fisico'] === 0) {
            $datos['calle'] = null;
            $datos['numero'] = null;
            return;
        }

        if ($datos['calle'] === null || $datos['numero'] === null) {
            throw new InvalidArgumentException('Completa calle y numero si tenes espacio fisico.');
        }
    }

    private function normalizarUbicaciones(array $ubicaciones): array
    {
        $normalizadas = [];

        foreach ($ubicaciones as $pais => $provincias) {
            if (!is_string($pais) || !is_array($provincias)) {
                continue;
            }

            $pais = trim($pais);
            if ($pais === '') {
                continue;
            }

            $normalizadas[$pais] = [];
            foreach ($provincias as $provincia => $localidades) {
                if (!is_string($provincia) || !is_array($localidades)) {
                    continue;
                }

                $provincia = trim($provincia);
                if ($provincia === '') {
                    continue;
                }

                $normalizadas[$pais][$provincia] = array_values(array_filter(array_map(
                    static fn (mixed $localidad): string => trim((string) $localidad),
                    $localidades
                ), static fn (string $localidad): bool => $localidad !== ''));
            }
        }

        return $normalizadas;
    }
}
