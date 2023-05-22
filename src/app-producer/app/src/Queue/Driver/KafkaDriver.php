<?php

declare(strict_types=1);

namespace App\Queue\Driver;

use Spiral\Queue\OptionsInterface;
use Spiral\Queue\QueueInterface;
use Spiral\RoadRunner\Jobs\JobsInterface;
use Spiral\RoadRunner\Jobs\KafkaOptions;

class KafkaDriver implements QueueInterface
{
    public function __construct(
        private readonly JobsInterface $jobs,
        private readonly string $pipeline,
        private readonly string $topic
    ) {
    }

    public function push(string $name, array $payload = [], OptionsInterface $options = null): string
    {
        $queue = $this->jobs->connect($this->pipeline, new KafkaOptions($this->topic));

        return $queue->push($name, $payload)->getId();
    }
}
