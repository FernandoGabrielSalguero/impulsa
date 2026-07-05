<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Registro de uso de IA
    |--------------------------------------------------------------------------
    |
    | Cada llamada a un proveedor (DeepSeek, etc.) puede registrarse en la tabla
    | ai_usage_logs con usuario, feature y métricas de tokens.
    |
    */
    'usage_logging' => [
        'enabled' => env('AI_USAGE_LOGGING_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Features conocidas (referencia para el equipo)
    |--------------------------------------------------------------------------
    |
    | No es obligatorio listar acá las features; sirve como catálogo documentado.
    | Al implementar IA nueva, usá AiUsageContext con un slug descriptivo.
    |
    */
    'features' => [
        'emprendedor.definicion.mision' => 'Generación de misión (DeepSeek)',
        'emprendedor.definicion.vision' => 'Generación de visión (DeepSeek)',
        'emprendedor.definicion.buyer_persona' => 'Generación de buyer persona (DeepSeek)',
    ],

];
