<?php

namespace App\PublicEmbed\Services;

use App\Models\ApiIntegration;
use App\Services\PublicApi\PublicMetricsTrackingService;

class PublicMetricsService
{
    public function __construct(
        private readonly PublicMetricsTrackingService $trackingService,
    ) {}

    public function recordPageVisit(ApiIntegration $integration, string $page): void
    {
        $this->trackingService->recordPageVisit($integration, $page);
    }

    public function recordContentView(
        ApiIntegration $integration,
        string $contentType,
        int $contentId,
        ?string $pageUrl,
        ?string $ipAddress,
    ): void {
        $this->trackingService->recordContentView(
            $integration,
            $contentType,
            $contentId,
            $pageUrl,
            $ipAddress,
        );
    }
}
