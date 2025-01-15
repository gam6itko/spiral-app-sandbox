<?php

declare(strict_types=1);

namespace App\Command;

use Spiral\Console\Command;
use Spiral\Console\Attribute as Console;
use Spiral\Mailer\MailerInterface;
use Spiral\Mailer\Message;
use Symfony\Component\Console\Output\OutputInterface;

#[Console\AsCommand(
    name: 'app:send-mail',
)]
final class SendMailCommand extends Command
{
    protected function perform(MailerInterface $mailer): void
    {
        $mailer->send(new Message('hello.dark.php', 'trap@buggregator.com'));
    }
}
