<?php

declare(strict_types=1);

namespace AppConsumer\Job;

use Spiral\Queue\HandlerInterface;

class FooBarJobHandler implements HandlerInterface
{
    public function handle(string $name, string $id, array $payload): void
    {
        dump($payload);
    }
}
