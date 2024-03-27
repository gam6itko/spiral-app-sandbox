<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\BanUser;
use App\Entity\User;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORMInterface;
use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;

#[Console\AsCommand(
    name: 'app:ban',
    description: 'Relations bug'
)]
class BanCommand extends Command
{
    protected function perform(ORMInterface $orm): void
    {
        $user = new User(uniqid('user_'));
        (new EntityManager($orm))->persist($user)->run();

        $muteUser = new BanUser($user);
        (new EntityManager($orm))->persist($muteUser)->run();
    }
}
