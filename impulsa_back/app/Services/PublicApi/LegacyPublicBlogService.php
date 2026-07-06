<?php

namespace App\Services\PublicApi;

use App\Models\ApiIntegration;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LegacyPublicBlogService
{
    public function __construct(
        private readonly PublicMediaUrlBuilder $mediaUrlBuilder,
    ) {}

    /** @return list<array<string, mixed>> */
    public function listPosts(ApiIntegration $integration): array
    {
        $rows = DB::table('api_blog_posts')
            ->where('api_integration_id', (int) $integration->id)
            ->where('status', 'active')
            ->orderByDesc('publication_date')
            ->orderByDesc('sort_order')
            ->orderByDesc('id')
            ->get();

        return $rows->map(fn (object $row): array => $this->mapPost($row))->all();
    }

    /** @return array<string, mixed> */
    public function findBySlug(ApiIntegration $integration, string $slug): array
    {
        $slug = trim($slug);

        if ($slug === '') {
            throw ValidationException::withMessages([
                'slug' => ['El slug es obligatorio.'],
            ]);
        }

        $row = DB::table('api_blog_posts')
            ->where('api_integration_id', (int) $integration->id)
            ->where('status', 'active')
            ->where('slug', $slug)
            ->first();

        if ($row === null) {
            throw ValidationException::withMessages([
                'slug' => ['Artículo no encontrado.'],
            ]);
        }

        return $this->mapPost($row);
    }

    /** @return array<string, mixed> */
    private function mapPost(object $row): array
    {
        $coverUrl = $this->mediaUrlBuilder->url($row->cover_image_path ?? null);
        $attachmentUrl = $this->mediaUrlBuilder->url($row->attachment_path ?? null);

        return [
            'id' => (int) $row->id,
            'title' => (string) $row->title,
            'slug' => (string) $row->slug,
            'subtitle' => $row->subtitle,
            'author' => $row->author,
            'category' => $row->category,
            'subcategory' => $row->subcategory,
            'excerpt' => $row->excerpt,
            'description_html' => (string) $row->description_html,
            'content' => (string) $row->description_html,
            'publication_date' => $row->publication_date,
            'published_at' => $row->publication_date,
            'date' => $row->publication_date,
            'cover_image_path' => $row->cover_image_path,
            'cover_image_path_url' => $coverUrl,
            'cover_image_url' => $coverUrl,
            'image_url' => $coverUrl,
            'attachment_path' => $row->attachment_path,
            'attachment_path_url' => $attachmentUrl,
            'attachment_url' => $attachmentUrl,
        ];
    }
}
