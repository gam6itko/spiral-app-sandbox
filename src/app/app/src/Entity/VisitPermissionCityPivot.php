<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'user_visit__permission_city')]
class VisitPermissionCityPivot
{
    #[Cycle\Column(type: 'primary', unsigned: true)]
    private ?int $id = null;
}
