<?php

namespace App\Services\Admin;

use App\Models\ApiIntegration;
use App\Models\ApiProduct;
use App\Services\ApiProduct\ApiProductNormalizer;
use App\Services\ApiProduct\ApiProductStorageService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApiProductAdminService
{
    public function __construct(
        private readonly ApiIntegrationAdminService $integrationAdminService,
        private readonly ApiProductNormalizer $normalizer,
        private readonly ApiProductStorageService $storageService,
    ) {}

    /** @return array<string, int> */
    public function summary(?int $integrationId = null): array
    {
        $query = DB::table('api_products');

        if ($integrationId !== null) {
            $query->where('api_integration_id', $integrationId);
        }

        $row = $query
            ->selectRaw("
                COUNT(*) AS total_productos,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS total_activos,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) AS total_borrador,
                SUM(CASE WHEN featured = 1 THEN 1 ELSE 0 END) AS total_destacados
            ")
            ->first();

        return [
            'total_productos' => (int) ($row->total_productos ?? 0),
            'total_activos' => (int) ($row->total_activos ?? 0),
            'total_borrador' => (int) ($row->total_borrador ?? 0),
            'total_destacados' => (int) ($row->total_destacados ?? 0),
        ];
    }

    /** @return Collection<int, object> */
    public function integrationOptions(): Collection
    {
        $this->integrationAdminService->syncOwners();

        return DB::table('api_integrations as ai')
            ->join('user_auth as ua', 'ua.id', '=', 'ai.user_auth_id')
            ->leftJoin('user_contacto as uc', 'uc.user_auth_id', '=', 'ua.id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->leftJoin('api_products as ap', 'ap.api_integration_id', '=', 'ai.id')
            ->selectRaw('
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
            ')
            ->groupBy(
                'ai.id',
                'ai.project_name',
                'ai.allowed_domain',
                'ai.public_key',
                'ai.status',
                'ai.user_auth_id',
                'ua.correo',
                'uc.correo',
                'ui.nombre',
                'ui.apellido',
                'ui.apodo',
            )
            ->orderBy('ai.project_name')
            ->orderBy('ai.id')
            ->get();
    }

    public function findIntegration(int $integrationId): object
    {
        $integration = $this->integrationOptions()->firstWhere('id', $integrationId);

        if ($integration === null) {
            throw ValidationException::withMessages([
                'api_integration_id' => ['La integración seleccionada no existe o no tiene usuario asignado.'],
            ]);
        }

        return $integration;
    }

    /** @return Collection<int, object> */
    public function listByIntegration(int $integrationId, ?string $q = null, ?string $status = null): Collection
    {
        $this->findIntegration($integrationId);

        $query = $this->baseProductQuery()->where('ap.api_integration_id', $integrationId);

        $search = trim((string) $q);

        if (mb_strlen($search) >= 3) {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('ap.title', 'like', $like)
                    ->orWhere('ap.sku', 'like', $like)
                    ->orWhere('ap.category', 'like', $like)
                    ->orWhere('ap.subcategory', 'like', $like);
            });
        }

        $statusFilter = trim((string) $status);

        if ($statusFilter !== '' && $statusFilter !== '__all__') {
            $query->where('ap.status', $statusFilter);
        }

        return $query
            ->orderBy('ap.sort_order')
            ->orderByDesc('ap.updated_at')
            ->orderByDesc('ap.id')
            ->get();
    }

    public function find(int $productId): object
    {
        $row = $this->baseProductQuery()->where('ap.id', $productId)->first();

        if ($row === null) {
            throw ValidationException::withMessages([
                'product' => ['El producto no existe.'],
            ]);
        }

        return $row;
    }

    /** @param array<string, mixed> $payload */
    public function create(int $integrationId, array $payload, array $files): ApiProduct
    {
        $integration = $this->findIntegration($integrationId);
        $ownerId = (int) ($integration->user_auth_id ?? 0);

        if ($ownerId <= 0) {
            throw ValidationException::withMessages([
                'api_integration_id' => ['La integración elegida no tiene un usuario propietario asignado.'],
            ]);
        }

        $normalized = $this->normalizer->normalizePayload($payload);
        $this->ensureUniqueSlug($integrationId, $normalized['slug']);

        $filePaths = $this->storageService->resolveUploadedFiles($files, null, $payload);

        $product = ApiProduct::query()->create(array_merge($normalized, $filePaths, [
            'api_integration_id' => $integrationId,
            'created_by_user_id' => $ownerId,
        ]));

        return $product;
    }

    /** @param array<string, mixed> $payload */
    public function update(ApiProduct $product, array $payload, array $files): ApiProduct
    {
        $integrationId = (int) $product->api_integration_id;
        $this->findIntegration($integrationId);

        $existing = $product->only([
            'sku',
            'short_description',
            'category',
            'subcategory',
            'price',
            'compare_at_price',
            'currency',
            'stock_quantity',
            'availability',
            'status',
            'featured',
            'sort_order',
            'metadata_json',
            'main_image_path',
            'thumbnail_path',
            'attachment_path',
        ]);

        $normalized = $this->normalizer->normalizePayload($payload, $existing);
        $this->ensureUniqueSlug($integrationId, $normalized['slug'], (int) $product->id);

        $filePaths = $this->storageService->resolveUploadedFiles($files, $existing, $payload);

        $product->fill(array_merge($normalized, $filePaths));
        $product->save();

        return $product;
    }

    public function updateStatus(ApiProduct $product, string $status): ApiProduct
    {
        $this->findIntegration((int) $product->api_integration_id);
        $product->status = $this->normalizer->normalizeStatus($status);
        $product->save();

        return $product;
    }

    public function resolveMediaFile(ApiProduct $product, string $mediaType): array
    {
        $column = match ($mediaType) {
            'main' => 'main_image_path',
            'thumbnail' => 'thumbnail_path',
            'attachment' => 'attachment_path',
            default => null,
        };

        if ($column === null) {
            throw ValidationException::withMessages([
                'media_type' => ['El tipo de archivo solicitado no es válido.'],
            ]);
        }

        $storedPath = trim((string) $product->{$column});

        if ($storedPath === '') {
            throw ValidationException::withMessages([
                'media_type' => ['Este producto no tiene ese archivo.'],
            ]);
        }

        $absolutePath = $this->storageService->resolveAbsolutePath($storedPath);

        if ($absolutePath === null) {
            throw ValidationException::withMessages([
                'media_type' => ['No se encontró el archivo en el servidor.'],
            ]);
        }

        $mimeType = mime_content_type($absolutePath) ?: 'application/octet-stream';

        return [
            'absolute_path' => $absolutePath,
            'mime_type' => $mimeType,
            'download_name' => basename($absolutePath),
        ];
    }

    private function ensureUniqueSlug(int $integrationId, string $slug, ?int $excludeId = null): void
    {
        $query = ApiProduct::query()
            ->where('api_integration_id', $integrationId)
            ->where('slug', $slug);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'slug' => ['Ya existe otro producto con el mismo slug dentro de esta integración.'],
            ]);
        }
    }

    private function baseProductQuery()
    {
        return DB::table('api_products as ap')
            ->join('api_integrations as ai', 'ai.id', '=', 'ap.api_integration_id')
            ->join('user_auth as ua', 'ua.id', '=', 'ai.user_auth_id')
            ->leftJoin('user_contacto as uc', 'uc.user_auth_id', '=', 'ua.id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->select([
                'ap.*',
                'ai.project_name',
                'ai.allowed_domain',
                'ai.public_key',
                'ai.user_auth_id',
                'ua.correo as owner_auth_correo',
                'uc.correo as owner_contacto_correo',
                'ui.nombre as owner_nombre',
                'ui.apellido as owner_apellido',
                'ui.apodo as owner_apodo',
            ]);
    }
}
