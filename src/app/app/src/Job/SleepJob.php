<?php

declare(strict_types=1);

namespace App\Job;

use Spiral\Queue\HandlerInterface;

final class SleepJob implements HandlerInterface
{
    public function handle(string $name, string $id, array $payload): void
    {
        \sleep($payload['seconds'] ?? 10);
    }
}
