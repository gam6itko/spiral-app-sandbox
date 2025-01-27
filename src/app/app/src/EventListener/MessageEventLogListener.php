<?php

declare(strict_types=1);

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Spiral\Events\Attribute\Listener;
use Spiral\SendIt\Event\MessageNotSent;
use Spiral\SendIt\Event\MessageSent;

final class MessageEventLogListener
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    #[Listener(event: MessageNotSent::class)]
    public function onMessageNotSent(MessageNotSent $event): void
    {
        $this->logger->error('Message send fail.', [
            'id' => $event->message->generateMessageId(),
            'message' => $event->message->toString(),
            'exception' => $event->exception,
        ]);
    }

    #[Listener(event: MessageSent::class)]
    public function onMessageSent(MessageSent $event): void
    {
        $this->logger->info('Message send success.', [
            'id' => $event->message->generateMessageId(),
            'message' => $event->message->toString(),
        ]);
    }
}
