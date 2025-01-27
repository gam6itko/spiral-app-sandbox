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

    #[Console\Option(name: 'count')]
    private int $count = 1;

    #[Console\Option(name: 'template')]
    private string $template = 'hello.dark.php';

    protected function perform(MailerInterface $mailer): void
    {
        $letters = implode('', range('A', 'Z')) . '- ';//30

        for ($i = 0; $i < $this->count; $i++) {
            $mailer->send(
                new Message(
                    subject: $this->template,
                    to: [
                        'User1 <gam6itko@mail.ru>',
                        'User2 <gam6itko@gmail.com>',
                        'User3 <gam6itko@yandex.ru>',
                    ],
                    data: [
                        'subject' => \str_repeat($letters, 5),
                    ],
                ),
            );
        }
    }
}
