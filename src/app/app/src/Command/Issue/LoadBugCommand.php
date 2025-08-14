<?php

declare(strict_types=1);

namespace App\Command\Issue;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\User;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select\Repository;
use Psr\Log\LoggerInterface;
use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;

#[Console\AsCommand(
    name: 'app:issue:load-bug',
)]
class LoadBugCommand extends Command
{
    public function __construct(
        private readonly ORMInterface $orm,
        private readonly LoggerInterface $logger,
    )
    {
        parent::__construct();
    }

    /**
     * Must throw:
     *
     *     Unable to parse incoming row: array_combine(): Argument #1 ($keys) and argument #2 ($values) must have the same number of elements in vendor/cycle/orm/src/Parser/AbstractNode.php:378
     *
     */
    protected function perform(): void
    {
        $this->tryAddData();
        /** @var Repository $repo */
        $repo = $this->orm->getRepository(User::class);
        $repo
            ->select()
            ->load('visitPermission')
            ->load('visitPermission.cities')
            ->load('passport')
            ->fetchData();
    }

    private function tryAddData(): void
    {
        try {
            $t = new EntityManager($this->orm);

            $country = new Country(uniqid(),);
            $city = new City($country, uniqid());
            $user = new User(uniqid() . '@mail.ru');

            $t
                ->persist($user)
                ->persist($country)
                ->persist($city)
                ->run();
        } catch (\Throwable $exc) {
            $this->logger->warning($exc->getMessage(), [
                'exception' => $exc,
            ]);
        }
    }
}
