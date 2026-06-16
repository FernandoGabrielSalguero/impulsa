<?php

declare(strict_types=1);

require_once __DIR__ . '/../api_content/api_content_shared_model.php';

final class ApiProductoModel extends ApiContentSharedModel
{
    protected function obtenerConfiguracionModulo(): array
    {
        return [
            'module' => 'product',
            'table' => 'api_products',
            'uses_publication_date' => false,
            'file_fields' => $this->buildProductFileFields(),
            'path_columns' => ['main_image_path', 'thumbnail_path', 'attachment_path'],
        ];
    }
}
