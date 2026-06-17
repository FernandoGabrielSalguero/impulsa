<?php

declare(strict_types=1);

final class ApiIntegrationAccessModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerIntegracionesAccesibles(int $userId): array
    {
        $sql = "
            SELECT DISTINCT
                ai.id,
                ai.project_name,
                ai.allowed_domain,
                ai.public_key,
                ai.status AS integration_status,
                CASE
                    WHEN p.id IS NOT NULL THEN 'project'
                    WHEN lpr.id IS NOT NULL THEN 'landing_request'
                    ELSE 'unknown'
                END AS source_type
            FROM api_integrations ai
            LEFT JOIN projects p
                ON p.project_name = ai.project_name
               AND p.client_user_id = :user_id
               AND p.client_visible = 1
            LEFT JOIN landing_page_request lpr
                ON lpr.nombre_emprendimiento = ai.project_name
               AND lpr.user_auth_id = :user_id
            WHERE p.id IS NOT NULL OR lpr.id IS NOT NULL
            ORDER BY ai.project_name ASC, ai.id ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerIntegracionAccesible(int $userId, int $integrationId): ?array
    {
        foreach ($this->obtenerIntegracionesAccesibles($userId) as $integracion) {
            if ((int) ($integracion['id'] ?? 0) === $integrationId) {
                return $integracion;
            }
        }

        return null;
    }
}

abstract class ApiContentSharedModel
{
    private const BLOG_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    private const BLOG_IMAGE_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    private const BLOG_ATTACHMENT_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'zip'];
    private const BLOG_ATTACHMENT_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/csv',
        'text/plain',
        'application/zip',
        'application/x-zip-compressed',
    ];
    private const PRODUCT_ATTACHMENT_EXTENSIONS = ['pdf', 'doc', 'docx', 'txt'];
    private const PRODUCT_ATTACHMENT_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'text/plain',
    ];

    protected PDO $pdo;
    private ApiIntegrationAccessModel $integrationAccessModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->integrationAccessModel = new ApiIntegrationAccessModel($pdo);
    }

    abstract protected function obtenerConfiguracionModulo(): array;

    public function obtenerIntegracionesAccesibles(int $userId): array
    {
        return $this->integrationAccessModel->obtenerIntegracionesAccesibles($userId);
    }

    public function obtenerIntegracionAccesible(int $userId, int $integrationId): ?array
    {
        return $this->integrationAccessModel->obtenerIntegracionAccesible($userId, $integrationId);
    }

    public function obtenerItemsPorUsuario(int $userId, ?int $integrationId = null): array
    {
        $config = $this->obtenerConfiguracionModulo();
        $sql = 'SELECT c.*, ai.project_name, ai.allowed_domain
                FROM ' . $config['table'] . ' c
                INNER JOIN api_integrations ai ON ai.id = c.api_integration_id
                LEFT JOIN projects p
                    ON p.project_name = ai.project_name
                   AND p.client_user_id = :user_id
                   AND p.client_visible = 1
                LEFT JOIN landing_page_request lpr
                    ON lpr.nombre_emprendimiento = ai.project_name
                   AND lpr.user_auth_id = :user_id
                WHERE (p.id IS NOT NULL OR lpr.id IS NOT NULL)';
        $params = [':user_id' => $userId];

        if ($integrationId !== null) {
            $sql .= ' AND c.api_integration_id = :integration_id';
            $params[':integration_id'] = $integrationId;
        }

        $sql .= $config['uses_publication_date']
            ? ' ORDER BY c.sort_order ASC, COALESCE(c.publication_date, c.created_at) DESC, c.id DESC'
            : ' ORDER BY c.sort_order ASC, c.created_at DESC, c.id DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn (array $item): array => $this->mapearRegistroParaVista($item), $items);
    }

    public function obtenerItemEditable(int $userId, int $itemId): ?array
    {
        $config = $this->obtenerConfiguracionModulo();
        $sql = 'SELECT c.*, ai.project_name, ai.allowed_domain
                FROM ' . $config['table'] . ' c
                INNER JOIN api_integrations ai ON ai.id = c.api_integration_id
                LEFT JOIN projects p
                    ON p.project_name = ai.project_name
                   AND p.client_user_id = :user_id
                   AND p.client_visible = 1
                LEFT JOIN landing_page_request lpr
                    ON lpr.nombre_emprendimiento = ai.project_name
                   AND lpr.user_auth_id = :user_id
                WHERE c.id = :item_id
                  AND (p.id IS NOT NULL OR lpr.id IS NOT NULL)
                LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':item_id' => $itemId,
        ]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($item) ? $this->mapearRegistroParaVista($item) : null;
    }

    public function guardarItem(int $userId, ?int $itemId, int $integrationId, array $payload, array $files): int
    {
        $integracion = $this->obtenerIntegracionAccesible($userId, $integrationId);

        if ($integracion === null) {
            throw new RuntimeException('La integracion seleccionada no pertenece a tu cuenta.');
        }

        $existente = $itemId !== null ? $this->obtenerItemEditable($userId, $itemId) : null;
        if ($itemId !== null && $existente === null) {
            throw new RuntimeException('El registro que intentas editar no existe o no pertenece a tu cuenta.');
        }

        $normalizado = $this->normalizarPayload($payload, $integrationId, $existente);
        $archivos = $this->resolverArchivos($files, $existente);
        $config = $this->obtenerConfiguracionModulo();

        $columnas = array_merge(
            [
                'api_integration_id' => $integrationId,
                'title' => $normalizado['title'],
                'slug' => $normalizado['slug'],
                'category' => $normalizado['category'],
                'subcategory' => $normalizado['subcategory'],
                'description_html' => $normalizado['description_html'],
                'status' => $normalizado['status'],
                'sort_order' => $normalizado['sort_order'],
                'metadata_json' => $normalizado['metadata_json'],
            ],
            $archivos,
            $this->obtenerCamposPersistentesNormalizados($normalizado, $userId)
        );

        if ($itemId === null) {
            $columnas['created_by_user_id'] = $userId;
            $this->asegurarSlugUnico($integrationId, $columnas['slug']);

            $campos = array_keys($columnas);
            $sql = 'INSERT INTO ' . $config['table'] . ' (' . implode(', ', $campos) . ')
                    VALUES (:' . implode(', :', $campos) . ')';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->prefijarParametros($columnas));

            return (int) $this->pdo->lastInsertId();
        }

        $this->asegurarSlugUnico($integrationId, $columnas['slug'], $itemId);
        $sets = [];
        foreach (array_keys($columnas) as $campo) {
            $sets[] = $campo . ' = :' . $campo;
        }

        $sql = 'UPDATE ' . $config['table'] . '
                SET ' . implode(', ', $sets) . ',
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id';
        $params = $this->prefijarParametros($columnas);
        $params[':id'] = $itemId;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $itemId;
    }

    public function guardarItemApi(int $integrationId, ?int $itemId, ?int $createdByUserId, array $payload, array $files): int
    {
        $existente = $itemId !== null ? $this->obtenerItemPorIntegracion($integrationId, $itemId) : null;
        if ($itemId !== null && $existente === null) {
            throw new RuntimeException('El registro indicado no existe dentro de esta integracion.', 404);
        }

        $normalizado = $this->normalizarPayload($payload, $integrationId, $existente);
        $archivos = $this->resolverArchivos($files, $existente);
        $config = $this->obtenerConfiguracionModulo();

        $columnas = array_merge(
            [
                'api_integration_id' => $integrationId,
                'title' => $normalizado['title'],
                'slug' => $normalizado['slug'],
                'category' => $normalizado['category'],
                'subcategory' => $normalizado['subcategory'],
                'description_html' => $normalizado['description_html'],
                'status' => $normalizado['status'],
                'sort_order' => $normalizado['sort_order'],
                'metadata_json' => $normalizado['metadata_json'],
            ],
            $archivos,
            $this->obtenerCamposPersistentesNormalizados($normalizado, (int) ($createdByUserId ?? 0))
        );

        if ($itemId === null) {
            $columnas['created_by_user_id'] = $createdByUserId;
            $this->asegurarSlugUnico($integrationId, $columnas['slug']);
            $campos = array_keys($columnas);
            $sql = 'INSERT INTO ' . $config['table'] . ' (' . implode(', ', $campos) . ')
                    VALUES (:' . implode(', :', $campos) . ')';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->prefijarParametros($columnas));

            return (int) $this->pdo->lastInsertId();
        }

        $this->asegurarSlugUnico($integrationId, $columnas['slug'], $itemId);
        $sets = [];
        foreach (array_keys($columnas) as $campo) {
            $sets[] = $campo . ' = :' . $campo;
        }

        $sql = 'UPDATE ' . $config['table'] . '
                SET ' . implode(', ', $sets) . ',
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
                  AND api_integration_id = :api_integration_id_guard';
        $params = $this->prefijarParametros($columnas);
        $params[':id'] = $itemId;
        $params[':api_integration_id_guard'] = $integrationId;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $itemId;
    }

    public function actualizarEstado(int $userId, int $itemId, string $status): void
    {
        $status = trim($status);
        if (!in_array($status, ['active', 'inactive', 'draft'], true)) {
            throw new RuntimeException('El estado indicado no es valido.');
        }

        $item = $this->obtenerItemEditable($userId, $itemId);
        if ($item === null) {
            throw new RuntimeException('El registro seleccionado no existe o no pertenece a tu cuenta.');
        }

        $config = $this->obtenerConfiguracionModulo();
        $stmt = $this->pdo->prepare(
            'UPDATE ' . $config['table'] . '
             SET status = :status,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );
        $stmt->execute([
            ':status' => $status,
            ':id' => $itemId,
        ]);
    }

    public function eliminarLogicamente(int $userId, int $itemId): void
    {
        $this->actualizarEstado($userId, $itemId, 'inactive');
    }

    public function actualizarEstadoApi(int $integrationId, int $itemId, string $status): void
    {
        $status = trim($status);
        if (!in_array($status, ['active', 'inactive', 'draft'], true)) {
            throw new RuntimeException('El estado indicado no es valido.', 422);
        }

        $item = $this->obtenerItemPorIntegracion($integrationId, $itemId);
        if ($item === null) {
            throw new RuntimeException('El registro seleccionado no existe dentro de esta integracion.', 404);
        }

        $config = $this->obtenerConfiguracionModulo();
        $stmt = $this->pdo->prepare(
            'UPDATE ' . $config['table'] . '
             SET status = :status,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :id
               AND api_integration_id = :integration_id'
        );
        $stmt->execute([
            ':status' => $status,
            ':id' => $itemId,
            ':integration_id' => $integrationId,
        ]);
    }

    public function eliminarLogicamenteApi(int $integrationId, int $itemId): void
    {
        $this->actualizarEstadoApi($integrationId, $itemId, 'inactive');
    }

    public function obtenerListadoPublico(int $integrationId): array
    {
        $config = $this->obtenerConfiguracionModulo();
        $sql = 'SELECT *
                FROM ' . $config['table'] . '
                WHERE api_integration_id = :integration_id
                  AND status = :status';

        if ($config['uses_publication_date']) {
            $sql .= ' AND (publication_date IS NULL OR publication_date <= NOW())';
        }

        $sql .= ' ORDER BY sort_order ASC, COALESCE(publication_date, created_at) DESC, id DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':integration_id' => $integrationId,
            ':status' => 'active',
        ]);

        return array_map(
            fn (array $row): array => $this->mapearRegistroPublico($row),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function obtenerDetallePublico(int $integrationId, ?int $itemId, ?string $slug): ?array
    {
        $config = $this->obtenerConfiguracionModulo();
        $sql = 'SELECT *
                FROM ' . $config['table'] . '
                WHERE api_integration_id = :integration_id
                  AND status = :status';
        $params = [
            ':integration_id' => $integrationId,
            ':status' => 'active',
        ];

        if ($config['uses_publication_date']) {
            $sql .= ' AND (publication_date IS NULL OR publication_date <= NOW())';
        }

        if ($itemId !== null) {
            $sql .= ' AND id = :item_id';
            $params[':item_id'] = $itemId;
        } elseif ($slug !== null && $slug !== '') {
            $sql .= ' AND slug = :slug';
            $params[':slug'] = $slug;
        } else {
            throw new RuntimeException('Debes indicar id o slug para obtener el detalle.', 422);
        }

        $sql .= ' LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($row) ? $this->mapearRegistroPublico($row) : null;
    }

    public function normalizarAction(?string $action): string
    {
        $action = trim((string) $action);
        if ($action === '') {
            throw new RuntimeException('La accion es obligatoria.');
        }

        return $action;
    }

    protected function obtenerCamposPersistentesNormalizados(array $normalizado, int $userId): array
    {
        unset($userId);

        return $normalizado;
    }

    private function normalizarPayload(array $payload, int $integrationId, ?array $existente): array
    {
        $config = $this->obtenerConfiguracionModulo();
        $title = $this->normalizarTexto($payload['title'] ?? null, true, 180);
        $slugFuente = $this->normalizarTexto($payload['slug'] ?? null, false, 220) ?? $title;
        $slug = $this->slugify($slugFuente);

        if ($slug === '') {
            throw new RuntimeException('No se pudo generar un slug valido para este registro.');
        }

        $status = $this->normalizarEstado($payload['status'] ?? ($existente['status'] ?? 'draft'));
        $descriptionHtml = $this->sanitizarHtmlBasico($this->normalizarTexto($payload['description_html'] ?? null, true, null));
        if ($descriptionHtml === '') {
            throw new RuntimeException('La descripcion no puede quedar vacia.');
        }

        $sortOrder = $this->normalizarEntero($payload['sort_order'] ?? ($existente['sort_order'] ?? 1), 1, 999999);
        $metadata = $this->normalizarMetadata($payload['metadata_json'] ?? ($existente['metadata_json'] ?? null));

        $base = [
            'title' => $title,
            'slug' => $slug,
            'category' => $this->normalizarTexto($payload['category'] ?? ($existente['category'] ?? null), false, 120),
            'subcategory' => $this->normalizarTexto($payload['subcategory'] ?? ($existente['subcategory'] ?? null), false, 120),
            'description_html' => $descriptionHtml,
            'status' => $status,
            'sort_order' => $sortOrder,
            'metadata_json' => $metadata,
        ];

        if ($config['module'] === 'blog') {
            return array_merge($base, [
                'subtitle' => $this->normalizarTexto($payload['subtitle'] ?? ($existente['subtitle'] ?? null), false, 255),
                'author' => $this->normalizarTexto($payload['author'] ?? ($existente['author'] ?? null), false, 180),
                'bibliography' => $this->normalizarTexto($payload['bibliography'] ?? ($existente['bibliography'] ?? null), false, null),
                'excerpt' => $this->normalizarTexto($payload['excerpt'] ?? ($existente['excerpt'] ?? null), false, 300),
                'publication_date' => $this->normalizarFechaHora($payload['publication_date'] ?? ($existente['publication_date'] ?? null)),
            ]);
        }

        return array_merge($base, [
            'sku' => $this->normalizarTexto($payload['sku'] ?? ($existente['sku'] ?? null), false, 80),
            'short_description' => $this->normalizarTexto($payload['short_description'] ?? ($existente['short_description'] ?? null), false, 300),
            'price' => $this->normalizarDecimal($payload['price'] ?? ($existente['price'] ?? null)),
            'compare_at_price' => $this->normalizarDecimal($payload['compare_at_price'] ?? ($existente['compare_at_price'] ?? null)),
            'currency' => $this->normalizarTexto($payload['currency'] ?? ($existente['currency'] ?? 'ARS'), true, 8),
            'stock_quantity' => $this->normalizarEnteroNullable($payload['stock_quantity'] ?? ($existente['stock_quantity'] ?? null), 0),
            'availability' => $this->normalizarDisponibilidad($payload['availability'] ?? ($existente['availability'] ?? 'on_request')),
            'featured' => $this->normalizarBooleano($payload['featured'] ?? ($existente['featured'] ?? 0)) ? 1 : 0,
        ]);
    }

    private function resolverArchivos(array $files, ?array $existente): array
    {
        $config = $this->obtenerConfiguracionModulo();
        $archivos = [];

        foreach ($config['file_fields'] as $fieldName => $fieldConfig) {
            $current = (string) ($existente[$fieldConfig['column']] ?? '');
            $archivos[$fieldConfig['column']] = $this->guardarArchivoSubido(
                $files[$fieldName] ?? null,
                $fieldConfig,
                $current
            );
        }

        return $archivos;
    }

    private function guardarArchivoSubido(?array $file, array $fieldConfig, string $currentPath): ?string
    {
        $currentPath = trim($currentPath);

        if ($file === null || !isset($file['error'])) {
            return $currentPath !== '' ? $currentPath : null;
        }

        if ((int) $file['error'] === UPLOAD_ERR_NO_FILE) {
            return $currentPath !== '' ? $currentPath : null;
        }

        if ((int) $file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('No se pudo subir el archivo para ' . $fieldConfig['label'] . '.');
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        $originalName = (string) ($file['name'] ?? '');
        $fileSize = (int) ($file['size'] ?? 0);

        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new RuntimeException('El archivo recibido para ' . $fieldConfig['label'] . ' no es valido.');
        }

        if ($fileSize <= 0 || $fileSize > (int) $fieldConfig['max_bytes']) {
            throw new RuntimeException('El archivo ' . $fieldConfig['label'] . ' supera el tamano permitido.');
        }

        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($extension, $fieldConfig['extensions'], true)) {
            throw new RuntimeException('El archivo ' . $fieldConfig['label'] . ' tiene una extension no permitida.');
        }

        $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : false;
        $mimeType = $finfo ? (string) finfo_file($finfo, $tmpName) : '';
        if ($finfo) {
            finfo_close($finfo);
        }

        if ($mimeType === '' || !in_array($mimeType, $fieldConfig['mime_types'], true)) {
            throw new RuntimeException('El archivo ' . $fieldConfig['label'] . ' no tiene un MIME permitido.');
        }

        $uploadDir = $this->normalizarRutaDirectorio((string) ($fieldConfig['upload_dir'] ?? ''));
        if ($uploadDir === '') {
            throw new RuntimeException('No se definio la carpeta de destino para ' . $fieldConfig['label'] . '.');
        }

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
            throw new RuntimeException('No se pudo preparar la carpeta de uploads para ' . $fieldConfig['label'] . '.');
        }

        @chmod($uploadDir, 0775);

        $fileName = $this->generarNombreArchivoSeguro(
            (string) ($fieldConfig['prefix'] ?? 'archivo'),
            pathinfo($originalName, PATHINFO_FILENAME),
            $extension
        );
        $destination = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

        if (!$this->moverArchivoSubido($tmpName, $destination)) {
            throw new RuntimeException('No se pudo guardar el archivo ' . $fieldConfig['label'] . ' en ' . $uploadDir . '.');
        }

        return rtrim($fieldConfig['public_path'], '/') . '/' . $fileName;
    }

    private function asegurarSlugUnico(int $integrationId, string $slug, ?int $excludeId = null): void
    {
        $config = $this->obtenerConfiguracionModulo();
        $sql = 'SELECT id
                FROM ' . $config['table'] . '
                WHERE api_integration_id = :integration_id
                  AND slug = :slug';
        $params = [
            ':integration_id' => $integrationId,
            ':slug' => $slug,
        ];

        if ($excludeId !== null) {
            $sql .= ' AND id <> :exclude_id';
            $params[':exclude_id'] = $excludeId;
        }

        $sql .= ' LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        if ($stmt->fetchColumn()) {
            throw new RuntimeException('Ya existe otro registro con el mismo slug dentro de esta integracion.');
        }
    }

    private function mapearRegistroParaVista(array $row): array
    {
        $mapped = $row;

        foreach ($this->obtenerConfiguracionModulo()['path_columns'] as $column) {
            $mapped[$column . '_url'] = $this->resolverUrlPublica((string) ($row[$column] ?? ''));
        }

        return $mapped;
    }

    private function mapearRegistroPublico(array $row): array
    {
        $data = [
            'id' => (int) ($row['id'] ?? 0),
            'api_integration_id' => (int) ($row['api_integration_id'] ?? 0),
            'title' => (string) ($row['title'] ?? ''),
            'slug' => (string) ($row['slug'] ?? ''),
            'category' => $row['category'] !== null ? (string) $row['category'] : null,
            'subcategory' => $row['subcategory'] !== null ? (string) $row['subcategory'] : null,
            'description_html' => (string) ($row['description_html'] ?? ''),
            'status' => (string) ($row['status'] ?? ''),
            'sort_order' => (int) ($row['sort_order'] ?? 1),
            'created_at' => $row['created_at'] !== null ? (string) $row['created_at'] : null,
            'updated_at' => $row['updated_at'] !== null ? (string) $row['updated_at'] : null,
        ];

        if (($this->obtenerConfiguracionModulo()['module'] ?? '') === 'blog') {
            $data['subtitle'] = $row['subtitle'] !== null ? (string) $row['subtitle'] : null;
            $data['author'] = $row['author'] !== null ? (string) $row['author'] : null;
            $data['bibliography'] = $row['bibliography'] !== null ? (string) $row['bibliography'] : null;
            $data['excerpt'] = $row['excerpt'] !== null ? (string) $row['excerpt'] : null;
            $data['publication_date'] = $row['publication_date'] !== null ? (string) $row['publication_date'] : null;
        } else {
            $data['sku'] = $row['sku'] !== null ? (string) $row['sku'] : null;
            $data['short_description'] = $row['short_description'] !== null ? (string) $row['short_description'] : null;
            $data['price'] = $row['price'] !== null ? (float) $row['price'] : null;
            $data['compare_at_price'] = $row['compare_at_price'] !== null ? (float) $row['compare_at_price'] : null;
            $data['currency'] = (string) ($row['currency'] ?? 'ARS');
            $data['stock_quantity'] = $row['stock_quantity'] !== null ? (int) $row['stock_quantity'] : null;
            $data['availability'] = (string) ($row['availability'] ?? 'on_request');
            $data['featured'] = (int) ($row['featured'] ?? 0) === 1;
        }

        foreach ($this->obtenerConfiguracionModulo()['path_columns'] as $column) {
            $path = trim((string) ($row[$column] ?? ''));
            $data[$column] = $path !== '' ? $path : null;
            $data[$column . '_url'] = $path !== '' ? $this->resolverUrlPublica($path) : null;
        }

        $metadata = $row['metadata_json'] ?? null;
        $data['metadata_json'] = is_string($metadata) && trim($metadata) !== '' ? $metadata : null;

        return $data;
    }

    private function resolverUrlPublica(string $path): ?string
    {
        $path = trim($path);
        if ($path === '') {
            return null;
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $normalizedPath = $this->normalizarRutaPublica($path);
        $baseUrl = $this->obtenerBasePublica();

        return $baseUrl !== '' ? rtrim($baseUrl, '/') . $normalizedPath : $normalizedPath;
    }

    private function normalizarRutaPublica(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return '';
        }

        $path = preg_replace('#^https?://[^/]+#i', '', $path) ?? $path;
        $path = explode('?', $path, 2)[0];
        $path = str_replace('\\', '/', $path);
        $path = '/' . ltrim($path, '/');

        $appBasePath = $this->obtenerBaseRutaAplicacion();
        if ($appBasePath !== '' && str_starts_with($path, '/uploads/')) {
            return $appBasePath . $path;
        }

        if ($appBasePath === '' && str_starts_with($path, '/impulsa_emprende/')) {
            return '/' . ltrim(substr($path, strlen('/impulsa_emprende/')), '/');
        }

        return $path;
    }

    private function obtenerBasePublica(): string
    {
        $envUrl = trim((string) getenv('APP_URL'));
        if ($envUrl !== '') {
            return rtrim($envUrl, '/');
        }

        $host = trim((string) ($_SERVER['HTTP_HOST'] ?? ''));
        if ($host === '') {
            return '';
        }

        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https');
        $scheme = $isHttps ? 'https' : 'http';

        return $scheme . '://' . $host;
    }

    private function obtenerBaseRutaAplicacion(): string
    {
        $scriptName = trim((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($scriptName === '') {
            return '';
        }

        $scriptName = str_replace('\\', '/', $scriptName);
        $controllerPos = strpos($scriptName, '/controller/');
        if ($controllerPos === false) {
            return '';
        }

        $basePath = rtrim(substr($scriptName, 0, $controllerPos), '/');

        return $basePath === '/' ? '' : $basePath;
    }

    private function sanitizarHtmlBasico(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        $html = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', '', $html) ?? '';
        $html = preg_replace('/\son\w+="[^"]*"/i', '', $html) ?? '';
        $html = preg_replace("/\son\w+='[^']*'/i", '', $html) ?? '';
        $html = preg_replace('/\sstyle="[^"]*expression[^"]*"/i', '', $html) ?? '';
        $html = preg_replace("/\sstyle='[^']*expression[^']*'/i", '', $html) ?? '';

        $allowed = '<p><br><strong><b><em><i><u><s><blockquote><ul><ol><li><a><h1><h2><h3><h4><h5><h6><code><pre><span><table><thead><tbody><tfoot><tr><th><td><colgroup><col>';
        $html = strip_tags($html, $allowed);

        return trim($html);
    }

    private function normalizarTexto(mixed $value, bool $required, ?int $maxLength): ?string
    {
        if ($value === null) {
            if ($required) {
                throw new RuntimeException('Falta un campo obligatorio.');
            }

            return null;
        }

        if (!is_scalar($value)) {
            throw new RuntimeException('Se recibio un valor invalido.');
        }

        $text = trim((string) $value);
        if ($text === '') {
            if ($required) {
                throw new RuntimeException('Falta un campo obligatorio.');
            }

            return null;
        }

        if ($maxLength !== null) {
            $length = function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);
            if ($length > $maxLength) {
                throw new RuntimeException('Uno de los campos supera la longitud permitida.');
            }
        }

        return $text;
    }

    private function normalizarEstado(mixed $value): string
    {
        $status = trim((string) $value);

        if (!in_array($status, ['active', 'inactive', 'draft'], true)) {
            throw new RuntimeException('El estado indicado no es valido.');
        }

        return $status;
    }

    private function normalizarDisponibilidad(mixed $value): string
    {
        $availability = trim((string) $value);
        $allowed = ['in_stock', 'out_of_stock', 'preorder', 'on_request'];

        if (!in_array($availability, $allowed, true)) {
            throw new RuntimeException('La disponibilidad seleccionada no es valida.');
        }

        return $availability;
    }

    private function normalizarFechaHora(mixed $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            throw new RuntimeException('La fecha de publicacion no tiene un formato valido.');
        }

        return date('Y-m-d H:i:s', $timestamp);
    }

    private function normalizarEntero(mixed $value, int $minimum, int $maximum): int
    {
        $integer = filter_var($value, FILTER_VALIDATE_INT);
        if ($integer === false || $integer < $minimum || $integer > $maximum) {
            throw new RuntimeException('Uno de los valores numericos no es valido.');
        }

        return (int) $integer;
    }

    private function normalizarEnteroNullable(mixed $value, int $minimum): ?int
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        $integer = filter_var($value, FILTER_VALIDATE_INT);
        if ($integer === false || $integer < $minimum) {
            throw new RuntimeException('Uno de los valores numericos no es valido.');
        }

        return (int) $integer;
    }

    private function normalizarDecimal(mixed $value): ?string
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        $value = str_replace(',', '.', trim((string) $value));
        if (!is_numeric($value)) {
            throw new RuntimeException('Uno de los importes no tiene un formato valido.');
        }

        return number_format((float) $value, 2, '.', '');
    }

    private function normalizarBooleano(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array((string) $value, ['1', 'true', 'on', 'yes'], true);
    }

    private function normalizarMetadata(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        $text = trim((string) $value);
        if ($text === '') {
            return null;
        }

        json_decode($text, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('El campo metadata_json debe contener JSON valido.');
        }

        return $text;
    }

    private function prefijarParametros(array $columnas): array
    {
        $params = [];
        foreach ($columnas as $campo => $valor) {
            $params[':' . $campo] = $valor;
        }

        return $params;
    }

    private function slugify(string $text): string
    {
        $text = trim($text);
        $translit = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = is_string($translit) ? $translit : $text;
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
        $text = trim($text, '-');

        return substr($text, 0, 220);
    }

    private function generarNombreArchivoSeguro(string $prefix, string $originalBaseName, string $extension): string
    {
        $prefix = $this->slugify($prefix);
        if ($prefix === '') {
            $prefix = 'archivo';
        }

        $originalBaseName = $this->slugify($originalBaseName);
        if ($originalBaseName === '') {
            $originalBaseName = 'adjunto';
        }

        return $prefix . '_' . $originalBaseName . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $extension;
    }

    private function moverArchivoSubido(string $tmpName, string $destination): bool
    {
        if (move_uploaded_file($tmpName, $destination)) {
            @chmod($destination, 0664);
            return true;
        }

        if (@rename($tmpName, $destination)) {
            @chmod($destination, 0664);
            return true;
        }

        if (@copy($tmpName, $destination)) {
            @unlink($tmpName);
            @chmod($destination, 0664);
            return true;
        }

        return false;
    }

    private function normalizarRutaDirectorio(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return '';
        }

        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);

        return rtrim($path, DIRECTORY_SEPARATOR);
    }

    protected function buildBlogFileFields(): array
    {
        $blogUploadDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'API_Blog';

        return [
            'cover_image_file' => [
                'column' => 'cover_image_path',
                'label' => 'portada',
                'upload_dir' => $blogUploadDir,
                'public_path' => '/impulsa_emprende/uploads/API_Blog',
                'extensions' => self::BLOG_IMAGE_EXTENSIONS,
                'mime_types' => self::BLOG_IMAGE_MIME_TYPES,
                'max_bytes' => 4 * 1024 * 1024,
                'prefix' => 'blog_cover',
            ],
            'attachment_file' => [
                'column' => 'attachment_path',
                'label' => 'adjunto',
                'upload_dir' => $blogUploadDir,
                'public_path' => '/impulsa_emprende/uploads/API_Blog',
                'extensions' => self::BLOG_ATTACHMENT_EXTENSIONS,
                'mime_types' => self::BLOG_ATTACHMENT_MIME_TYPES,
                'max_bytes' => 8 * 1024 * 1024,
                'prefix' => 'blog_attachment',
            ],
        ];
    }

    protected function buildProductFileFields(): array
    {
        $productUploadDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'API_Productos';

        return [
            'main_image_file' => [
                'column' => 'main_image_path',
                'label' => 'imagen principal',
                'upload_dir' => $productUploadDir,
                'public_path' => '/impulsa_emprende/uploads/API_Productos',
                'extensions' => self::BLOG_IMAGE_EXTENSIONS,
                'mime_types' => self::BLOG_IMAGE_MIME_TYPES,
                'max_bytes' => 4 * 1024 * 1024,
                'prefix' => 'product_main',
            ],
            'thumbnail_file' => [
                'column' => 'thumbnail_path',
                'label' => 'miniatura',
                'upload_dir' => $productUploadDir,
                'public_path' => '/impulsa_emprende/uploads/API_Productos',
                'extensions' => self::BLOG_IMAGE_EXTENSIONS,
                'mime_types' => self::BLOG_IMAGE_MIME_TYPES,
                'max_bytes' => 4 * 1024 * 1024,
                'prefix' => 'product_thumb',
            ],
            'attachment_file' => [
                'column' => 'attachment_path',
                'label' => 'adjunto',
                'upload_dir' => $productUploadDir,
                'public_path' => '/impulsa_emprende/uploads/API_Productos',
                'extensions' => self::PRODUCT_ATTACHMENT_EXTENSIONS,
                'mime_types' => self::PRODUCT_ATTACHMENT_MIME_TYPES,
                'max_bytes' => 8 * 1024 * 1024,
                'prefix' => 'product_attachment',
            ],
        ];
    }

    private function obtenerItemPorIntegracion(int $integrationId, int $itemId): ?array
    {
        $config = $this->obtenerConfiguracionModulo();
        $stmt = $this->pdo->prepare(
            'SELECT *
             FROM ' . $config['table'] . '
             WHERE id = :id
               AND api_integration_id = :integration_id
             LIMIT 1'
        );
        $stmt->execute([
            ':id' => $itemId,
            ':integration_id' => $integrationId,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($row) ? $row : null;
    }
}
