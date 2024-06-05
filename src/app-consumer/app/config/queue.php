<?php

declare(strict_types=1);

use Spiral\RoadRunner\Jobs\Queue\MemoryCreateInfo;

return [
    /**
     *  Default queue connection name
     */
    'default' => env('QUEUE_CONNECTION', 'roadrunner'),

    /**
     *  Aliases for queue connections, if you want to use domain specific queues
     */
    'aliases' => [
        'queueRoadrunner' => 'roadrunner',
        'queueSync' => 'sync',
    ],

    /**
     * Queue connections
     * Drivers: "sync", "roadrunner"
     */
    'connections' => [
        'sync' => [
            // Job will be handled immediately without queueing
            'driver' => 'sync',
        ],
        'roadrunner' => [
            'driver' => 'roadrunner',
            'default' => 'local',
        ],
        'roadrunner_a' => [
            'driver' => 'roadrunner',
            'default' => 'queue_a',
        ],
    ],

    'pipelines' => [
        'local' => [
            'connector' => new MemoryCreateInfo('local'),
            'consume' => true,
        ],
        'queue_a' => [
            'connector' => new MemoryCreateInfo('queue_a', 64),
            'consume' => true,
        ],
    ],

    'driverAliases' => [
        'sync' => \Spiral\Queue\Driver\SyncDriver::class,
        'roadrunner' => \Spiral\RoadRunnerBridge\Queue\Queue::class,
    ],
];
