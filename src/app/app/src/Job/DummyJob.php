<?php

declare(strict_types=1);

namespace App\Job;

use Spiral\Queue\JobHandler;

class DummyJob extends JobHandler
{
    public function invoke(array $payload): void
    {
        // nothing to do
    }
}
