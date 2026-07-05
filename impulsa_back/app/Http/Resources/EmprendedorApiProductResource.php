<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class EmprendedorApiProductResource extends AdminApiProductResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $productId = (int) ($data['id'] ?? 0);

        foreach (['main', 'thumbnail', 'attachment'] as $type) {
            $pathKey = $type === 'main' ? 'main_image_path' : ($type === 'thumbnail' ? 'thumbnail_path' : 'attachment_path');
            $urlKey = $type === 'main' ? 'main_image_url' : ($type === 'thumbnail' ? 'thumbnail_url' : 'attachment_url');

            if (trim((string) ($data[$pathKey] ?? '')) !== '') {
                $data[$urlKey] = url("/api/v1/emprendedor/products/{$productId}/media/{$type}");
            }
        }

        return $data;
    }
}
