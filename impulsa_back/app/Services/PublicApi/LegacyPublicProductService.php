<?php

namespace App\Services\PublicApi;

use App\Models\ApiIntegration;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LegacyPublicProductService
{
    public function __construct(
        private readonly PublicMediaUrlBuilder $mediaUrlBuilder,
    ) {}

    /** @return list<array<string, mixed>> */
    public function listProducts(ApiIntegration $integration): array
    {
        $rows = DB::table('api_products')
            ->where('api_integration_id', (int) $integration->id)
            ->where('status', 'active')
            ->orderByDesc('featured')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return $rows->map(fn (object $row): array => $this->mapProduct($row))->all();
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

        $row = DB::table('api_products')
            ->where('api_integration_id', (int) $integration->id)
            ->where('status', 'active')
            ->where('slug', $slug)
            ->first();

        if ($row === null) {
            throw ValidationException::withMessages([
                'slug' => ['Producto no encontrado.'],
            ]);
        }

        return $this->mapProduct($row);
    }

    /** @return array<string, mixed> */
    private function mapProduct(object $row): array
    {
        $mainUrl = $this->mediaUrlBuilder->url($row->main_image_path ?? null);
        $thumbUrl = $this->mediaUrlBuilder->url($row->thumbnail_path ?? null);

        return [
            'id' => (int) $row->id,
            'title' => (string) $row->title,
            'slug' => (string) $row->slug,
            'sku' => $row->sku,
            'short_description' => $row->short_description,
            'description_html' => (string) $row->description_html,
            'main_image_path' => $row->main_image_path,
            'main_image_path_url' => $mainUrl,
            'main_image_url' => $mainUrl,
            'thumbnail_path' => $row->thumbnail_path,
            'thumbnail_path_url' => $thumbUrl,
            'thumbnail_url' => $thumbUrl,
            'category' => $row->category,
            'subcategory' => $row->subcategory,
            'price' => $row->price !== null ? (float) $row->price : null,
            'compare_at_price' => $row->compare_at_price !== null ? (float) $row->compare_at_price : null,
            'currency' => (string) ($row->currency ?? 'ARS'),
            'stock_quantity' => $row->stock_quantity !== null ? (int) $row->stock_quantity : null,
            'availability' => (string) ($row->availability ?? 'on_request'),
            'featured' => (bool) ($row->featured ?? false),
        ];
    }
}
