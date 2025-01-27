<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
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
                        'user' => new User('foo@mail.ru'),
                        'payload' => [
                            "bcWebsite" => "https://google.ru/",
                            "bcWebsiteTitle" => "google.com",
                            "spotName" => "Google",
                            "experienceShare" => [
                                "transactionId" => "574dab7c-6f64-4731-8d4b-ff157ed0cff7",
                                "text" => "текст претензии",
                            ],
                        ],
                    ],
                ),
            );
        }
    }
}
