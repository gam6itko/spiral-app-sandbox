<?php

declare(strict_types=1);

return [
    'default' => env('STORAGE_SERVER', 'local'),

    'servers' => [
        'local' => [
            'adapter' => 'local',
            'directory' => __DIR__ . '/../../runtime/static',
        ],

        's3' => [
            'adapter' => 's3',
            'endpoint' => env('S3_ENDPOINT'),
            'region' => env('S3_REGION'),
            'bucket' => env('S3_BUCKET', 'sandbox'),
            'key' => env('S3_KEY'),
            'secret' => env('S3_SECRET'),
            'options' => [
                'use_path_style_endpoint' => true,
            ],
        ],
    ],

    'buckets' => [
        'local' => [
            'server' => 'local',
        ],

        's3' => [
            'server' => 's3',
        ],
    ],
];
