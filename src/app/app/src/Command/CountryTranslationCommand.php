<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Country;
use App\Entity\CountryTranslation;
use App\Entity\Locale;
use Cycle\Database\Injection\Fragment;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\SchemaInterface;
use Cycle\ORM\Select\JoinableLoader;
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

        $localeId = 1;

        $list1 = $this->orm->getRepository(Country::class)
            ->select()
            ->with('translations', [
                'as' => 'trans',
                'method' => JoinableLoader::LEFT_JOIN,
                'where' => [
                    'locale_id' => $localeId,
                ]
            ])
            ->load('translations', [
//                'using' => 'trans',
                'method' => JoinableLoader::LEFT_JOIN,
            ])
            ->orderBy('trans.value')
//            ->buildQuery()->columns('*, trans.value AS abc')
//            ->orderBy('abc')
            ->fetchData();

        $columns = $this->orm->getSchema()->define(Country::class, SchemaInterface::COLUMNS);
//        \var_dump($columns);

//
//        $list2 = [];
//        foreach ($list1 as $key => $item) {
//            $item2 = [];
//            foreach ($columns as $to => $from) {
//                $item2[$to] = $item[$from];
//            }
//            $list2[] = $item2;
//        }


        \print_r($list1);
//        \var_dump($list2);

        //SELECT `country`.`id` AS `c0`, `country`.`code` AS `c1`
        //FROM `country` AS `country`
        //LEFT JOIN `country_translation` AS `trans`
        //    ON `trans`.`country_id` = `country`.`id` AND `trans`.`locale_id` = ?
        //ORDER BY `trans`.`value` ASC

//        $this->seed();
//        $list1 = $this->example1('ru');
//        assert(3 === count($list1));
        $output->writeln('example1 - ru');
        foreach ($list1 as $item) {
            $output->writeln(\sprintf('%s   %s', $item['id'], $item['code']));
        }

//        $list2 = $this->example2('en');
    }

    private function seed(): void
    {
        $db = $this->orm->getSource(Country::class)->getDatabase();
        $db->delete('country')->run();
        $db->delete('country_translation')->run();
        $db->delete('locale')->run();

        $localeRu = new Locale(1, 'ru');
        $localeEn = new Locale(2, 'en');
        (new EntityManager($this->orm))
            ->persist($localeRu)
            ->persist($localeEn)
            ->run();

        $afganistan = new Country('1 AF');
        $russia = new Country('2 RU');
        $china = new Country('3 CH');
        (new EntityManager($this->orm))
            ->persist($afganistan)
            ->persist($russia)
            ->persist($china)
            ->run();

        // У русских всё вверх дном
        $afganistanRu = new CountryTranslation($afganistan, $localeRu, '2 - Афганистан');
        $chinaRu = new CountryTranslation($china, $localeRu, '9 - Китай');
        $russiaRu = new CountryTranslation($russia, $localeRu, '1 - Россия');

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

}
