<?php

declare(strict_types=1);

namespace App\Jobs;

use Spiral\Logger\Traits\LoggerTrait;
use Spiral\Queue\JobHandler;

class KafkaJob extends JobHandler
{
    use LoggerTrait;

    public function invoke(string $id, array $payload): void
    {
        $time = microtime(true);
        echo "!!! start: $id. {$payload['i']}. payload: " . json_encode($payload);
        $this->getLogger()->info(KafkaJob::class, $payload);
        sleep($payload['sleep'] ?? 1);
        echo "!!! stop : $id. {$payload['i']}. time: " . (microtime(true) - $time);
    }
}
