<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'user')]
class User
{
    #[Cycle\Column(type: 'primary', unsigned: true)]
    public ?int $id = null;

    #[Cycle\Column(type: 'string')]
    public string $username;

    #[Cycle\Relation\HasOne(target: Passport::class, nullable: true, outerKey: 'user_id')]
    public ?Passport $passport = null;

    #[Cycle\Relation\HasMany(target: Post::class, outerKey: 'user_id', fkCreate: false)]
    public array $posts = [];

    #[Cycle\Relation\HasOne(target: VisitPermission::class, fkCreate: false, outerKey: 'user_id')]
    public ?VisitPermission $visitPermission = null;

    public function __construct(string $username)
    {
        $this->username = $username;
    }
}
