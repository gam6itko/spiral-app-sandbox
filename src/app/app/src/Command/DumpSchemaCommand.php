<?php

declare(strict_types=1);

namespace App\Command;

use Cycle\Database\DatabaseInterface;
use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[Console\AsCommand(
    name: 'app:dump-schema',
    description: 'Dump actual table/column schema to show charset/collation (Cycle ORM charset/collation check)'
)]
final class DumpSchemaCommand extends Command
{
    private const TABLE = 'user_charset_check';

    #[Console\Option(name: 'output', shortcut: 'o', description: 'Path to save SQL dump')]
    private string $outputPath = 'app/resources/schema-dump.sql';

    public function __construct(
        private readonly DatabaseInterface $db,
    ) {
        parent::__construct();
    }

    protected function perform(OutputInterface $output): void
    {
        $nameRow = $this->db->query('SELECT DATABASE() AS db')->fetch();
        $name = $nameRow['db'] ?? $nameRow[0] ?? 'unknown';

        $result = $this->db->query('SHOW CREATE TABLE ' . self::TABLE)->fetchAll();
        $createTable = null;
        foreach ($result as $row) {
            $createTable = $row['Create Table'] ?? $row[1] ?? implode("\t", $row);
            break;
        }

        $rows = $this->db->query(
            'SELECT COLUMN_NAME, COLUMN_TYPE, CHARACTER_SET_NAME, COLLATION_NAME 
             FROM information_schema.COLUMNS 
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = \'' . self::TABLE . '\' AND COLUMN_NAME = \'email\''
        )->fetchAll();

        $dump = $this->buildSqlDump($name, $createTable, $rows);

        $outPath = $this->outputPath;
        $dir = \dirname($outPath);
        if ($dir !== '' && !is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($outPath, $dump);
        $output->writeln('<info>Schema dump written to:</info> ' . $outPath);

        $output->writeln('');
        $output->writeln('<info>Database:</info> ' . $name);
        $output->writeln('');
        if ($createTable !== null) {
            $output->writeln('<info>SHOW CREATE TABLE ' . self::TABLE . ':</info>');
            $output->writeln($createTable);
            $output->writeln('');
        }
        $output->writeln('<info>information_schema.COLUMNS for ' . self::TABLE . '.email:</info>');
        if ($rows === []) {
            $output->writeln('<comment>Column not found (table may not exist yet).</comment>');
            return;
        }
        foreach ($rows as $row) {
            $output->writeln('  COLUMN_NAME: ' . ($row['COLUMN_NAME'] ?? $row[0]));
            $output->writeln('  COLUMN_TYPE: ' . ($row['COLUMN_TYPE'] ?? $row[1]));
            $output->writeln('  CHARACTER_SET_NAME: ' . ($row['CHARACTER_SET_NAME'] ?? $row[2] ?? '(null)'));
            $output->writeln('  COLLATION_NAME: ' . ($row['COLLATION_NAME'] ?? $row[3] ?? '(null)'));
        }
    }

    private function buildSqlDump(string $databaseName, ?string $createTable, array $emailColumnRows): string
    {
        $lines = [
            '-- Schema dump (Cycle ORM charset/collation check)',
            '-- Database: ' . $databaseName,
            '-- Date: ' . date('Y-m-d H:i:s'),
            '',
        ];

        if ($createTable !== null) {
            $lines[] = $createTable . ';';
            $lines[] = '';
        }

        if ($emailColumnRows !== []) {
            $lines[] = '-- information_schema.COLUMNS for ' . self::TABLE . '.email (charset/collation):';
            foreach ($emailColumnRows as $row) {
                $col = $row['COLUMN_NAME'] ?? $row[0];
                $type = $row['COLUMN_TYPE'] ?? $row[1];
                $charset = $row['CHARACTER_SET_NAME'] ?? $row[2] ?? null;
                $collation = $row['COLLATION_NAME'] ?? $row[3] ?? null;
                $lines[] = sprintf(
                    '--   %s: COLUMN_TYPE=%s, CHARACTER_SET_NAME=%s, COLLATION_NAME=%s',
                    $col,
                    $type,
                    $charset ?? '(null)',
                    $collation ?? '(null)'
                );
            }
        }

        return implode("\n", $lines) . "\n";
    }
}
