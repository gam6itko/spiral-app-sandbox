<?php

declare(strict_types=1);

namespace App\Job;

use Spiral\Queue\HandlerInterface;

/**
 * Another consumer group (`sas.observer`) of topic `sas.foo-bar`. It never fails, only logs every `retry-delay`
 * record it receives: each requeue made by app-consumer (group `sas.consumer`) shows up here as a new message.
 */
final class RetryDelayObserverJob implements HandlerInterface
{
    public function handle(string $name, string $id, mixed $payload, array $headers = []): void
    {
        \file_put_contents('php://stderr', \sprintf(
            "[retry-delay-observer] id=%s x-attempt=%s\n",
            $id,
            $headers['x-attempt'][0] ?? '-',
        ));
    }
}
