<?php

declare(strict_types=1);

namespace App\Command\Issue;

use App\Entity\Issue1\Pipeline;
use App\Entity\Issue1\PipelineJob;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORMInterface;
use Psr\Log\LoggerInterface;
use Spiral\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Spiral\Console\Attribute as Console;

#[Console\AsCommand(
    name: 'app:issue:m2m',
)]
class ManyToManyIssueCommand extends Command
{
    protected function perform(ORMInterface $orm): void
    {
        $pipeline = new Pipeline();

        $taskBB = new PipelineJob($pipeline, 'base');
        $pipeline->addJob($taskBB);

        $pipeline->addJob(new PipelineJob($pipeline, 'job_with_dep', [$taskBB]));

        (new EntityManager($orm))->persist($pipeline)->run();
    }
}
