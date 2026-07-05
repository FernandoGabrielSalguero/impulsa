<?php

namespace App\Services\PublicApi;

use App\Models\ApiIntegration;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PublicMetricsTrackingService
{
    public function recordPageVisit(ApiIntegration $integration, string $page): void
    {
        $page = trim($page);

        if ($page === '') {
            throw ValidationException::withMessages([
                'page' => ['La página visitada es obligatoria.'],
            ]);
        }

        DB::table('visit_user_page')->insert([
            'page' => mb_substr($page, 0, 150),
            'api_integration_id' => (int) $integration->id,
            'visited_at' => now(),
        ]);
    }

    public function recordContentView(
        ApiIntegration $integration,
        string $contentType,
        int $contentId,
        ?string $pageUrl,
        ?string $ipAddress,
    ): void {
        if (! in_array($contentType, ['blog_post', 'product'], true)) {
            throw ValidationException::withMessages([
                'content_type' => ['Tipo de contenido inválido.'],
            ]);
        }

        $this->assertContentBelongsToIntegration($integration, $contentType, $contentId);

        DB::table('api_content_views')->insert([
            'api_integration_id' => (int) $integration->id,
            'content_type' => $contentType,
            'content_id' => $contentId,
            'page_url' => $pageUrl !== null && trim($pageUrl) !== '' ? mb_substr(trim($pageUrl), 0, 500) : null,
            'ip_hash' => $this->hashIp($ipAddress),
            'created_at' => now(),
        ]);
    }

    private function assertContentBelongsToIntegration(ApiIntegration $integration, string $contentType, int $contentId): void
    {
        $table = $contentType === 'blog_post' ? 'api_blog_posts' : 'api_products';

        $exists = DB::table($table)
            ->where('api_integration_id', (int) $integration->id)
            ->where('id', $contentId)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'content_id' => ['El contenido no existe para esta integración.'],
            ]);
        }
    }

    private function hashIp(?string $ipAddress): ?string
    {
        $ipAddress = trim((string) $ipAddress);

        if ($ipAddress === '') {
            return null;
        }

        return hash('sha256', $ipAddress);
    }
}
