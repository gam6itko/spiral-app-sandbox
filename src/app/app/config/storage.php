<?php

declare(strict_types=1);

return [
    'default' => env('STORAGE_SERVER', 'uploads'),

    'servers' => [
        'static' => [
            'adapter' => 'local',
            'directory' => __DIR__ . '/../../runtime/static',
        ],

        's3' => [
            'adapter' => 's3',
            'endpoint' => env('S3_ENDPOINT', 'http://minio:9000'),
            'region' => env('S3_REGION'),
            'bucket' => env('S3_BUCKET'),
            'key' => env('S3_KEY'),
            'secret' => env('S3_SECRET'),
        ],
    ],

    'buckets' => [
        'uploads' => [
            'server' => 'static',
            'prefix' => 'upload',
        ],

        'images' => [
            'server' => 'static',
            'prefix' => 'img',
        ],

        's3' => [
            'server' => 's3',
        ],
    ],
];
