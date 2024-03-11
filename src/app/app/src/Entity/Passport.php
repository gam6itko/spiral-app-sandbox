<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'passport')]
class Passport
{
    #[Cycle\Column(type: 'primary', unsigned: true)]
    public ?int $id = null;

    #[Cycle\Column(type: 'string')]
    public string $number;

    #[Cycle\Relation\BelongsTo(target: User::class, innerKey: 'user_id', indexCreate: false)]
    public User $user;
}
