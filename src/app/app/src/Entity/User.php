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

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
}
