<?php

declare(strict_types=1);

namespace App\Entity\Issue1;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(table: 'pipeline_job_need')]
class PipelineJobNeedPivot
{
    #[Cycle\Column(type: 'primary', unsigned: true)]
    public ?int $id = null;
}
