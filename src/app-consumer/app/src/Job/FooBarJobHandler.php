<?php

declare(strict_types=1);

namespace AppConsumer\Job;

use Spiral\Queue\JobHandler;

final class FooBarJobHandler extends JobHandler
{
    public function invoke(array $payload, array $headers): void
    {
        dump([
            'payload' => $payload,
            'headers' => $headers,
        ]);
    }
}
