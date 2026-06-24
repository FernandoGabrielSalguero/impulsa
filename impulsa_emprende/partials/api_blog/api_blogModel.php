<?php

declare(strict_types=1);

final class ApiBlogIntegrationAccessModel
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

final class ApiBlogModel
{
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    private const IMAGE_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    private const ATTACHMENT_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'zip'];
    private const ATTACHMENT_MIME_TYPES = [
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

    private const TABLE = 'api_blog_posts';
    private const PATH_COLUMNS = ['cover_image_path', 'attachment_path'];
    private const PUBLIC_UPLOAD_PATH = '/impulsa_emprende/uploads/API_Blog';

    private ApiBlogIntegrationAccessModel $integrationAccessModel;
    private static array $debugEvents = [];

    public function __construct(private PDO $pdo)
    {
        $this->integrationAccessModel = new ApiBlogIntegrationAccessModel($pdo);
    }

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
        $sql = 'SELECT c.*, ai.project_name, ai.allowed_domain
                FROM ' . self::TABLE . ' c
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

        $sql .= ' ORDER BY c.sort_order ASC, COALESCE(c.publication_date, c.created_at) DESC, c.id DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return array_map(fn (array $item): array => $this->mapearRegistroParaVista($item), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function obtenerItemEditable(int $userId, int $itemId): ?array
    {
        $sql = 'SELECT c.*, ai.project_name, ai.allowed_domain
                FROM ' . self::TABLE . ' c
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

    public function obtenerArchivoEditable(int $userId, int $itemId, string $column): ?array
    {
        $column = trim($column);
        if ($column === '') {
            return null;
        }

        $item = $this->obtenerItemEditable($userId, $itemId);
        if ($item === null) {
            return null;
        }

        foreach ($this->obtenerConfiguracionArchivos() as $fieldConfig) {
            if (($fieldConfig['column'] ?? '') !== $column) {
                continue;
            }

            $storedPath = trim((string) ($item[$column] ?? ''));
            if ($storedPath === '') {
                return null;
            }

            $uploadDir = (string) ($fieldConfig['upload_dir'] ?? '');
            $absolutePath = $this->resolverRutaArchivoLocal($storedPath, $uploadDir);
            if ($absolutePath === null) {
                $this->registrarEventoArchivo('stored_path_missing_on_read', $fieldConfig, [
                    'item_id' => $itemId,
                    'stored_path' => $storedPath,
                    'resolver_candidates' => $this->construirCandidatosRutaArchivo($storedPath, $uploadDir),
                ]);
                return null;
            }

            return [
                'absolute_path' => $absolutePath,
                'mime_type' => $this->resolverMimeTypeArchivo($absolutePath),
                'download_name' => basename($absolutePath),
            ];
        }

        return null;
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

        $normalizado = $this->normalizarPayload($payload, $existente);
        $archivos = $this->resolverArchivos($files, $existente, $payload);

        $columnas = array_merge([
            'api_integration_id' => $integrationId,
            'title' => $normalizado['title'],
            'slug' => $normalizado['slug'],
            'subtitle' => $normalizado['subtitle'],
            'author' => $normalizado['author'],
            'bibliography' => $normalizado['bibliography'],
            'category' => $normalizado['category'],
            'subcategory' => $normalizado['subcategory'],
            'excerpt' => $normalizado['excerpt'],
            'description_html' => $normalizado['description_html'],
            'publication_date' => $normalizado['publication_date'],
            'status' => $normalizado['status'],
            'sort_order' => $normalizado['sort_order'],
            'metadata_json' => $normalizado['metadata_json'],
        ], $archivos);

        if ($itemId === null) {
            $columnas['created_by_user_id'] = $userId;
            $this->asegurarSlugUnico($integrationId, $columnas['slug']);
            $campos = array_keys($columnas);
            $sql = 'INSERT INTO ' . self::TABLE . ' (' . implode(', ', $campos) . ')
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

        $sql = 'UPDATE ' . self::TABLE . '
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

        $normalizado = $this->normalizarPayload($payload, $existente);
        $archivos = $this->resolverArchivos($files, $existente, $payload);

        $columnas = array_merge([
            'api_integration_id' => $integrationId,
            'title' => $normalizado['title'],
            'slug' => $normalizado['slug'],
            'subtitle' => $normalizado['subtitle'],
            'author' => $normalizado['author'],
            'bibliography' => $normalizado['bibliography'],
            'category' => $normalizado['category'],
            'subcategory' => $normalizado['subcategory'],
            'excerpt' => $normalizado['excerpt'],
            'description_html' => $normalizado['description_html'],
            'publication_date' => $normalizado['publication_date'],
            'status' => $normalizado['status'],
            'sort_order' => $normalizado['sort_order'],
            'metadata_json' => $normalizado['metadata_json'],
        ], $archivos);

        if ($itemId === null) {
            $columnas['created_by_user_id'] = $createdByUserId;
            $this->asegurarSlugUnico($integrationId, $columnas['slug']);
            $campos = array_keys($columnas);
            $sql = 'INSERT INTO ' . self::TABLE . ' (' . implode(', ', $campos) . ')
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

        $sql = 'UPDATE ' . self::TABLE . '
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
        $status = $this->normalizarEstado($status);
        $item = $this->obtenerItemEditable($userId, $itemId);
        if ($item === null) {
            throw new RuntimeException('El registro seleccionado no existe o no pertenece a tu cuenta.');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE ' . self::TABLE . '
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
        $status = $this->normalizarEstado($status);
        $item = $this->obtenerItemPorIntegracion($integrationId, $itemId);
        if ($item === null) {
            throw new RuntimeException('El registro seleccionado no existe dentro de esta integracion.', 404);
        }

        $stmt = $this->pdo->prepare(
            'UPDATE ' . self::TABLE . '
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
        $sql = 'SELECT *
                FROM ' . self::TABLE . '
                WHERE api_integration_id = :integration_id
                  AND status = :status
                  AND (publication_date IS NULL OR publication_date <= NOW())
                ORDER BY sort_order ASC, COALESCE(publication_date, created_at) DESC, id DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':integration_id' => $integrationId,
            ':status' => 'active',
        ]);

        return array_map(fn (array $row): array => $this->mapearRegistroPublico($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function obtenerDetallePublico(int $integrationId, ?int $itemId, ?string $slug): ?array
    {
        $sql = 'SELECT *
                FROM ' . self::TABLE . '
                WHERE api_integration_id = :integration_id
                  AND status = :status
                  AND (publication_date IS NULL OR publication_date <= NOW())';
        $params = [
            ':integration_id' => $integrationId,
            ':status' => 'active',
        ];

        if ($itemId !== null) {
            $sql .= ' AND id = :item_id';
            $params[':item_id'] = $itemId;
        } elseif ($slug !== null && trim($slug) !== '') {
            $sql .= ' AND slug = :slug';
            $params[':slug'] = trim($slug);
        } else {
            throw new RuntimeException('Debes indicar id o slug para obtener el detalle.', 422);
        }

        $sql .= ' LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($row) ? $this->mapearRegistroPublico($row) : null;
    }

    public static function pullDebugEvents(): array
    {
        $events = self::$debugEvents;
        self::$debugEvents = [];

        return $events;
    }

    private function normalizarPayload(array $payload, ?array $existente): array
    {
        $title = $this->normalizarTexto($payload['title'] ?? null, true, 180);
        $slugFuente = $this->normalizarTexto($payload['slug'] ?? null, false, 220) ?? $title;
        $slug = $this->slugify($slugFuente);
        if ($slug === '') {
            throw new RuntimeException('No se pudo generar un slug valido para este registro.');
        }

        $descriptionHtml = $this->sanitizarHtmlBasico($this->normalizarTexto($payload['description_html'] ?? null, true, null));
        if ($descriptionHtml === '') {
            throw new RuntimeException('La descripcion no puede quedar vacia.');
        }

        return [
            'title' => $title,
            'slug' => $slug,
            'subtitle' => $this->normalizarTexto($payload['subtitle'] ?? ($existente['subtitle'] ?? null), false, 255),
            'author' => $this->normalizarTexto($payload['author'] ?? ($existente['author'] ?? null), false, 180),
            'bibliography' => $this->normalizarTexto($payload['bibliography'] ?? ($existente['bibliography'] ?? null), false, null),
            'category' => $this->normalizarTexto($payload['category'] ?? ($existente['category'] ?? null), false, 120),
            'subcategory' => $this->normalizarTexto($payload['subcategory'] ?? ($existente['subcategory'] ?? null), false, 120),
            'excerpt' => $this->normalizarTexto($payload['excerpt'] ?? ($existente['excerpt'] ?? null), false, 300),
            'description_html' => $descriptionHtml,
            'publication_date' => $this->normalizarFechaHora($payload['publication_date'] ?? ($existente['publication_date'] ?? null)),
            'status' => $this->normalizarEstado($payload['status'] ?? ($existente['status'] ?? 'draft')),
            'sort_order' => $this->normalizarEntero($payload['sort_order'] ?? ($existente['sort_order'] ?? 1), 1, 999999),
            'metadata_json' => $this->normalizarMetadata($payload['metadata_json'] ?? ($existente['metadata_json'] ?? null)),
        ];
    }

    private function resolverArchivos(array $files, ?array $existente, array $payload): array
    {
        $archivos = [];
        foreach ($this->obtenerConfiguracionArchivos() as $fieldName => $fieldConfig) {
            $current = (string) ($existente[$fieldConfig['column']] ?? '');
            $removeField = (string) ($fieldConfig['remove_field'] ?? '');
            $shouldRemove = $removeField !== '' && $this->normalizarBooleano($payload[$removeField] ?? false);
            $archivos[$fieldConfig['column']] = $this->guardarArchivoSubido($files[$fieldName] ?? null, $fieldConfig, $current, $shouldRemove);
        }

        return $archivos;
    }

    private function guardarArchivoSubido(?array $file, array $fieldConfig, string $currentPath, bool $shouldRemove = false): ?string
    {
        $currentPath = trim($currentPath);
        $preserveFiles = (bool) ($fieldConfig['preserve_file_on_replace'] ?? false);

        if ($shouldRemove) {
            $this->registrarEventoArchivo('remove_requested', $fieldConfig, [
                'stored_path' => $currentPath,
                'preserve_file_on_replace' => $preserveFiles,
            ]);
            if (!$preserveFiles) {
                $this->eliminarArchivoLocalSiExiste($currentPath, (string) ($fieldConfig['upload_dir'] ?? ''));
            }
            $currentPath = '';
        }

        if ($file === null || !isset($file['error']) || (int) $file['error'] === UPLOAD_ERR_NO_FILE) {
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

        $fileName = $this->generarNombreArchivoSeguro((string) ($fieldConfig['prefix'] ?? 'archivo'), pathinfo($originalName, PATHINFO_FILENAME), $extension);
        $destination = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
        if (!$this->moverArchivoSubido($tmpName, $destination)) {
            throw new RuntimeException('No se pudo guardar el archivo ' . $fieldConfig['label'] . ' en ' . $uploadDir . '.');
        }

        $storedPath = rtrim((string) $fieldConfig['public_path'], '/') . '/' . $fileName;
        $this->registrarEventoArchivo('upload_stored', $fieldConfig, [
            'original_name' => $originalName,
            'stored_path' => $storedPath,
            'destination' => $destination,
            'replaced_previous_path' => $currentPath !== '' ? $currentPath : null,
        ]);

        if ($currentPath !== '') {
            $this->registrarEventoArchivo($preserveFiles ? 'replace_preserved_previous' : 'replace_deleted_previous', $fieldConfig, [
                'stored_path' => $currentPath,
                'preserve_file_on_replace' => $preserveFiles,
            ]);
            if (!$preserveFiles) {
                $this->eliminarArchivoLocalSiExiste($currentPath, (string) ($fieldConfig['upload_dir'] ?? ''));
            }
        }

        return $storedPath;
    }

    private function obtenerConfiguracionArchivos(): array
    {
        $publicPath = self::PUBLIC_UPLOAD_PATH;
        $uploadDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'API_Blog';

        return [
            'cover_image_file' => [
                'column' => 'cover_image_path',
                'remove_field' => 'cover_image_remove',
                'label' => 'portada',
                'upload_dir' => $uploadDir,
                'public_path' => $publicPath,
                'preserve_file_on_replace' => true,
                'extensions' => self::IMAGE_EXTENSIONS,
                'mime_types' => self::IMAGE_MIME_TYPES,
                'max_bytes' => 4 * 1024 * 1024,
                'prefix' => 'blog_cover',
            ],
            'attachment_file' => [
                'column' => 'attachment_path',
                'remove_field' => 'attachment_remove',
                'label' => 'adjunto',
                'upload_dir' => $uploadDir,
                'public_path' => $publicPath,
                'preserve_file_on_replace' => true,
                'extensions' => self::ATTACHMENT_EXTENSIONS,
                'mime_types' => self::ATTACHMENT_MIME_TYPES,
                'max_bytes' => 8 * 1024 * 1024,
                'prefix' => 'blog_attachment',
            ],
        ];
    }

    private function mapearRegistroParaVista(array $row): array
    {
        $mapped = $row;
        foreach (self::PATH_COLUMNS as $column) {
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
            'subtitle' => $row['subtitle'] !== null ? (string) $row['subtitle'] : null,
            'author' => $row['author'] !== null ? (string) $row['author'] : null,
            'bibliography' => $row['bibliography'] !== null ? (string) $row['bibliography'] : null,
            'category' => $row['category'] !== null ? (string) $row['category'] : null,
            'subcategory' => $row['subcategory'] !== null ? (string) $row['subcategory'] : null,
            'excerpt' => $row['excerpt'] !== null ? (string) $row['excerpt'] : null,
            'description_html' => (string) ($row['description_html'] ?? ''),
            'publication_date' => $row['publication_date'] !== null ? (string) $row['publication_date'] : null,
            'status' => (string) ($row['status'] ?? ''),
            'sort_order' => (int) ($row['sort_order'] ?? 1),
            'created_at' => $row['created_at'] !== null ? (string) $row['created_at'] : null,
            'updated_at' => $row['updated_at'] !== null ? (string) $row['updated_at'] : null,
        ];

        foreach (self::PATH_COLUMNS as $column) {
            $path = trim((string) ($row[$column] ?? ''));
            $data[$column] = $path !== '' ? $path : null;
            $data[$column . '_url'] = $path !== '' ? $this->resolverUrlPublica($path) : null;
        }

        $metadata = $row['metadata_json'] ?? null;
        $data['metadata_json'] = is_string($metadata) && trim($metadata) !== '' ? $metadata : null;

        return $data;
    }

    private function asegurarSlugUnico(int $integrationId, string $slug, ?int $excludeId = null): void
    {
        $sql = 'SELECT id
                FROM ' . self::TABLE . '
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

    private function obtenerItemPorIntegracion(int $integrationId, int $itemId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
             FROM ' . self::TABLE . '
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

    private function resolverRutaArchivoLocal(string $storedPath, string $uploadDir): ?string
    {
        $storedPath = trim($storedPath);
        $uploadDir = $this->normalizarRutaDirectorio($uploadDir);
        if ($storedPath === '' || $uploadDir === '') {
            return null;
        }

        $candidate = $uploadDir . DIRECTORY_SEPARATOR . basename(str_replace('\\', '/', $storedPath));

        return is_file($candidate) ? $candidate : null;
    }

    private function construirCandidatosRutaArchivo(string $storedPath, string $uploadDir): array
    {
        $storedPath = trim($storedPath);
        if ($storedPath === '') {
            return [];
        }

        $uploadDir = $this->normalizarRutaDirectorio($uploadDir);
        if ($uploadDir === '') {
            return [];
        }

        return [$uploadDir . DIRECTORY_SEPARATOR . basename(str_replace('\\', '/', $storedPath))];
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

    private function resolverMimeTypeArchivo(string $absolutePath): string
    {
        $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : false;
        $mimeType = $finfo ? (string) finfo_file($finfo, $absolutePath) : '';
        if ($finfo) {
            finfo_close($finfo);
        }

        if ($mimeType !== '') {
            return $mimeType;
        }

        return match (strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION))) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'csv' => 'text/csv',
            'txt' => 'text/plain',
            'zip' => 'application/zip',
            default => 'application/octet-stream',
        };
    }

    private function eliminarArchivoLocalSiExiste(string $storedPath, string $uploadDir): void
    {
        $absolutePath = $this->resolverRutaArchivoLocal($storedPath, $uploadDir);
        if ($absolutePath === null || !is_file($absolutePath)) {
            return;
        }

        @unlink($absolutePath);
    }

    private function registrarEventoArchivo(string $event, array $fieldConfig, array $context = []): void
    {
        $payload = [
            'component' => 'api_blog_model',
            'module' => 'blog',
            'event' => $event,
            'field' => (string) ($fieldConfig['column'] ?? ''),
            'label' => (string) ($fieldConfig['label'] ?? ''),
            'request_method' => (string) ($_SERVER['REQUEST_METHOD'] ?? ''),
            'request_uri' => (string) ($_SERVER['REQUEST_URI'] ?? ''),
            'context' => $context,
        ];

        self::$debugEvents[] = $payload;
        error_log('[ImpulsaFile] ' . json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
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

        return trim(strip_tags($html, '<p><br><strong><b><em><i><u><s><blockquote><ul><ol><li><a><h1><h2><h3><h4><h5><h6><code><pre><span><table><thead><tbody><tfoot><tr><th><td><colgroup><col>'));
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
        $prefix = $this->slugify($prefix) ?: 'archivo';
        $originalBaseName = $this->slugify($originalBaseName) ?: 'adjunto';

        return $prefix . '_' . $originalBaseName . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $extension;
    }

    private function moverArchivoSubido(string $tmpName, string $destination): bool
    {
        if (move_uploaded_file($tmpName, $destination) || @rename($tmpName, $destination)) {
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

        return rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
    }

}
