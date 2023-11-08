<?php

declare(strict_types=1);

namespace App\Command;

use App\Job\DummyJob;
use Cycle\ORM\FactoryInterface;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;
use Spiral\Queue\QueueManager;
use Symfony\Component\Console\Output\OutputInterface;

#[Console\AsCommand(
    name: 'app:push-dummy-job',
)]
final class PushDummyJobCommand extends Command
{
    #[Console\Option(name: 'queue')]
    private string $queueName = 'roadrunner';

    public function __construct(
        private readonly QueueManager $qm,
    )
    {
        parent::__construct();
    }

    protected function perform(OutputInterface $output, FactoryInterface $factory, EnvironmentInterface $env): void
    {
        $this->qm->getConnection($this->queueName)
            ->push(DummyJob::class);
    }

}
