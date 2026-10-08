<?php

declare(strict_types=1);

namespace AppConsumer\Command;

use AppConsumer\Job\RetryDelayJobHandler;
use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;
use Spiral\Queue\Options;
use Spiral\Queue\QueueConnectionProviderInterface;

/**
 * Control case: the same retry-delay job through the memory pipeline, where the delay is honored.
 */
#[Console\AsCommand(
    name: 'app:issue:retry-delay:local',
    description: 'Push retry-delay job into the memory pipeline (control case)',
)]
final class PushRetryDelayLocalCommand extends Command
{
    protected function perform(QueueConnectionProviderInterface $queues): int
    {
        $queues->getConnection('roadrunner')->push(
            RetryDelayJobHandler::NAME,
            ['via' => 'local', 'sent_at' => \microtime(true)],
            Options::onQueue('local'),
        );
        $this->info('pushed to local');

        return self::SUCCESS;
    }
}
