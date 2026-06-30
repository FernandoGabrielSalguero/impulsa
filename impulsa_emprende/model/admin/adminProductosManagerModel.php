<?php

declare(strict_types=1);

require_once __DIR__ . '/../../partials/api_product/api_productoModel.php';

final class AdminProductosManagerModel
{
    private ApiProductoModel $apiProductoModel;

    public function __construct(private PDO $pdo)
    {
        $this->apiProductoModel = new ApiProductoModel($pdo);
    }

    public function obtenerResumen(?int $integrationId = null): array
    {
        $sql = "
            SELECT
                COUNT(*) AS total_productos,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS total_activos,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) AS total_borrador,
                SUM(CASE WHEN featured = 1 THEN 1 ELSE 0 END) AS total_destacados
            FROM api_products
        ";
        $params = [];

        if ($integrationId !== null) {
            $sql .= ' WHERE api_integration_id = :integration_id';
            $params[':integration_id'] = $integrationId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerIntegracionesAsignables(): array
    {
        $sql = "
            SELECT
                ai.id,
                ai.project_name,
                ai.allowed_domain,
                ai.public_key,
                ai.status,
                ai.user_auth_id,
                ua.correo AS owner_auth_correo,
                uc.correo AS owner_contacto_correo,
                ui.nombre AS owner_nombre,
                ui.apellido AS owner_apellido,
                ui.apodo AS owner_apodo,
                COUNT(ap.id) AS total_productos
            FROM api_integrations ai
            INNER JOIN user_auth ua ON ua.id = ai.user_auth_id
            LEFT JOIN user_contacto uc ON uc.user_auth_id = ua.id
            LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
            LEFT JOIN api_products ap ON ap.api_integration_id = ai.id
            GROUP BY
                ai.id,
                ai.project_name,
                ai.allowed_domain,
                ai.public_key,
                ai.status,
                ai.user_auth_id,
                ua.correo,
                uc.correo,
                ui.nombre,
                ui.apellido,
                ui.apodo
            ORDER BY ai.project_name ASC, ai.id ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return array_map(fn (array $row): array => $this->mapearIntegracion($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function obtenerIntegracionPorId(int $integrationId): ?array
    {
        foreach ($this->obtenerIntegracionesAsignables() as $integration) {
            if ((int) ($integration['id'] ?? 0) === $integrationId) {
                return $integration;
            }
        }

        return null;
    }

    public function obtenerProductosPorIntegracion(int $integrationId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT
                ap.*,
                ai.project_name,
                ai.allowed_domain,
                ai.user_auth_id,
                ua.correo AS owner_auth_correo,
                uc.correo AS owner_contacto_correo,
                ui.nombre AS owner_nombre,
                ui.apellido AS owner_apellido,
                ui.apodo AS owner_apodo
             FROM api_products ap
             INNER JOIN api_integrations ai ON ai.id = ap.api_integration_id
             INNER JOIN user_auth ua ON ua.id = ai.user_auth_id
             LEFT JOIN user_contacto uc ON uc.user_auth_id = ua.id
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             WHERE ap.api_integration_id = :integration_id
             ORDER BY ap.sort_order ASC, ap.updated_at DESC, ap.id DESC"
        );
        $stmt->execute([':integration_id' => $integrationId]);

        return array_map(fn (array $row): array => $this->mapearProducto($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function obtenerProductoPorId(int $productId): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT
                ap.*,
                ai.project_name,
                ai.allowed_domain,
                ai.user_auth_id,
                ua.correo AS owner_auth_correo,
                uc.correo AS owner_contacto_correo,
                ui.nombre AS owner_nombre,
                ui.apellido AS owner_apellido,
                ui.apodo AS owner_apodo
             FROM api_products ap
             INNER JOIN api_integrations ai ON ai.id = ap.api_integration_id
             INNER JOIN user_auth ua ON ua.id = ai.user_auth_id
             LEFT JOIN user_contacto uc ON uc.user_auth_id = ua.id
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             WHERE ap.id = :id
             LIMIT 1"
        );
        $stmt->execute([':id' => $productId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($row) ? $this->mapearProducto($row) : null;
    }

    public function guardarProducto(?int $productId, int $integrationId, array $payload, array $files): int
    {
        $integration = $this->obtenerIntegracionPorId($integrationId);
        if ($integration === null) {
            throw new RuntimeException('La integracion seleccionada no existe o no tiene usuario asignado.');
        }

        $createdByUserId = (int) ($integration['user_auth_id'] ?? 0);
        if ($createdByUserId <= 0) {
            throw new RuntimeException('La integracion elegida no tiene un usuario propietario asignado.');
        }

        if ($productId !== null) {
            $existing = $this->obtenerProductoPorId($productId);
            if ($existing === null) {
                throw new RuntimeException('El producto que intentas editar no existe.');
            }
        }

        return $this->apiProductoModel->guardarItemApi(
            $integrationId,
            $productId,
            $createdByUserId,
            $payload,
            $files
        );
    }

    public function actualizarEstadoProducto(int $productId, string $status): void
    {
        $product = $this->obtenerProductoPorId($productId);
        if ($product === null) {
            throw new RuntimeException('El producto seleccionado no existe.');
        }

        $this->apiProductoModel->actualizarEstadoApi(
            (int) ($product['api_integration_id'] ?? 0),
            $productId,
            $status
        );
    }

    private function mapearIntegracion(array $row): array
    {
        return [
            'id' => (int) ($row['id'] ?? 0),
            'project_name' => (string) ($row['project_name'] ?? ''),
            'allowed_domain' => (string) ($row['allowed_domain'] ?? ''),
            'public_key' => (string) ($row['public_key'] ?? ''),
            'status' => (string) ($row['status'] ?? 'inactive'),
            'user_auth_id' => isset($row['user_auth_id']) ? (int) $row['user_auth_id'] : null,
            'owner_name' => $this->resolverNombreUsuario($row),
            'owner_email' => $this->resolverCorreoUsuario($row),
            'total_productos' => (int) ($row['total_productos'] ?? 0),
        ];
    }

    private function mapearProducto(array $row): array
    {
        return [
            'id' => (int) ($row['id'] ?? 0),
            'api_integration_id' => (int) ($row['api_integration_id'] ?? 0),
            'project_name' => (string) ($row['project_name'] ?? ''),
            'allowed_domain' => (string) ($row['allowed_domain'] ?? ''),
            'user_auth_id' => isset($row['user_auth_id']) ? (int) $row['user_auth_id'] : null,
            'owner_name' => $this->resolverNombreUsuario($row),
            'owner_email' => $this->resolverCorreoUsuario($row),
            'title' => (string) ($row['title'] ?? ''),
            'slug' => (string) ($row['slug'] ?? ''),
            'sku' => (string) ($row['sku'] ?? ''),
            'short_description' => (string) ($row['short_description'] ?? ''),
            'description_html' => (string) ($row['description_html'] ?? '<p></p>'),
            'main_image_path' => (string) ($row['main_image_path'] ?? ''),
            'thumbnail_path' => (string) ($row['thumbnail_path'] ?? ''),
            'attachment_path' => (string) ($row['attachment_path'] ?? ''),
            'category' => (string) ($row['category'] ?? ''),
            'subcategory' => (string) ($row['subcategory'] ?? ''),
            'price' => $row['price'] !== null ? (string) $row['price'] : '',
            'compare_at_price' => $row['compare_at_price'] !== null ? (string) $row['compare_at_price'] : '',
            'currency' => (string) ($row['currency'] ?? 'ARS'),
            'stock_quantity' => $row['stock_quantity'] !== null ? (string) $row['stock_quantity'] : '',
            'availability' => (string) ($row['availability'] ?? 'on_request'),
            'status' => (string) ($row['status'] ?? 'draft'),
            'featured' => (int) ($row['featured'] ?? 0) === 1,
            'sort_order' => (int) ($row['sort_order'] ?? 1),
            'metadata_json' => (string) ($row['metadata_json'] ?? ''),
            'created_by_user_id' => isset($row['created_by_user_id']) ? (int) $row['created_by_user_id'] : null,
            'created_at' => (string) ($row['created_at'] ?? ''),
            'updated_at' => (string) ($row['updated_at'] ?? ''),
        ];
    }

    private function resolverNombreUsuario(array $row): string
    {
        $nombre = trim((string) ($row['owner_nombre'] ?? '') . ' ' . (string) ($row['owner_apellido'] ?? ''));
        $apodo = trim((string) ($row['owner_apodo'] ?? ''));
        $correo = $this->resolverCorreoUsuario($row);

        if ($nombre !== '') {
            return $nombre;
        }

        if ($apodo !== '') {
            return $apodo;
        }

        return $correo !== '' ? $correo : 'Usuario sin nombre';
    }

    private function resolverCorreoUsuario(array $row): string
    {
        $contacto = trim((string) ($row['owner_contacto_correo'] ?? ''));
        if ($contacto !== '') {
            return $contacto;
        }

        return trim((string) ($row['owner_auth_correo'] ?? ''));
    }
}
