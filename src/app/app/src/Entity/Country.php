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
    #[Cycle\Column(type: 'string(2)')]
    public string $code;

    #[Cycle\Relation\HasMany(target: CountryTranslation::class)]
    public array $translations = [];

    public function __construct(string $code)
    {
        $this->code = $code;
    }
}
