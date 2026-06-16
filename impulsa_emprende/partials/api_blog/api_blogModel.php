<?php

declare(strict_types=1);

require_once __DIR__ . '/../api_content/api_content_shared_model.php';

final class ApiBlogModel extends ApiContentSharedModel
{
    protected function obtenerConfiguracionModulo(): array
    {
        return [
            'module' => 'blog',
            'table' => 'api_blog_posts',
            'uses_publication_date' => true,
            'file_fields' => $this->buildBlogFileFields(),
            'path_columns' => ['cover_image_path', 'attachment_path'],
        ];
    }
}
