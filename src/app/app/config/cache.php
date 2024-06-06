<?php

declare(strict_types=1);

use Spiral\Cache\Storage\ArrayStorage;
use Spiral\Cache\Storage\FileStorage;

return [
    'default' => env('CACHE_STORAGE', 'local'),

    'aliases' => [
        'local' => 'rr.local',
    ],

    'storages' => [
        'array' => [
            'type' => 'array',
        ],

        'rr.local' => [
            'type' => 'roadrunner',
            'driver' => 'local',
        ],
    ],

    'typeAliases' => [
        'array' => ArrayStorage::class,
        'file' => FileStorage::class,
    ],
];
