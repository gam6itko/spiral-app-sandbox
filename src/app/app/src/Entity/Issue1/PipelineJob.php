<?php

declare(strict_types=1);

namespace App\Entity\Issue1;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'job')]
class PipelineJob
{
    #[Cycle\Column(type: 'primary', unsigned: true)]
    public ?int $id = null;

    #[Cycle\Column(type: 'string')]
    public string $name;

    #[Cycle\Relation\BelongsTo(target: Pipeline::class, cascade: false, innerKey: 'pipeline_id')]
    public Pipeline $pipeline;

    /**
     * @var list<PipelineJob>
     */
    #[Cycle\Relation\ManyToMany(
        target: PipelineJob::class,
        through: PipelineJobNeedPivot::class,
        throughInnerKey: 'job_id',
        throughOuterKey: 'need_job_id',
        load: 'lazy',
        indexCreate: false,
    )]
    public array $needs = [];

    public function __construct(
        Pipeline $pipeline,
        string $name,
        array $needs = [],
    )
    {
        $this->pipeline = $pipeline;
        $this->name = $name;
        $this->needs = $needs;
    }
}
