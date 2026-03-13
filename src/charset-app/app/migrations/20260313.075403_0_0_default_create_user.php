<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefaultB9ce859e98da7f50eb7a1baa8831eda6 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('user')
            ->addColumn('id', 'primary', [
                'nullable' => false,
                'defaultValue' => null,
                'size' => 11,
                'autoIncrement' => true,
                'unsigned' => true,
                'zerofill' => false,
                'comment' => '',
            ])
            ->addColumn('email', 'string', [
                'nullable' => false,
                'defaultValue' => null,
                'length' => 255,
                'charset' => 'ascii',
                'collation' => 'ascii_bin',
                'size' => 255,
                'comment' => '',
            ])
            ->setPrimaryKeys(['id'])
            ->create();
    }

    public function down(): void
    {
        $this->table('user')->drop();
    }
}
