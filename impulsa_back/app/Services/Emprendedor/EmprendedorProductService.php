<?php

namespace App\Services\Emprendedor;

use App\Models\ApiProduct;
use App\Models\UserAuth;
use App\Services\Admin\ApiProductAdminService;
use App\Support\EmprendedorIntegrationAccess;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class EmprendedorProductService
{
    public function __construct(
        private readonly EmprendedorIntegrationAccess $integrationAccess,
        private readonly ApiProductAdminService $productAdminService,
    ) {}

    public function summary(UserAuth $user): array
    {
        $integration = $this->integrationAccess->requireIntegration($user);

        return $this->productAdminService->summary((int) $integration->id);
    }

    /** @return Collection<int, object> */
    public function list(UserAuth $user, ?string $q = null, ?string $status = null): Collection
    {
        $integration = $this->integrationAccess->requireIntegration($user);

        return $this->productAdminService->listByIntegration((int) $integration->id, $q, $status);
    }

    public function find(UserAuth $user, int $productId): object
    {
        $integration = $this->integrationAccess->requireIntegration($user);
        $product = $this->productAdminService->find($productId);

        if ((int) $product->api_integration_id !== (int) $integration->id) {
            throw ValidationException::withMessages([
                'product' => ['No tenés permiso para acceder a este producto.'],
            ]);
        }

        return $product;
    }

    /** @param array<string, mixed> $payload */
    public function create(UserAuth $user, array $payload, array $files): ApiProduct
    {
        $integration = $this->integrationAccess->requireIntegration($user);

        return $this->productAdminService->create((int) $integration->id, $payload, $files);
    }

    /** @param array<string, mixed> $payload */
    public function update(UserAuth $user, ApiProduct $product, array $payload, array $files): ApiProduct
    {
        $this->find($user, (int) $product->id);

        return $this->productAdminService->update($product, $payload, $files);
    }

    public function updateStatus(UserAuth $user, ApiProduct $product, string $status): ApiProduct
    {
        $this->find($user, (int) $product->id);

        return $this->productAdminService->updateStatus($product, $status);
    }

    /** @return array{categories: list<string>, subcategories: list<string>} */
    public function taxonomy(UserAuth $user): array
    {
        $integration = $this->integrationAccess->requireIntegration($user);

        return $this->productAdminService->taxonomy((int) $integration->id);
    }

    public function delete(UserAuth $user, ApiProduct $product): void
    {
        $this->find($user, (int) $product->id);
        $this->productAdminService->delete($product);
    }

    public function resolveMediaFile(UserAuth $user, ApiProduct $product, string $mediaType): array
    {
        $this->find($user, (int) $product->id);

        return $this->productAdminService->resolveMediaFile($product, $mediaType);
    }
}
