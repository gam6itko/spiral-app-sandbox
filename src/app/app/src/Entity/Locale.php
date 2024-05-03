<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'locale')]
class Locale
{
    #[Cycle\Column(type: 'primary')]
    public ?int $id = null;

    #[Cycle\Column(type: 'string(5)')]
    public string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }
}
