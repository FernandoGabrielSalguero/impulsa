<?php

namespace App\PublicEmbed\Services;

use App\Models\ApiIntegration;
use App\Services\PublicApi\LegacyPublicProductService;

class PublicProductService
{
    public function __construct(
        private readonly LegacyPublicProductService $legacyProductService,
    ) {}

    /** @return list<array<string, mixed>> */
    public function listProducts(ApiIntegration $integration): array
    {
        return $this->legacyProductService->listProducts($integration);
    }

    /** @return array<string, mixed> */
    public function findBySlug(ApiIntegration $integration, string $slug): array
    {
        return $this->legacyProductService->findBySlug($integration, $slug);
    }
}
