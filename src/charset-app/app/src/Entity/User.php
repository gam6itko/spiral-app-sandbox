<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'user')]
class User
{
    #[Cycle\Column(type: 'primary', unsigned: true)]
    public ?int $id = null;

    #[Cycle\Column(type: 'string', length: 255, charset: 'ascii', collation: 'ascii_bin')]
    public string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
