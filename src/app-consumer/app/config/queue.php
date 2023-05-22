<?php

declare(strict_types=1);

use Spiral\RoadRunner\Jobs\Queue\KafkaCreateInfo;
use Spiral\RoadRunnerBridge\Queue\Queue;

return [
    'default' => env('QUEUE_CONNECTION', 'roadrunner'),

    'connections' => [
        'roadrunner' => [
            'driver' => 'roadrunner',
            'default' => 'memory',
            'pipelines' => [
                'kafka_test' => [
                    'connector' => new KafkaCreateInfo(
                        name: 'kafka_test',
                        topic: 'kafka_test',
                    ),
                    'consume' => true,
                ],
            ],
        ],
    ],

    'driverAliases' => [
        'roadrunner' => Queue::class,
    ],

    'registry' => [
        'handlers' => [
            'kafka-job' => \App\Jobs\KafkaJob::class,
        ],
    ],
];
