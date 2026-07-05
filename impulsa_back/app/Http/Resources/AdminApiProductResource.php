<?php

namespace App\Http\Resources;

use App\Support\ApiProductLabels;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminApiProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $productId = (int) $this->value('id');

        return [
            'id' => $productId,
            'api_integration_id' => (int) $this->value('api_integration_id'),
            'project_name' => $this->value('project_name'),
            'allowed_domain' => $this->value('allowed_domain'),
            'owner_name' => $this->resolveOwnerName(),
            'owner_email' => $this->resolveOwnerEmail(),
            'title' => (string) $this->value('title'),
            'slug' => (string) $this->value('slug'),
            'sku' => $this->value('sku'),
            'short_description' => $this->value('short_description'),
            'description_html' => (string) $this->value('description_html'),
            'main_image_path' => $this->value('main_image_path'),
            'main_image_url' => $this->mediaUrl($productId, 'main', $this->value('main_image_path')),
            'thumbnail_path' => $this->value('thumbnail_path'),
            'thumbnail_url' => $this->mediaUrl($productId, 'thumbnail', $this->value('thumbnail_path')),
            'attachment_path' => $this->value('attachment_path'),
            'attachment_url' => $this->mediaUrl($productId, 'attachment', $this->value('attachment_path')),
            'category' => $this->value('category'),
            'subcategory' => $this->value('subcategory'),
            'price' => $this->value('price') !== null ? (float) $this->value('price') : null,
            'compare_at_price' => $this->value('compare_at_price') !== null
                ? (float) $this->value('compare_at_price')
                : null,
            'currency' => (string) ($this->value('currency') ?? 'ARS'),
            'stock_quantity' => $this->value('stock_quantity') !== null
                ? (int) $this->value('stock_quantity')
                : null,
            'availability' => (string) ($this->value('availability') ?? 'on_request'),
            'availability_label' => ApiProductLabels::availabilityLabel((string) ($this->value('availability') ?? 'on_request')),
            'status' => (string) ($this->value('status') ?? 'draft'),
            'status_label' => ApiProductLabels::statusLabel((string) ($this->value('status') ?? 'draft')),
            'featured' => (bool) $this->value('featured', false),
            'sort_order' => (int) ($this->value('sort_order') ?? 1),
            'metadata_json' => $this->value('metadata_json'),
            'created_by_user_id' => $this->value('created_by_user_id')
                ? (int) $this->value('created_by_user_id')
                : null,
            'created_at' => $this->formatDate($this->value('created_at')),
            'updated_at' => $this->formatDate($this->value('updated_at')),
        ];
    }

    private function mediaUrl(int $productId, string $type, mixed $storedPath): ?string
    {
        if ($productId <= 0 || trim((string) $storedPath) === '') {
            return null;
        }

        return url("/api/v1/admin/api-products/{$productId}/media/{$type}");
    }

    private function resolveOwnerName(): string
    {
        $nombre = trim((string) $this->value('owner_nombre') . ' ' . (string) $this->value('owner_apellido'));
        $apodo = trim((string) $this->value('owner_apodo'));
        $correo = $this->resolveOwnerEmail();

        if ($nombre !== '') {
            return $nombre;
        }

        if ($apodo !== '') {
            return $apodo;
        }

        return $correo !== '' ? $correo : 'Usuario sin nombre';
    }

    private function resolveOwnerEmail(): string
    {
        $contacto = trim((string) $this->value('owner_contacto_correo'));

        if ($contacto !== '') {
            return $contacto;
        }

        return trim((string) $this->value('owner_auth_correo'));
    }

    private function value(string $key, mixed $default = null): mixed
    {
        $resource = $this->resource;

        if (is_object($resource) && property_exists($resource, $key)) {
            return $resource->{$key} ?? $default;
        }

        if (is_array($resource) && array_key_exists($key, $resource)) {
            return $resource[$key];
        }

        return $default;
    }

    private function formatDate(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        try {
            return Carbon::parse((string) $value)->toISOString();
        } catch (\Throwable) {
            return null;
        }
    }
}
