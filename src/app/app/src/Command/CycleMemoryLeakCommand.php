<?php

declare(strict_types=1);

namespace App\Command;

use BestChange\Be\Cmn\CurrencyRateFetcher\CbrfRateFetcher;
use BestChange\Be\Cmn\CurrencyRateFetcher\HitBtcRateFetcher;
use BestChange\Be\Cmn\CurrencyRateFetcher\StableCoinFetcher;
use Cycle\Database\DatabaseInterface;
use Ramsey\Uuid\Uuid;
use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[Console\AsCommand(
    name: 'app:cycle-memory-leak',
    description: 'Fetch currencies rates from stock-markets'
)]
final class CycleMemoryLeakCommand extends Command
{
    #[Console\Option(name: 'max', shortcut: 'm')]
    private int $max = 1_000;

    #[Console\Option(name: 'sleep', shortcut: 's')]
    private int $sleepSec = 1;

    public function __construct(
        private readonly DatabaseInterface $db,
    )
    {
        parent::__construct();
    }

    protected function perform(OutputInterface $output): void
    {
        $i =0;
        while($i< $this->max) {
            $rowsCount = rand(1, 1000);
            [$query, $parameters] = $this->buildBatch($rowsCount);
            $output->writeln("Insert $rowsCount rows");
            $this->db->execute($query, $parameters);
            \sleep($this->sleepSec);
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
INSERT INTO exchange_rate (`id`, `code`, `name`, `number`)
VALUES %values%
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
            $n = rand(1, 20);
            $result[] = [
                Uuid::uuid7()->toString(),
                $this->getRandomString(10),
                $this->getRandomString($n),
                $n,
            ];
        }
        return $result;
    }

    private function getRandomString(int $n): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $result .= $characters[$index];
        }

        return $result;
    }
}
