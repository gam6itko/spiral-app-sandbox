<?php

declare(strict_types=1);

namespace AppConsumer\Job;

use Psr\Log\LoggerInterface;
use Spiral\Queue\JobHandler;

class FooBarJobHandler extends JobHandler
{
    public function invoke(array $headers, LoggerInterface $logger): void
    {
        $logger->info('kafka foo-bar', $headers);
    }
}
