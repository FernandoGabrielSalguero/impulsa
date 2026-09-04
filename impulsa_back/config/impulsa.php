<?php

return [

    'frontend_url' => rtrim((string) env('FRONTEND_URL', 'http://localhost:4200'), '/'),

    /*
    | Subcarpeta del build Angular en producción (Hostinger: /impulsa_front/).
    | En local dejar vacío o no definir; localhost no usa este prefijo.
    */
    'frontend_app_path' => env('FRONTEND_APP_PATH', 'impulsa_front'),

    'public_api_base_url' => rtrim((string) env('PUBLIC_API_BASE_URL', env('APP_URL', 'http://localhost')), '/'),

    'public_storage_base_url' => rtrim((string) env('PUBLIC_STORAGE_BASE_URL', ''), '/'),

    /*
    | URL pública para imágenes de blog/productos.
    | - api: sirve por Laravel (/api/v1/public/media/...) — recomendado en Hostinger
    | - storage: usa PUBLIC_STORAGE_BASE_URL o /storage (requiere symlink/.htaccess)
    */
    'public_media_url_mode' => env('PUBLIC_MEDIA_URL_MODE', 'api'),

    'mail_from_name' => env('MAIL_FROM_NAME', 'Impulsa'),

    'hostinger_mail' => [
        'imap_host' => env('HOSTINGER_IMAP_HOST', 'imap.hostinger.com'),
        'imap_port' => (int) env('HOSTINGER_IMAP_PORT', 993),
        'imap_encryption' => env('HOSTINGER_IMAP_ENCRYPTION', 'ssl'),
        'smtp_host' => env('HOSTINGER_SMTP_HOST', 'smtp.hostinger.com'),
        'smtp_port' => (int) env('HOSTINGER_SMTP_PORT', 465),
        'smtp_encryption' => env('HOSTINGER_SMTP_ENCRYPTION', 'ssl'),
        'max_attachment_bytes' => (int) env('HOSTINGER_MAIL_MAX_ATTACHMENT_BYTES', 10 * 1024 * 1024),
    ],

    'verification_token_ttl_hours' => (int) env('EMAIL_VERIFICATION_TTL_HOURS', 24),

    'password_reset_token_ttl_minutes' => (int) env('PASSWORD_RESET_TTL_MINUTES', 60),

    /*
    | Feriados Argentina (YYYY-MM-DD) para calcular primer día hábil del mes.
    | Completar manualmente cada año.
    */
    'business_day_holidays' => array_values(array_filter(array_map(
        static fn (string $date): string => trim($date),
        explode(',', (string) env('IMPULSA_BUSINESS_HOLIDAYS', '')),
    ))),

];