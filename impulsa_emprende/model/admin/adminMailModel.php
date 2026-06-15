<?php

declare(strict_types=1);

class AdminMailModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function contarCorreos(array $filtros): int
    {
        $params = [];
        $where = $this->construirWhere($filtros, $params);
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM correos_log cl' . $where);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    public function obtenerCorreos(array $filtros, int $limit, int $offset): array
    {
        $params = [];
        $where = $this->construirWhere($filtros, $params);
        $stmt = $this->pdo->prepare(
            'SELECT cl.id,
                    cl.user_auth_id,
                    cl.correo,
                    cl.asunto,
                    cl.template,
                    cl.mensaje_html,
                    cl.mensaje_text,
                    cl.estado,
                    cl.error,
                    cl.meta,
                    cl.created_at,
                    ua.correo AS usuario_correo,
                    ui.nombre AS usuario_nombre,
                    ui.apellido AS usuario_apellido,
                    ui.apodo AS usuario_apodo
             FROM correos_log cl
             LEFT JOIN user_auth ua ON ua.id = cl.user_auth_id
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id' .
             $where .
             ' ORDER BY cl.created_at DESC, cl.id DESC
               LIMIT :limit OFFSET :offset'
        );

        foreach ($params as $clave => $valor) {
            $stmt->bindValue($clave, $valor, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerCorreoPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM correos_log WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $correo = $stmt->fetch(PDO::FETCH_ASSOC);

        return $correo ?: null;
    }

    private function construirWhere(array $filtros, array &$params): string
    {
        $condiciones = [];
        $correo = trim((string) ($filtros['correo'] ?? ''));
        if ($correo !== '') {
            $condiciones[] = 'cl.correo LIKE :correo';
            $params[':correo'] = '%' . $correo . '%';
        }

        $asunto = trim((string) ($filtros['asunto'] ?? ''));
        if ($asunto !== '') {
            $condiciones[] = 'cl.asunto LIKE :asunto';
            $params[':asunto'] = '%' . $asunto . '%';
        }

        return $condiciones === [] ? '' : ' WHERE ' . implode(' AND ', $condiciones);
    }
}
