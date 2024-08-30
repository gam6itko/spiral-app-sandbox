<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Country;
use App\Entity\CountryTranslation;
use App\Entity\Locale;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORMInterface;
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

        $localeIdRu = 1;

        $list1 = $this->orm->getRepository(Country::class)
            ->select()
            ->with('translations', [
                'as' => 'transRu',
                'method' => JoinableLoader::LEFT_JOIN,
                'where' => [
                    'locale_id' => $localeIdRu,
                ]
            ])
            ->load('translations')
            ->orderBy('transRu.value')
            ->fetchData();

        \print_r($list1);
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
}
