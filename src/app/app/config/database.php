<?php

// https://github.com/spiral/cycle-bridge#configuration

declare(strict_types=1);

use Cycle\Database\Config\MySQLDriverConfig;
use Gam6itko\Cycle\Database\Config\NyholmDsnConnectionConfig;

return [
    'default' => env('DATABASE_DEFAULT_DRIVER', 'default'),

    'logger' => [
        'default' => null,
        'drivers' => [
            'runtime' => 'stdout'
        ],
    ],

    'databases' => [
        'default' => [
            'connection' => 'mysql.default',
        ],
    ],

    'connections' => [
        'mysql.default' => new MySQLDriverConfig(
            connection: new NyholmDsnConnectionConfig(env('DATABASE_DEFAULT_DSN')),
            queryCache: true,
            options: [
                'logQueryParameters' => env('DEBUG', false),
                'logInterpolatedQueries' => env('DEBUG', false),
            ]
        ),
    ],
];
