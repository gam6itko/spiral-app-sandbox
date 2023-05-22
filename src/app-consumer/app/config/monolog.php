<?php

declare(strict_types=1);


use App\Jobs\KafkaJob;
use Monolog\Logger;
use Spiral\Monolog\Config\MonologConfig;

return [
    'default' => MonologConfig::DEFAULT_CHANNEL,
    'handlers' => [

        // jobs
        KafkaJob::class => [
            [
                'class' => 'log.rotate',
                'options' => [
                    'filename' => directory('runtime') . 'logs/kafka-job.log',
                    'level' => Logger::DEBUG,
                    'maxFiles' => 5,
                ],
            ]
        ],

    ],
];

