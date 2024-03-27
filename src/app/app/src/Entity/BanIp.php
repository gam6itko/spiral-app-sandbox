<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'ban_ip')]
class BanIp
{
    #[Cycle\Column(type: 'primary', unsigned: true)]
    public ?int $id = null;

    #[Cycle\Column(type: 'integer', unsigned: true)]
    public int $ipV4;

    #[Cycle\Relation\HasMany(target: BanUser::class, fkCreate: false, indexCreate: false, outerKey: 'mute_ip_id')]
    public array $muteUsers = [];

    public function __construct(int $ipV4)
    {
        $this->ipV4 = $ipV4;
    }
}
