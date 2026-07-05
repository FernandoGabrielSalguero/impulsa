<?php

namespace App\Services\ApiProduct;

use App\Support\ApiProductLabels;
use Illuminate\Validation\ValidationException;

class ApiProductNormalizer
{
    /** @param array<string, mixed>|null $existing */
    public function normalizePayload(array $payload, ?array $existing = null): array
    {
        $title = $this->normalizeText($payload['title'] ?? null, required: true, maxLength: 180);
        $slugSource = $this->normalizeText($payload['slug'] ?? null, required: false, maxLength: 220) ?? $title;
        $slug = $this->slugify((string) $slugSource);

        if ($slug === '') {
            throw ValidationException::withMessages([
                'slug' => ['No se pudo generar un slug válido para este producto.'],
            ]);
        }

        $descriptionHtml = $this->sanitizeHtml(
            $this->normalizeText($payload['description_html'] ?? null, required: true, maxLength: null),
        );

        if ($descriptionHtml === '') {
            throw ValidationException::withMessages([
                'description_html' => ['La descripción no puede quedar vacía.'],
            ]);
        }

        return [
            'title' => $title,
            'slug' => $slug,
            'sku' => $this->normalizeText($payload['sku'] ?? ($existing['sku'] ?? null), required: false, maxLength: 80),
            'short_description' => $this->normalizeText(
                $payload['short_description'] ?? ($existing['short_description'] ?? null),
                required: false,
                maxLength: 300,
            ),
            'description_html' => $descriptionHtml,
            'category' => $this->normalizeText($payload['category'] ?? ($existing['category'] ?? null), required: false, maxLength: 120),
            'subcategory' => $this->normalizeText($payload['subcategory'] ?? ($existing['subcategory'] ?? null), required: false, maxLength: 120),
            'price' => $this->normalizeDecimal($payload['price'] ?? ($existing['price'] ?? null)),
            'compare_at_price' => $this->normalizeDecimal($payload['compare_at_price'] ?? ($existing['compare_at_price'] ?? null)),
            'currency' => $this->normalizeText($payload['currency'] ?? ($existing['currency'] ?? 'ARS'), required: true, maxLength: 8) ?? 'ARS',
            'stock_quantity' => $this->normalizeIntegerNullable($payload['stock_quantity'] ?? ($existing['stock_quantity'] ?? null), min: 0),
            'availability' => $this->normalizeAvailability($payload['availability'] ?? ($existing['availability'] ?? 'on_request')),
            'status' => $this->normalizeStatus($payload['status'] ?? ($existing['status'] ?? 'draft')),
            'featured' => $this->normalizeBoolean($payload['featured'] ?? ($existing['featured'] ?? false)),
            'sort_order' => $this->normalizeInteger($payload['sort_order'] ?? ($existing['sort_order'] ?? 1), min: 1, max: 999999),
            'metadata_json' => $this->normalizeMetadata($payload['metadata_json'] ?? ($existing['metadata_json'] ?? null)),
        ];
    }

    public function normalizeStatus(mixed $value): string
    {
        $status = trim((string) $value);

        if (! in_array($status, ApiProductLabels::statuses(), true)) {
            throw ValidationException::withMessages([
                'status' => ['El estado indicado no es válido.'],
            ]);
        }

        return $status;
    }

    public function slugify(string $text): string
    {
        $text = trim($text);
        $translit = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = is_string($translit) ? $translit : $text;
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
        $text = trim($text, '-');

        return substr($text, 0, 220);
    }

    private function normalizeAvailability(mixed $value): string
    {
        $availability = trim((string) $value);

        if (! in_array($availability, ApiProductLabels::availabilities(), true)) {
            throw ValidationException::withMessages([
                'availability' => ['La disponibilidad seleccionada no es válida.'],
            ]);
        }

        return $availability;
    }

    private function normalizeText(mixed $value, bool $required, ?int $maxLength): ?string
    {
        if ($value === null || $value === '') {
            if ($required) {
                throw ValidationException::withMessages([
                    'title' => ['Falta un campo obligatorio.'],
                ]);
            }

            return null;
        }

        if (! is_scalar($value)) {
            throw ValidationException::withMessages([
                'title' => ['Se recibió un valor inválido.'],
            ]);
        }

        $text = trim((string) $value);

        if ($text === '') {
            if ($required) {
                throw ValidationException::withMessages([
                    'title' => ['Falta un campo obligatorio.'],
                ]);
            }

            return null;
        }

        if ($maxLength !== null && mb_strlen($text) > $maxLength) {
            throw ValidationException::withMessages([
                'title' => ['Uno de los campos supera la longitud permitida.'],
            ]);
        }

        return $text;
    }

    private function normalizeDecimal(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (! is_numeric($value)) {
            throw ValidationException::withMessages([
                'price' => ['El precio indicado no es válido.'],
            ]);
        }

        return number_format((float) $value, 2, '.', '');
    }

    private function normalizeIntegerNullable(mixed $value, int $min): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (! is_numeric($value)) {
            throw ValidationException::withMessages([
                'stock_quantity' => ['El stock indicado no es válido.'],
            ]);
        }

        $intValue = (int) $value;

        if ($intValue < $min) {
            throw ValidationException::withMessages([
                'stock_quantity' => ['El stock no puede ser negativo.'],
            ]);
        }

        return $intValue;
    }

    private function normalizeInteger(mixed $value, int $min, int $max): int
    {
        if (! is_numeric($value)) {
            throw ValidationException::withMessages([
                'sort_order' => ['El orden indicado no es válido.'],
            ]);
        }

        $intValue = (int) $value;

        if ($intValue < $min || $intValue > $max) {
            throw ValidationException::withMessages([
                'sort_order' => ['El orden debe estar entre 1 y 999999.'],
            ]);
        }

        return $intValue;
    }

    private function normalizeBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value === 1;
        }

        $normalized = strtolower(trim((string) $value));

        return in_array($normalized, ['1', 'true', 'yes', 'on', 'si', 'sí'], true);
    }

    private function normalizeMetadata(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_array($value)) {
            $encoded = json_encode($value, JSON_UNESCAPED_UNICODE);

            return is_string($encoded) ? $encoded : null;
        }

        $text = trim((string) $value);

        if ($text === '') {
            return null;
        }

        json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages([
                'metadata_json' => ['El metadata JSON no es válido.'],
            ]);
        }

        return $text;
    }

    private function sanitizeHtml(?string $html): string
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
}
