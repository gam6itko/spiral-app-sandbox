<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Country;
use App\Entity\CountryTranslation;
use App\Entity\Locale;
use Cycle\Database\Injection\Fragment;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORMInterface;
use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[Console\AsCommand(
    name: 'app:sort-field',
    description: 'Fetch currencies rates from stock-markets'
)]
final class CountryTranslationCommand extends Command
{

    public function __construct(
        private readonly ORMInterface $orm
    )
    {
        parent::__construct();
    }

    protected function perform(OutputInterface $output): void
    {
        $this->seed();
        $list1 = $this->example1('ru');
        assert(3 === count($list1));
        $output->writeln('example1 - ru');
        foreach ($list1 as $item) {
            $output->writeln(\sprintf('%s   %s', $item['id'], $item['code']));
        }

        $list2 = $this->example2('en');
    }

    private function seed(): void
    {
        $db = $this->orm->getSource(Country::class)->getDatabase();
        $db->delete('country')->run();
        $db->delete('country_translation')->run();
        $db->delete('locale')->run();

        $localeRu = new Locale('ru');
        $localeEn = new Locale('en');
        (new EntityManager($this->orm))
            ->persist($localeRu)
            ->persist($localeEn)
            ->run();

        $afganistan = new Country('AF');
        $russia = new Country('RU');
        $china = new Country('CH');
        (new EntityManager($this->orm))
            ->persist($afganistan)
            ->persist($russia)
            ->persist($china)
            ->run();

        $afganistanRu = new CountryTranslation($afganistan, $localeRu, 'Афганистан');
        $chinaRu = new CountryTranslation($china, $localeRu, 'Китай');
        $russiaRu = new CountryTranslation($russia, $localeRu, 'Россия');

        $afganistanEn = new CountryTranslation($afganistan, $localeEn, 'Afghanistan');
        $chinaEn = new CountryTranslation($china, $localeEn, 'China');
        $russiaEn = new CountryTranslation($russia, $localeEn, 'Russia');
        (new EntityManager($this->orm))
            ->persist($afganistanRu)
            ->persist($chinaRu)
            ->persist($russiaRu)
            // en
            ->persist($afganistanEn)
            ->persist($chinaEn)
            ->persist($russiaEn)
            ->run();
    }

    /**
     * 2 queries
     */
    private function example1(string $locale): array
    {
        /** @var Locale $locale */
        $locale = $this->orm->get(Locale::class, ['code' => $locale]);

        $transList = $this->orm->getRepository(CountryTranslation::class)
            ->select()
            ->where('locale_id', $locale->id)
            ->orderBy('value')
            ->fetchData();
        $idList = \array_column($transList, 'id');

        return $this->orm->getRepository(Country::class)
            ->select()
            ->orderBy(
                new Fragment(
                    \sprintf(
                        'FIELD(`id`, %s)',
                        \implode(',', $idList)
                    )
                )
            )
            ->fetchData();
    }

    /**
     * 1 query proposal
     */
    private function example2(string $locale): array
    {
        /** @var Locale $locale */
        $locale = $this->orm->get(Locale::class, ['code' => $locale]);

        $db = $this->orm->getSource(CountryTranslation::class)->getDatabase();

        return $this->orm->getRepository(CountryTranslation::class)
            ->select()
            ->orderBy(

                // todo - order subquery
                new Fragment(
                    'FIELD',
                    'country_translation.id',
                    $db->select(['id'])
                        ->from('country_translation')
                        ->where('locale_id', $locale->id)
                        ->orderBy('value')
                )

            )
            ->fetchAll();
    }
}
