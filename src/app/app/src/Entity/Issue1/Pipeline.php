<?php

declare(strict_types=1);

namespace App\Entity\Issue1;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'pipeline')]
class Pipeline
{
    #[Cycle\Column(type: 'primary', unsigned: true)]
    public ?int $id = null;

    /**
     * @var list<PipelineJob>
     */
    #[Cycle\Relation\HasMany(target: PipelineJob::class, fkCreate: false, cascade: true, outerKey: 'pipeline_id')]
    public array $jobs = [];

    public function addJob(PipelineJob $taskBB): void
    {
        $this->jobs[] = $taskBB;
    }
}
