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
    private const TABLE = 'api_blog_posts';
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    private const IMAGE_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    private const ATTACHMENT_EXTENSIONS = ['pdf', 'doc', 'docx', 'txt'];
    private const ATTACHMENT_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'text/plain',
    ];
    private const PATH_COLUMNS = ['cover_image_path', 'attachment_path'];

    private ApiBlogIntegrationAccessModel $integrationAccessModel;

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
        $sql = 'SELECT b.*, ai.project_name, ai.allowed_domain
                FROM ' . self::TABLE . ' b
                INNER JOIN api_integrations ai ON ai.id = b.api_integration_id
                LEFT JOIN projects p
                    ON p.project_name = ai.project_name
                   AND p.client_user_id = :user_id
                   AND p.client_visible = 1
                LEFT JOIN landing_page_request lpr
                    ON lpr.nombre_emprendimiento = ai.project_name
                   AND lpr.user_auth_id = :user_id
                WHERE (p.id IS NOT NULL OR lpr.id IS NOT NULL)
                  AND b.created_by_user_id = :created_by_user_id';
        $params = [
            ':user_id' => $userId,
            ':created_by_user_id' => $userId,
        ];

        if ($integrationId !== null) {
            $sql .= ' AND b.api_integration_id = :integration_id';
            $params[':integration_id'] = $integrationId;
        }

        $sql .= ' ORDER BY b.sort_order ASC, b.publication_date DESC, b.created_at DESC, b.id DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return array_map(fn (array $item): array => $this->mapearRegistroParaVista($item), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function obtenerItemEditable(int $userId, int $itemId): ?array
    {
        $sql = 'SELECT b.*, ai.project_name, ai.allowed_domain
                FROM ' . self::TABLE . ' b
                INNER JOIN api_integrations ai ON ai.id = b.api_integration_id
                LEFT JOIN projects p
                    ON p.project_name = ai.project_name
                   AND p.client_user_id = :user_id
                   AND p.client_visible = 1
                LEFT JOIN landing_page_request lpr
                    ON lpr.nombre_emprendimiento = ai.project_name
                   AND lpr.user_auth_id = :user_id
                WHERE b.id = :item_id
                  AND b.created_by_user_id = :created_by_user_id
                  AND (p.id IS NOT NULL OR lpr.id IS NOT NULL)
                LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':created_by_user_id' => $userId,
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
            throw new RuntimeException('La publicacion que intentas editar no existe o no pertenece a tu cuenta.');
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
                WHERE id = :id
                  AND created_by_user_id = :created_by_user_id_guard';
        $params = $this->prefijarParametros($columnas);
        $params[':id'] = $itemId;
        $params[':created_by_user_id_guard'] = $userId;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $itemId;
    }

    public function eliminarItem(int $userId, int $itemId): void
    {
        $item = $this->obtenerItemEditable($userId, $itemId);
        if ($item === null) {
            throw new RuntimeException('La publicacion seleccionada no existe o no pertenece a tu cuenta.');
        }

        $configArchivos = $this->obtenerConfiguracionArchivos();
        foreach ($configArchivos as $config) {
            $columna = (string) ($config['column'] ?? '');
            if ($columna !== '') {
                $this->eliminarArchivoLocalSiExiste((string) ($item[$columna] ?? ''), (string) ($config['upload_dir'] ?? ''));
            }
        }

        $stmt = $this->pdo->prepare(
            'DELETE FROM ' . self::TABLE . '
             WHERE id = :id
               AND created_by_user_id = :created_by_user_id'
        );
        $stmt->execute([
            ':id' => $itemId,
            ':created_by_user_id' => $userId,
        ]);
    }

    public function obtenerArchivoEditable(int $userId, int $itemId, string $column): ?array
    {
        if (!in_array($column, self::PATH_COLUMNS, true)) {
            return null;
        }

        $item = $this->obtenerItemEditable($userId, $itemId);
        if ($item === null) {
            return null;
        }

        $rutaGuardada = trim((string) ($item[$column] ?? ''));
        if ($rutaGuardada === '') {
            return null;
        }

        $config = $this->buscarConfiguracionArchivoPorColumna($column);
        if ($config === null) {
            return null;
        }

        $absolutePath = $this->resolverRutaArchivoLocal($rutaGuardada, (string) ($config['upload_dir'] ?? ''));
        if ($absolutePath === null) {
            return null;
        }

        $mimeType = function_exists('mime_content_type') ? (string) mime_content_type($absolutePath) : '';
        if ($mimeType === '') {
            $mimeType = $column === 'cover_image_path' ? 'image/jpeg' : 'application/octet-stream';
        }

        return [
            'absolute_path' => $absolutePath,
            'mime_type' => $mimeType,
            'download_name' => basename($absolutePath),
        ];
    }

    private function normalizarPayload(array $payload, ?array $existente): array
    {
        $title = $this->normalizarTexto($payload['title'] ?? null, true, 180);
        $slugFuente = $this->normalizarTexto($payload['slug'] ?? null, false, 220) ?? $title;
        $slug = $this->slugify($slugFuente);
        if ($slug === '') {
            throw new RuntimeException('No se pudo generar un slug valido para esta publicacion.');
        }

        $descriptionHtml = $this->sanitizarHtmlBasico($this->normalizarTexto($payload['description_html'] ?? null, true, null));
        if ($descriptionHtml === '') {
            throw new RuntimeException('El contenido del blog no puede quedar vacio.');
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
        if ($shouldRemove) {
            $this->eliminarArchivoLocalSiExiste($currentPath, (string) ($fieldConfig['upload_dir'] ?? ''));
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
            throw new RuntimeException('No se pudo guardar el archivo ' . $fieldConfig['label'] . '.');
        }

        if ($currentPath !== '') {
            $this->eliminarArchivoLocalSiExiste($currentPath, (string) ($fieldConfig['upload_dir'] ?? ''));
        }

        return rtrim((string) $fieldConfig['public_path'], '/') . '/' . $fileName;
    }

private function obtenerConfiguracionArchivos(): array
{
    $documentRoot = $this->normalizarRutaDirectorio((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));

    if ($documentRoot === '') {
        throw new RuntimeException('No se pudo resolver DOCUMENT_ROOT para guardar archivos.');
    }

    // public_html está en DOCUMENT_ROOT. Subimos un nivel y usamos /storage/API_Blog.
    $storageRoot = dirname($documentRoot);
    $uploadDir = $storageRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'API_Blog';

    // No es una URL pública directa. Es una marca interna para guardar el archivo.
    $publicPath = 'API_Blog';

    return [
            'cover_image_file' => [
                'column' => 'cover_image_path',
                'label' => 'portada',
                'upload_dir' => $uploadDir,
                'public_path' => $publicPath,
                'extensions' => self::IMAGE_EXTENSIONS,
                'mime_types' => self::IMAGE_MIME_TYPES,
                'max_bytes' => 4 * 1024 * 1024,
                'prefix' => 'blog_cover',
                'remove_field' => 'remove_cover_image',
            ],
            'attachment_file' => [
                'column' => 'attachment_path',
                'label' => 'adjunto',
                'upload_dir' => $uploadDir,
                'public_path' => $publicPath,
                'extensions' => self::ATTACHMENT_EXTENSIONS,
                'mime_types' => self::ATTACHMENT_MIME_TYPES,
                'max_bytes' => 8 * 1024 * 1024,
                'prefix' => 'blog_attachment',
                'remove_field' => 'remove_attachment',
            ],
        ];
    }

    private function buscarConfiguracionArchivoPorColumna(string $column): ?array
    {
        foreach ($this->obtenerConfiguracionArchivos() as $config) {
            if (($config['column'] ?? '') === $column) {
                return $config;
            }
        }

        return null;
    }

private function mapearRegistroParaVista(array $row): array
{
    $mapped = $row;
    $itemId = (int) ($row['id'] ?? 0);

    foreach (self::PATH_COLUMNS as $column) {
        $storedPath = trim((string) ($row[$column] ?? ''));

        if ($storedPath === '' || $itemId <= 0) {
            $mapped[$column . '_url'] = null;
            continue;
        }

        $mediaType = $column === 'cover_image_path' ? 'cover' : 'attachment';

        $mapped[$column . '_url'] = '/impulsa_emprende/controller/client/ClienteBlogController.php?'
            . http_build_query([
                'media_item_id' => $itemId,
                'media_type' => $mediaType,
            ]);
    }

    return $mapped;
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
            throw new RuntimeException('Ya existe otra publicacion con el mismo slug dentro de esta integracion.');
        }
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
        if ($storedPath === '') {
            return null;
        }

        $normalizedStoredPath = str_replace('\\', '/', $storedPath);
        $baseName = basename($normalizedStoredPath);
        $uploadDir = $this->normalizarRutaDirectorio($uploadDir);
        $candidates = [];
        if ($uploadDir !== '') {
            $candidates[] = $uploadDir . DIRECTORY_SEPARATOR . $baseName;
        }

        $repoRoot = dirname(__DIR__, 3);
        $appRoot = dirname(__DIR__, 2);
        $trimmedPath = ltrim(preg_replace('#^https?://[^/]+#i', '', $normalizedStoredPath) ?? $normalizedStoredPath, '/');
        if ($trimmedPath !== '') {
            $candidates[] = $repoRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $trimmedPath);
            $candidates[] = $appRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, preg_replace('#^impulsa_emprende/#', '', $trimmedPath) ?? $trimmedPath);
        }

        foreach (array_unique($candidates) as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
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

    private function eliminarArchivoLocalSiExiste(string $storedPath, string $uploadDir): void
    {
        $absolutePath = $this->resolverRutaArchivoLocal($storedPath, $uploadDir);
        if ($absolutePath !== null && is_file($absolutePath)) {
            @unlink($absolutePath);
        }
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

    private function normalizarEntero(mixed $value, int $minimum, int $maximum): int
    {
        $integer = filter_var($value, FILTER_VALIDATE_INT);
        if ($integer === false || $integer < $minimum || $integer > $maximum) {
            throw new RuntimeException('Uno de los valores numericos no es valido.');
        }

        return (int) $integer;
    }

    private function normalizarFechaHora(mixed $value): ?string
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        $text = trim((string) $value);
        $date = DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $text)
            ?: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $text)
            ?: DateTimeImmutable::createFromFormat('Y-m-d H:i', $text);

        if (!$date) {
            throw new RuntimeException('La fecha de publicacion no tiene un formato valido.');
        }

        return $date->format('Y-m-d H:i:s');
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

    private function resolverDirectorioUploadDesdeRutaPublica(string $publicPath, string $moduleFolder): string
    {
        $publicPath = '/' . ltrim(trim($publicPath), '/');
        $moduleFolder = trim($moduleFolder);
        if ($publicPath === '/' || $moduleFolder === '') {
            return '';
        }

        $documentRoot = $this->normalizarRutaDirectorio((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));
        if ($documentRoot !== '') {
            return $documentRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($publicPath, '/'));
        }

        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $moduleFolder;
    }
}
