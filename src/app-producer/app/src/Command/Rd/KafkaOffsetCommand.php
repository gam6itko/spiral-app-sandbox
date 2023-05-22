<?php

declare(strict_types=1);

namespace App\Command\Rd;

use Spiral\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class KafkaOffsetCommand extends Command
{

    public const NAME = 'rd:kafka-offset';

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
        $topicName = $input->getOption('topic');
        $partition = $input->getOption('partition');
        // configure
        $conf = new \RdKafka\Conf();
        $conf->set('group.id', $input->getOption('group'));
        $conf->set('metadata.broker.list', 'kafka'); //docker container name
        $conf->set('auto.offset.reset', 'earliest');
        $conf->set('enable.partition.eof', 'true');
        $conf->set('enable.auto.offset.store', 'false');

        $consumer = new \RdKafka\Consumer($conf);

        $consumer->queryWatermarkOffsets($topicName, $partition, $low, $high, 10_000);
        $topic = $consumer->newTopic($topicName);

        // retrieve stored offset
        $topic->consumeStart($partition, RD_KAFKA_OFFSET_STORED);
        $currentMessage = $topic->consume($partition, 10_000);
        $offset = $currentMessage->offset;
        unset($currentMessage);
        printf(
            "[%s:%d]. offset: %d. low: %d high: %d. behind: %d\n",
            $topicName,
            $partition,
            $offset,
            $low,
            $high,
            $high - $offset
        );
        $topic->consumeStop($partition);
    }
}
