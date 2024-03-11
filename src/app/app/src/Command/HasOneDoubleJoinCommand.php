<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select\JoinableLoader;
use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[Console\AsCommand(
    name: 'app:has-one-double-join',
    description: 'Fetch currencies rates from stock-markets'
)]
final class HasOneDoubleJoinCommand extends Command
{
    protected function perform(OutputInterface $output, ORMInterface $orm): void
    {
        $this->insertNew($orm);

        $result = $orm
            ->getRepository(User::class)
            ->select()
            ->load('passport')
            ->where('passport.id', null)
            ->fetchAll();

        $output->writeln(
            \sprintf('DOUBLE JOIN. we have %d users without passport', count($result))
        );

        $result = $orm
            ->getRepository(User::class)
            ->select()
            ->with('passport', ['method' => JoinableLoader::LEFT_JOIN])
            ->where('passport.id', null)
            ->fetchAll();

        $output->writeln(
            \sprintf('DOUBLE JOIN. we have %d users without passport', count($result))
        );
    }

    private function insertNew(ORMInterface $orm): void
    {
        $user = new User();
        $user->username = uniqid('test_username');
        (new EntityManager($orm))->persist($user)->run();
    }
}
