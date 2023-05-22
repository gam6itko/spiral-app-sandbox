<?php

declare(strict_types=1);

use App\Queue\Driver\KafkaDriver;
use Spiral\RoadRunner\Jobs\Queue\KafkaCreateInfo;
use Spiral\RoadRunnerBridge\Queue\Queue;

return [
    'default' => env('QUEUE_CONNECTION', 'roadrunner'),

    'connections' => [
        'roadrunner' => [
            'driver' => 'roadrunner',
            'default' => 'kafka',
            'pipelines' => [
                'kafka_test' => [
                    'connector' => new KafkaCreateInfo(
                        name: 'kafka_test',
                        topic: 'kafka_test',
                    ),
                    'consume' => false,
                ],
            ],
        ],

        'kafka' => [
            'driver' => 'kafka',
            'pipeline' => 'kafka_test',
            'topic' => 'kafka_test',
        ],
    ],

    'driverAliases' => [
        'roadrunner' => Queue::class,
        'kafka' => KafkaDriver::class,
    ],
];
