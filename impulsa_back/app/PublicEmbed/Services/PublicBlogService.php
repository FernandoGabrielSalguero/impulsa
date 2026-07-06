<?php

namespace App\PublicEmbed\Services;

use App\Models\ApiIntegration;
use App\Services\PublicApi\LegacyPublicBlogService;

class PublicBlogService
{
    public function __construct(
        private readonly LegacyPublicBlogService $legacyBlogService,
    ) {}

    /** @return list<array<string, mixed>> */
    public function listPosts(ApiIntegration $integration): array
    {
        return $this->legacyBlogService->listPosts($integration);
    }

    /** @return array<string, mixed> */
    public function findBySlug(ApiIntegration $integration, string $slug): array
    {
        return $this->legacyBlogService->findBySlug($integration, $slug);
    }

    public function countActive(ApiIntegration $integration): int
    {
        return count($this->listPosts($integration));
    }
}
