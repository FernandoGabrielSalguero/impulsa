<?php

return [

    'storage_path' => env('UPLOAD_ACADEMIA_PATH')
        ?: storage_path('app/academia'),

    'path_prefix' => env('UPLOAD_ACADEMIA_PREFIX', 'Academia'),

    'attachment' => [
        'extensions' => ['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx', 'zip'],
        'mime_types' => [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/zip',
        ],
        'max_bytes' => 10 * 1024 * 1024,
        'max_files' => 10,
    ],

];
