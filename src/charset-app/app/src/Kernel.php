<?php

declare(strict_types=1);

namespace App;

use Spiral\Boot\Bootloader\CoreBootloader;
use Spiral\Bootloader as Framework;
use Spiral\DotEnv\Bootloader as DotEnv;
use Spiral\Monolog\Bootloader as Monolog;
use Spiral\Tokenizer\Bootloader\TokenizerBootloader;
use Spiral\Cycle\Bootloader as CycleBridge;

class Kernel extends \Spiral\Framework\Kernel
{
    protected const SYSTEM = [
        CoreBootloader::class,
        TokenizerBootloader::class,
        DotEnv\DotenvBootloader::class,
    ];

    protected const LOAD = [
        Monolog\MonologBootloader::class,
        Framework\SnapshotsBootloader::class,
        Framework\I18nBootloader::class,
        CycleBridge\DatabaseBootloader::class,
        CycleBridge\MigrationsBootloader::class,
        CycleBridge\SchemaBootloader::class,
        CycleBridge\CycleOrmBootloader::class,
        CycleBridge\AnnotatedBootloader::class,
        Framework\CommandBootloader::class,
        CycleBridge\CommandBootloader::class,
    ];
}
