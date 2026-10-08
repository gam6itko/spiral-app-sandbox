<?php

declare(strict_types=1);

namespace AppConsumer\Job;

use Spiral\Queue\Exception\RetryException;
use Spiral\Queue\HandlerInterface;
use Spiral\Queue\Options;

/**
 * Reproduces "kafka driver ignores the requeue delay".
 *
 * Every attempt throws RetryException with delay=RETRY_DELAY and logs how many seconds passed since the previous
 * attempt. Implements HandlerInterface directly: JobHandler would wrap the exception into JobException and the
 * bridge Dispatcher would not recognize it as RetryException.
 *
 * Expected: "since_prev" ~ RETRY_DELAY seconds for every retry.
 * memory pipeline: OK. kafka pipeline: retries arrive immediately (delay ignored).
 */
final class RetryDelayJobHandler implements HandlerInterface
{
    public const string NAME = 'retry-delay';
    private const int RETRY_DELAY = 10;
    private const int MAX_ATTEMPTS = 3;

    public function handle(string $name, string $id, mixed $payload, array $headers = []): void
    {
        $now = \microtime(true);
        $attempt = (int) ($headers['x-attempt'][0] ?? 0);
        $prevAt = (float) ($headers['x-prev-at'][0] ?? $payload['sent_at'] ?? $now);

        $this->log(\sprintf(
            'via=%s id=%s attempt=%d since_prev=%.2fs expected_delay=%ds',
            $payload['via'] ?? '?',
            $id,
            $attempt,
            $now - $prevAt,
            0 === $attempt ? 0 : self::RETRY_DELAY,
        ));

        if ($attempt >= self::MAX_ATTEMPTS) {
            $this->log(\sprintf('via=%s id=%s done', $payload['via'] ?? '?', $id));

            return;
        }

        throw new RetryException(
            'retry',
            (new Options())
                ->withDelay(self::RETRY_DELAY)
                ->withHeader('x-attempt', (string) ($attempt + 1))
                ->withHeader('x-prev-at', (string) $now),
        );
    }

    private function log(string $message): void
    {
        \file_put_contents('php://stderr', '[retry-delay] ' . $message . \PHP_EOL);
    }
}
