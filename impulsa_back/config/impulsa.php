<?php

return [

    'frontend_url' => rtrim((string) env('FRONTEND_URL', 'http://localhost:4200'), '/'),

    'public_api_base_url' => rtrim((string) env('PUBLIC_API_BASE_URL', env('APP_URL', 'http://localhost')), '/'),

    'public_storage_base_url' => rtrim((string) env('PUBLIC_STORAGE_BASE_URL', ''), '/'),

    /*
    | URL pública para imágenes de blog/productos.
    | - api: sirve por Laravel (/api/v1/public/media/...) — recomendado en Hostinger
    | - storage: usa PUBLIC_STORAGE_BASE_URL o /storage (requiere symlink/.htaccess)
    */
    'public_media_url_mode' => env('PUBLIC_MEDIA_URL_MODE', 'api'),

    'mail_from_name' => env('MAIL_FROM_NAME', 'Impulsa'),

    'verification_token_ttl_hours' => (int) env('EMAIL_VERIFICATION_TTL_HOURS', 24),

    /*
    | Feriados Argentina (YYYY-MM-DD) para calcular primer día hábil del mes.
    | Completar manualmente cada año.
    */
    'business_day_holidays' => array_values(array_filter(array_map(
        static fn (string $date): string => trim($date),
        explode(',', (string) env('IMPULSA_BUSINESS_HOLIDAYS', '')),
    ))),

];