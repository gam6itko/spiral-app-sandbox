<?php

declare(strict_types=1);

namespace App\Command\Rd;

use Spiral\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class KafkaPushCommand extends Command
{
    public const NAME = 'rd:kafka-push';

    protected const OPTIONS = [
        [
            'topic',
            't',
            InputOption::VALUE_REQUIRED,
            'topic',
            'my_topic_name'
        ],
        [
            'partition',
            'p',
            InputOption::VALUE_REQUIRED,
            'partition',
            0
        ],
        [
            'count',
            'c',
            InputOption::VALUE_REQUIRED,
            'Produce message count',
            1
        ],
    ];

    protected function perform(InputInterface $input, OutputInterface $output): void
    {
        $conf = new \RdKafka\Conf();
        $conf->set('metadata.broker.list', 'kafka'); //docker container name

        $rk = new \RdKafka\Producer($conf);
        $topic = $rk->newTopic($input->getOption('topic'));

        $count = (int)$input->getOption('count');

        for ($i = 0; $i < $count; $i++) {
            $payload = json_encode([
                'foo' => 'bar',
                'ts' => time(),
                'rand' => rand(1, 100)
            ]);
            $topic->produce($input->getOption('partition'), 0, $payload);

            $output->writeln(
                sprintf(
                    'PUSH. [%d] topic: %s. partition: %s. payload: %s',
                    $i,
                    $input->getOption('topic'),
                    $input->getOption('partition'),
                    $payload
                )
            );
        }

        $rk->flush(10_000);
    }
}
