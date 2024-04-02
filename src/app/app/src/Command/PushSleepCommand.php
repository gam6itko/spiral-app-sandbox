<?php

declare(strict_types=1);

namespace App\Command;

use App\Job\SleepJob;
use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;
use Spiral\Queue\QueueManager;
use Symfony\Component\Console\Output\OutputInterface;

#[Console\AsCommand(
    name: 'app:push-sleep',
)]
class PushSleepCommand extends Command
{
    #[Console\Option(name: 'queue')]
    private string $queueName = 'roadrunner';

    #[Console\Option(name: 'count')]
    private int $count = 1000;

    #[Console\Option(name: 'seconds')]
    private int $seconds = 10;

    public function __construct(
        private readonly QueueManager $qm,
    )
    {
        parent::__construct();
    }

    protected function perform(OutputInterface $output): void
    {
        \assert($this->count > 0);
        \assert($this->seconds > 0);

        for ($i = 0; $i < $this->count; $i++) {
            $this->qm->getConnection($this->queueName)
                ->push(SleepJob::class, ['seconds' => $this->seconds]);
        }

        $output->writeln(sprintf("Push %d jobs to queue %s", $this->count, $this->queueName));
    }
}
