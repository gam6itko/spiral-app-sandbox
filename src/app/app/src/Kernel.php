<?php

declare(strict_types=1);

namespace App;

use Spiral\Boot\Bootloader\CoreBootloader;
use Spiral\Bootloader as Framework;
use Spiral\DotEnv\Bootloader as DotEnv;
use Spiral\League\Event\Bootloader\EventBootloader;
use Spiral\Monolog\Bootloader as Monolog;
use Spiral\Nyholm\Bootloader as Nyholm;
use Spiral\Profiler\ProfilerBootloader;
use Spiral\RoadRunnerBridge\Bootloader as RoadRunnerBridge;
use Spiral\Tokenizer\Bootloader\TokenizerBootloader;
use Spiral\Validation\Bootloader\ValidationBootloader;
use Spiral\Cycle\Bootloader as CycleBridge;
use Spiral\SendIt\Bootloader as SendIt;

class Kernel extends \Spiral\Framework\Kernel
{
    protected const SYSTEM = [
        CoreBootloader::class,
        TokenizerBootloader::class,
        DotEnv\DotenvBootloader::class,
    ];

    /*
     * List of components and extensions to be automatically registered
     * within system container on application start.
     */
    protected const LOAD = [

        // Logging and exceptions handling
        Monolog\MonologBootloader::class,

        // RoadRunner
        RoadRunnerBridge\HttpBootloader::class,
        RoadRunnerBridge\GRPCBootloader::class,
        RoadRunnerBridge\QueueBootloader::class,
        RoadRunnerBridge\CacheBootloader::class,
        RoadRunnerBridge\CommandBootloader::class,

        EventBootloader::class,

        // Core Services
        Framework\SnapshotsBootloader::class,
        Framework\I18nBootloader::class,

        // Security and validation
        Framework\Security\EncrypterBootloader::class,
        ValidationBootloader::class,
        Framework\Security\FiltersBootloader::class,
        Framework\Security\GuardBootloader::class,

        // HTTP extensions
        Nyholm\NyholmBootloader::class,
        Framework\Http\RouterBootloader::class,
        Framework\Http\JsonPayloadsBootloader::class,
        \Spiral\Router\Bootloader\AnnotatedRoutesBootloader::class,
//        Framework\Http\CookiesBootloader::class,
//        Framework\Http\SessionBootloader::class,
//        Framework\Http\CsrfBootloader::class,
//        Framework\Http\PaginationBootloader::class,

        Framework\Views\TranslatedCacheBootloader::class,

        // Databases
        CycleBridge\DatabaseBootloader::class,
        CycleBridge\MigrationsBootloader::class,

        // ORM
        CycleBridge\SchemaBootloader::class,
        CycleBridge\CycleOrmBootloader::class,
        CycleBridge\AnnotatedBootloader::class,

        // Framework commands
        Framework\CommandBootloader::class,
        CycleBridge\CommandBootloader::class,

        // Debug and debug extensions
        Framework\DebugBootloader::class,
        Framework\Debug\LogCollectorBootloader::class,
        Framework\Debug\HttpCollectorBootloader::class,

        SendIt\MailerBootloader::class,

        RoadRunnerBridge\CommandBootloader::class,

        ProfilerBootloader::class,

        \Spiral\OpenTelemetry\Bootloader\OpenTelemetryBootloader::class,

        \Spiral\Storage\Bootloader\StorageBootloader::class,
        \Spiral\Distribution\Bootloader\DistributionBootloader::class,
    ];
}
