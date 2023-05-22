<?php

declare(strict_types=1);

namespace App\Command\Rd;

use Spiral\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class KafkaPullCommand extends Command
{
    public const NAME = 'rd:kafka-pull';

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
            'group',
            'g',
            InputOption::VALUE_REQUIRED,
            'group',
            'my_group_id'
        ],
    ];

    protected function perform(InputInterface $input, OutputInterface $output): void
    {
        $partition = $input->getOption('partition');

        // configure
        $conf = new \RdKafka\Conf();
        $conf->set('group.id', $input->getOption('group'));
        $conf->set('metadata.broker.list', 'kafka'); //docker container name
        $conf->set('auto.offset.reset', 'earliest');
        $conf->set('enable.partition.eof', 'true');

        $consumer = new \RdKafka\Consumer($conf);

        $topic = $consumer->newTopic($input->getOption('topic'));

        $topic->consumeStart($partition, RD_KAFKA_OFFSET_STORED);
        $message = $topic->consume($partition, 10_000);
        $output->writeln(
            sprintf(
                "PULL. topic: %s. partition: %d. offset: %d. payload: %s",
                $input->getOption('topic'),
                $input->getOption('partition'),
                $message->offset,
                $message->payload
            )
        );
        $topic->consumeStop($partition);
    }
}
