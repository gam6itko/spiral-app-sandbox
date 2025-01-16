<?php

declare(strict_types=1);

use Monolog\Logger;

return [
    'globalLevel' => Logger::toMonologLevel(env('MONOLOG_DEFAULT_LEVEL', 'INFO')),
    'handlers' => [
        'mailer' => [
            [
                'class' => 'log.rotate',
                'options' => [
                    'filename' => directory('runtime') . 'logs/mailer.log',
                    'level' => Logger::toMonologLevel(env('MONOLOG_DEFAULT_LEVEL', 'INFO')),
                    'maxFiles' => 5,
                ],
            ],
        ],
    ],
];
