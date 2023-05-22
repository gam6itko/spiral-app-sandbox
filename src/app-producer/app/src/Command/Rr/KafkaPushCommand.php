<?php

declare(strict_types=1);

namespace App\Command\Rr;

use App\Jobs\KafkaJob;
use Spiral\Console\Command;
use Spiral\Queue\QueueConnectionProviderInterface;

class KafkaPushCommand extends Command
{
    public const NAME = 'rr:kafka-push';

    protected function perform(QueueConnectionProviderInterface $provider): void
    {
        $queue = $provider->getConnection('kafka');
        $queue->push(
            'kafka-job',
            [
                'i' => 0,
                'ts' => time(),
                'foo' => 'bar',
                'sleep' => rand(1, 10)
            ],
        );
    }
}
