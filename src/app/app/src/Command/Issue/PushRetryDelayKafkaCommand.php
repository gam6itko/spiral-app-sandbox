<?php

declare(strict_types=1);

namespace App\Command\Issue;

use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;
use Spiral\Queue\Options;
use Spiral\Queue\QueueConnectionProviderInterface;

/**
 * Pushes a retry-delay job into kafka topic `sas.foo-bar`; it is consumed by app-consumer
 * (see AppConsumer\Job\RetryDelayJobHandler).
 */
#[Console\AsCommand(
    name: 'app:issue:retry-delay:kafka',
    description: 'Push retry-delay job into kafka (bug case)',
)]
final class PushRetryDelayKafkaCommand extends Command
{
    protected function perform(QueueConnectionProviderInterface $queues): int
    {
        $queues->getConnection('roadrunner')->push(
            'retry-delay',
            ['via' => 'kafka', 'sent_at' => \microtime(true)],
            Options::onQueue('kafka'),
        );
        $this->info('pushed to kafka');

        return self::SUCCESS;
    }
}
