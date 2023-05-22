<?php

declare(strict_types=1);

namespace App\Command\Rr;

use App\Jobs\KafkaJob;
use Spiral\Console\Command;
use Spiral\Queue\QueueConnectionProviderInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class KafkaPushFloodCommand extends Command
{
    public const NAME = 'rr:kafka-push-flood';

    protected const ARGUMENTS = [
        [
            'count',
            InputArgument::OPTIONAL,
            'Produce message count',
            '1'
        ],
    ];

    protected function perform(InputInterface $input, OutputInterface $output, QueueConnectionProviderInterface $provider): void
    {
        $queue = $provider->getConnection('kafka');
        $count = (int)$input->getArgument('count');

        $progressBar = new ProgressBar($output, $count);
        $progressBar->start();

        for ($i = 0; $i < $count; $i++) {
            $progressBar->advance();
            $queue->push(
                'kafka-job',
                [
                    'i' => $i,
                    'ts' => time(),
                    'foo' => 'bar',
                    'sleep' => rand(1, 10)
                ],
            );
        }

        $progressBar->finish();
        $output->writeln('');
    }
}
