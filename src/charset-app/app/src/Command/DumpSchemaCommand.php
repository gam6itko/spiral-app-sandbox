<?php

declare(strict_types=1);

namespace App\Command;

use Cycle\Database\DatabaseInterface;
use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[Console\AsCommand(
    name: 'app:dump-schema',
    description: 'Dump actual table/column schema to show charset/collation (to verify migration bug)'
)]
final class DumpSchemaCommand extends Command
{
    public function __construct(
        private readonly DatabaseInterface $db,
    ) {
        parent::__construct();
    }

    protected function perform(OutputInterface $output): void
    {
        $nameRow = $this->db->query('SELECT DATABASE() AS db')->fetch();
        $name = $nameRow['db'] ?? $nameRow[0] ?? 'unknown';
        $output->writeln('<info>Database:</info> ' . $name);
        $output->writeln('');

        $output->writeln('<info>SHOW CREATE TABLE user:</info>');
        $result = $this->db->query('SHOW CREATE TABLE user')->fetchAll();
        foreach ($result as $row) {
            $createTable = $row['Create Table'] ?? $row[1] ?? implode("\t", $row);
            $output->writeln($createTable);
        }
        $output->writeln('');

        $output->writeln('<info>information_schema.COLUMNS for user.email:</info>');
        $rows = $this->db->query(
            'SELECT COLUMN_NAME, COLUMN_TYPE, CHARACTER_SET_NAME, COLLATION_NAME 
             FROM information_schema.COLUMNS 
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = \'user\' AND COLUMN_NAME = \'email\''
        )->fetchAll();

        if ($rows === []) {
            $output->writeln('<comment>Column user.email not found (table may not exist yet).</comment>');
            return;
        }
        foreach ($rows as $row) {
            $output->writeln('  COLUMN_NAME: ' . ($row['COLUMN_NAME'] ?? $row[0]));
            $output->writeln('  COLUMN_TYPE: ' . ($row['COLUMN_TYPE'] ?? $row[1]));
            $output->writeln('  CHARACTER_SET_NAME: ' . ($row['CHARACTER_SET_NAME'] ?? $row[2] ?? '(null)'));
            $output->writeln('  COLLATION_NAME: ' . ($row['COLLATION_NAME'] ?? $row[3] ?? '(null)'));
        }
    }
}
