<?php

declare(strict_types=1);

return [
    'default' => env('DISTRIBUTION_RESOLVER', 'local'),

    'resolvers' => [
        'local' => [
            'type' => 'static',
            'uri'  => env('APP_URL', 'http://localhost:10101')
        ],

        's3' => [
            'type' => 's3',
            'endpoint' => env('S3_DISTRIBUTION_URL', 'http://localhost:9000'),
            'region' => env('S3_REGION'),
            'bucket' => env('S3_BUCKET'),
            'key' => env('S3_KEY'),
            'secret' => env('S3_SECRET'),
            'options' => [
                'use_path_style_endpoint' => true,
            ],
        ],
    ],
];
