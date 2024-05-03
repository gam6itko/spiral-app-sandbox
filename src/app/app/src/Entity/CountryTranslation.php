<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'country_translation')]
class CountryTranslation
{
    #[Cycle\Column(type: 'primary')]
    public ?int $id = null;

    /**
     * Translation for locale
     */
    #[Cycle\Column(type: 'string')]
    public string $value;

    #[Cycle\Relation\BelongsTo(target: Country::class, innerKey: 'country_id')]
    public Country $country;

    #[Cycle\Relation\RefersTo(target: Locale::class, innerKey: 'locale_id')]
    public Locale $locale;

    public function __construct(Country $country, Locale $locale, string $value)
    {
        $this->country = $country;
        $this->locale = $locale;
        $this->value = $value;
    }
}
