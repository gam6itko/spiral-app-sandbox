<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'city')]
class City
{
    #[Cycle\Column(type: 'smallPrimary', unsigned: true)]
    public ?int $id = null;

    #[Cycle\Column(type: 'string')]
    public string $name;

    #[Cycle\Relation\BelongsTo(target: Country::class, innerKey: 'country_id')]
    public Country $country;

    public function __construct(Country $country, string $name)
    {
        $this->name = $name;
        $this->country = $country;
    }
}
