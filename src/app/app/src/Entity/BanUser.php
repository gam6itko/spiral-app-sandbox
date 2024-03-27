<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'ban_user')]
class BanUser
{
    #[Cycle\Column(type: 'primary', unsigned: true)]
    public ?int $id = null;

    #[Cycle\Relation\RefersTo(target: User::class, innerKey: 'user_id')]
    public User $user;

    #[Cycle\Column(type: 'timestamp')]
    public \DateTimeInterface $expiresAt;

    /**
     * Дополнительная блокировка ip.
     */
    #[Cycle\Relation\RefersTo(target: BanIp::class, nullable: true, innerKey: 'mute_ip_id')]
    public ?BanIp $muteIp = null;

    public function __construct(User $user, ?BanIp $muteIp = null)
    {
        $this->user = $user;
        $this->muteIp = $muteIp;
    }
}
