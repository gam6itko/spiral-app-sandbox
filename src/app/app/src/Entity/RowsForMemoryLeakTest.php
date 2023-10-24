<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'rows_for_memory_leak_test')]
class RowsForMemoryLeakTest
{
    #[Cycle\Column(type: 'string(36)', primary: true, unsigned: true)]
    private ?string $id = null;

    #[Cycle\Column(type: 'string(10)', default: 'bar')]
    private string $code;

    #[Cycle\Column(type: 'string', default: 'foo')]
    private string $name;

    #[Cycle\Column(type: 'integer', default: 0)]
    private string $number;
}
