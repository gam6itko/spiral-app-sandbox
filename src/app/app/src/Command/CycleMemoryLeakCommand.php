<?php

declare(strict_types=1);

namespace App\Command;

use Cycle\Database\DatabaseInterface;
use Cycle\ORM\FactoryInterface;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;
use SpiralPackages\Profiler\Profiler;
use Symfony\Component\Console\Output\OutputInterface;

#[Console\AsCommand(
    name: 'app:cycle-memory-leak',
    description: 'Fetch currencies rates from stock-markets'
)]
final class CycleMemoryLeakCommand extends Command
{
    #[Console\Option(name: 'max', shortcut: 'm')]
    private int $max = 1_000;

    #[Console\Option(name: 'step', shortcut: 's')]
    private int $step = 10;

    #[Console\Option(name: 'sleep', shortcut: 'u')]
    private int $sleepUs = 1_000_000;

    public function __construct(
        private readonly DatabaseInterface $db,
    )
    {
        parent::__construct();
    }

    protected function perform(OutputInterface $output, FactoryInterface $factory, EnvironmentInterface $env): void
    {
        $profiler = $factory->make(Profiler::class, [
            'appName' => $env->get('PROFILER_APP_NAME', 'Spiral'),
        ]);

        $rowsCount = $this->step;
        while ($rowsCount < $this->max) {
            $profiler->start();

            [$query, $parameters] = $this->buildBatch($rowsCount);
            $output->writeln("Insert $rowsCount rows");
            $this->db->execute($query, $parameters);
            \usleep($this->sleepUs);
            $rowsCount += $this->step;

            $profiler->end([
                'rowsCount' => $rowsCount,
            ]);
        }
    }

    private function buildBatch(int $rowsCount): array
    {
        return [
            $this->buildQuery($rowsCount),
            $this->createRandomInsertData($rowsCount)
        ];
    }

    private function buildQuery(int $rowsCount)
    {
        $columnsCount = 4;

        $sql = <<<SQL
INSERT INTO rows_for_memory_leak_test (`id`, `code`, `name`, `number`)
VALUES %values%
ON DUPLICATE KEY UPDATE  
    `code` = VALUES(`code`), 
    `name` = VALUES(`name`), 
    `number` = VALUES(`number`)
SQL;

        $rowTemplate = \sprintf(
            '(%s)',
            \implode(
                ',',
                \array_pad([], $columnsCount, '?')
            )
        );

        return \str_replace(
            ['%values%'],
            [
                \rtrim(\str_repeat("$rowTemplate,", $rowsCount), ','),
            ],
            $sql
        );
    }

    private function createRandomInsertData(int $rowsCount): array
    {
        $result = [];
        for ($i = 0; $i < $rowsCount; $i++) {
            $result[] = [
                "{$rowsCount}-{$i}",
                (string)$rowsCount,
                (string)$rowsCount,
                $rowsCount,
            ];
        }
        return \array_merge(...$result);
    }
}
