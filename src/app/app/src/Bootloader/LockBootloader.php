<?php

declare(strict_types=1);

namespace App\Bootloader;

use RoadRunner\Lock as RR;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\RoadRunner\Symfony\Lock\RoadRunnerStore;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\PersistingStoreInterface;
use Symfony\Component\Lock\SharedLockStoreInterface;

final class LockBootloader extends Bootloader
{
    #[\Override]
    public function defineSingletons(): array
    {
        return [
            RR\LockInterface::class => RR\Lock::class,
            LockFactory::class => LockFactory::class,
            PersistingStoreInterface::class => SharedLockStoreInterface::class,
            RoadRunnerStore::class => static fn(RR\LockInterface $rrLock) => new RoadRunnerStore($rrLock),
            SharedLockStoreInterface::class => RoadRunnerStore::class,
        ];
    }
}
