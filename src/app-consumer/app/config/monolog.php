<?php

declare(strict_types=1);

use Monolog\Logger;
use Spiral\Monolog\Config\MonologConfig;

return [
    'default' => MonologConfig::DEFAULT_CHANNEL,
    'handlers' => [
        'default' => [
            [
                'class' => 'log.rotate',
                'options' => [
                    'filename' => directory('runtime') . 'logs/app.log',
                    'level' => Logger::toMonologLevel(env('MONOLOG_DEFAULT_LEVEL', 'INFO')),
                    'maxFiles' => 5,
                    'bubble' => true,
                ],
            ],
        ],
    ],
];
