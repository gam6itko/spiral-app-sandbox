<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'country')]
class Country
{
    #[Cycle\Column(type: 'primary')]
    public ?int $id = null;

    /**
     * ISO 3166-1 alpha-2. Uppercase.
     */
    #[Cycle\Column(type: 'string')]
    public string $code;

    #[Cycle\Column(type: 'string', default: 'foo')]
    private string $camelCasePropertyFoo;

    #[Cycle\Column(type: 'string', default: 'bar')]
    private string $camelCasePropertyBar;



    #[Cycle\Relation\HasMany(target: CountryTranslation::class)]
    public array $translations = [];



    #[Cycle\Relation\HasMany(target: City::class)]
    public array $cities = [];

    public function __construct(string $code)
    {
        $this->code = $code;
    }
}
