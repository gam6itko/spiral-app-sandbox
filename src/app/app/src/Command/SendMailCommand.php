<?php

declare(strict_types=1);

namespace App\Command;

use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;
use Spiral\Mailer\MailerInterface;
use Spiral\Mailer\Message;

#[Console\AsCommand(
    name: 'app:send-mail',
)]
final class SendMailCommand extends Command
{
    protected function perform(MailerInterface $mailer): void
    {
        $mailer->send(
            new Message(
                subject: 'hello.dark.php',
                to: [
                    'User1 <gam6itko@mail.ru>',
                    'User2 <gam6itko@gmail.com>',
                    'User3 <gam6itko@yandex.ru>',
                ],
            ),
        );
    }
}
