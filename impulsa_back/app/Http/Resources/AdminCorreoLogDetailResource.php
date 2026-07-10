<?php

namespace App\Http\Resources;

use App\Models\CorreoLog;
use Illuminate\Http\Request;

/** @mixin CorreoLog */
class AdminCorreoLogDetailResource extends AdminCorreoLogResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'mensaje_html' => $this->mensaje_html,
            'mensaje_text' => $this->mensaje_text,
            'meta' => $this->resolveMetaValue(),
            'contenido_legible' => $this->resolveReadableContent(),
            'meta_legible' => $this->resolveMetaReadable(),
        ]);
    }

    private function resolveReadableContent(): string
    {
        $text = trim((string) ($this->mensaje_text ?? ''));

        if ($text !== '') {
            return $text;
        }

        $html = trim((string) ($this->mensaje_html ?? ''));

        if ($html === '') {
            return 'No hay contenido disponible para este correo.';
        }

        $plain = trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        return $plain !== '' ? $plain : 'No hay contenido disponible para este correo.';
    }

    private function resolveMetaReadable(): string
    {
        $meta = $this->resolveMetaValue();

        if ($meta === null || $meta === []) {
            return 'Sin metadata adicional.';
        }

        $encoded = json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $encoded === false ? 'Sin metadata adicional.' : $encoded;
    }

    /** @return array<string, mixed>|null */
    private function resolveMetaValue(): ?array
    {
        $meta = $this->meta;

        if ($meta === null || $meta === '') {
            return null;
        }

        if (is_array($meta)) {
            return $meta;
        }

        if (is_string($meta)) {
            $decoded = json_decode($meta, true);

            return is_array($decoded) ? $decoded : null;
        }

        return null;
    }
}
