<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'post')]
class Post
{
    #[Cycle\Column(type: 'primary')]
    public ?int $id = null;

    #[Cycle\Relation\RefersTo(target: User::class, innerKey: 'user_id')]
    public User $user;

    #[Cycle\Column(type: 'string')]
    public string $text = 'default text';
}
