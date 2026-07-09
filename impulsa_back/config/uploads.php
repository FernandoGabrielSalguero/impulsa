<?php

/*
|--------------------------------------------------------------------------
| Rutas de archivos subidos (Impulsa)
|--------------------------------------------------------------------------
|
| storage_path  → carpeta absoluta en disco donde se guardan los archivos.
| path_prefix   → prefijo relativo que se persiste en la base de datos.
|
| Dejá vacío en .env para usar el default de Laravel (storage/app/...).
| Podés usar rutas absolutas, ej: D:/impulsa_uploads/productos
|
| Variables: UPLOAD_* en .env (ver .env.example).
|
*/

return [

    'api_product' => [
        'label' => 'Productos API',
        'description' => 'Imagen principal, miniatura y adjunto de api_products.',
        'storage_path' => env('UPLOAD_API_PRODUCT_PATH')
            ?: env('API_PRODUCT_STORAGE_PATH')
            ?: storage_path('app/api_product'),
        'path_prefix' => env('UPLOAD_API_PRODUCT_PREFIX')
            ?: env('API_PRODUCT_PATH_PREFIX')
            ?: 'API_Product',
    ],

    'api_blog' => [
        'label' => 'Blog API',
        'description' => 'Portada y adjunto de api_blog_posts.',
        'storage_path' => env('UPLOAD_API_BLOG_PATH')
            ?: env('API_BLOG_STORAGE_PATH')
            ?: storage_path('app/api_blog'),
        'path_prefix' => env('UPLOAD_API_BLOG_PREFIX')
            ?: env('API_BLOG_PATH_PREFIX')
            ?: 'API_Blog',
    ],

    'chatbot_avatar' => [
        'label' => 'Avatar chatbot',
        'description' => 'Imagen de avatar del chatbot (panel emprendedor).',
        'storage_path' => env('UPLOAD_CHATBOT_AVATAR_PATH')
            ?: storage_path('app/chatbot-avatars'),
        'path_prefix' => env('UPLOAD_CHATBOT_AVATAR_PREFIX', 'chatbot-avatars'),
    ],

    /*
    | Hostinger: UPLOAD_CHATBOT_AVATAR_PATH=/home/.../storage/chatbot-avatars
    | Crear la carpeta con permisos de escritura para PHP (775).
    */

    'user_avatar' => [
        'label' => 'Avatar usuario',
        'description' => 'Foto de perfil en user_info.avatar_path (futuro).',
        'storage_path' => env('UPLOAD_USER_AVATAR_PATH')
            ?: storage_path('app/user-avatars'),
        'path_prefix' => env('UPLOAD_USER_AVATAR_PREFIX', 'user-avatars'),
    ],

    'marketing_import' => [
        'label' => 'Importaciones marketing',
        'description' => 'Archivos Excel/CSV de marketing_import_batches (futuro).',
        'storage_path' => env('UPLOAD_MARKETING_IMPORT_PATH')
            ?: storage_path('app/marketing-imports'),
        'path_prefix' => env('UPLOAD_MARKETING_IMPORT_PREFIX', 'marketing-imports'),
    ],

    'project_deliverable' => [
        'label' => 'Entregables de proyecto',
        'description' => 'Documentos y archivos de project_deliverables (futuro).',
        'storage_path' => env('UPLOAD_PROJECT_DELIVERABLE_PATH')
            ?: storage_path('app/project-deliverables'),
        'path_prefix' => env('UPLOAD_PROJECT_DELIVERABLE_PREFIX', 'project-deliverables'),
    ],

    'project_contract' => [
        'label' => 'Contratos de proyecto',
        'description' => 'PDFs u otros adjuntos de contratos firmados (futuro).',
        'storage_path' => env('UPLOAD_PROJECT_CONTRACT_PATH')
            ?: storage_path('app/project-contracts'),
        'path_prefix' => env('UPLOAD_PROJECT_CONTRACT_PREFIX', 'project-contracts'),
    ],

    'academia' => [
        'label' => 'Academia',
        'description' => 'Adjuntos de videos de Academia.',
        'storage_path' => env('UPLOAD_ACADEMIA_PATH')
            ?: storage_path('app/academia'),
        'path_prefix' => env('UPLOAD_ACADEMIA_PREFIX', 'Academia'),
    ],

];
