<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'user_visit_permission')]
class VisitPermission
{
    #[Cycle\Column(type: 'integer', primary: true, name: 'user_id', unsigned: true)]
    public int $user_id;

    #[Cycle\Relation\BelongsTo(target: User::class, innerKey: 'user_id', indexCreate: false)]
    public User $user;

    #[Cycle\Column(type: 'timestamp', name: 'created_at')]
    protected \DateTimeInterface $createdAt;

    #[Cycle\Column(type: 'boolean', default: false)]
    public bool $allCities = false;

    /**
     * @var list<City>
     */
    #[Cycle\Relation\ManyToMany(
        target: City::class,
        through: VisitPermissionCityPivot::class,
        throughInnerKey: 'user_id',
        throughOuterKey: 'city_id',
    )]
    public array $cities = [];

    public function __construct(User $user, bool $allCities = false)
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->user = $user;
        $this->allCities = $allCities;
    }

}
