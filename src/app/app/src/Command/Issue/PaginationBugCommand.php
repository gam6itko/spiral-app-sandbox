<?php

declare(strict_types=1);

namespace App\Command\Issue;

use App\Entity\Country;
use App\Entity\CountryTranslation;
use App\Entity\Locale;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select\Repository;
use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;
use Spiral\Pagination\Paginator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[Console\AsCommand(
    name: 'app:issue:page',
)]
final class PaginationBugCommand extends Command
{
    #[Console\Argument(name: 'limit')]
    private int $limit = 10;

    #[Console\Option(name: 'seed', mode: InputOption::VALUE_NONE)]
    private bool $seed = false;

    protected function perform(OutputInterface $output, ORMInterface $orm): void
    {
        if ($this->seed) {
            $l = $orm->get(Locale::class, ['id' => 1]);
            if (null === $l) {
                $l = new Locale(null, \uniqid());
                (new EntityManager($orm))->persist($l)->run();
            }
            $t = new EntityManager($orm);
            for ($i = 0; $i < 10; $i++) {
                $c = new Country(\uniqid());
                $t->persist($c);

                for ($j = 0; $j < 2; $j++) {
                    $trans = new CountryTranslation($c, $l, \uniqid());
                    $t->persist($trans);
                }
            }
            $t->run();
        }

        /** @var Repository $repo */
        $repo = $orm->getRepository(Country::class);

        $select = $repo->select();
        $paginator = (new Paginator($this->limit))->paginate($select);
        $output->writeln(\sprintf('1. Rows count: %d. Total: %d', \count($select->fetchAll()), $paginator->count()));

        $select = $repo->select()
            ->load('translations');
        $paginator = (new Paginator($this->limit))->paginate($select);
        $output->writeln(\sprintf('2. Rows count: %d. Total: %d', \count($select->fetchAll()), $paginator->count()));

        $select = $repo->select()
            ->load('translations')
            ->where('translations.locale_id', 1);
        $paginator = (new Paginator($this->limit))->paginate($select);
        $output->writeln(\sprintf('3. Rows count: %d. Total: %d', \count($select->fetchAll()), $paginator->count()));
    }
}
