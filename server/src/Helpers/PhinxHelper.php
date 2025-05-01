<?php

namespace App\Helpers;

use Phinx\Db\Table;

class PhinxHelper
{
    public static function setDatetimeColumns(Table $table): Table
    {
        $table->addColumn(
            'created_at',
            'datetime',
            ['default' => 'CURRENT_TIMESTAMP', 'null' => false]
        );

        $table->addColumn('updated_at', 'datetime');
        $table->addColumn('deleted_at', 'datetime');

        return $table;
    }

    public static function setForeignColumn(
        Table $table,
        string $columnName,
        string $referencedTable,
        bool $nullable = false
    ): Table {
        $table
            ->addColumn($columnName, 'integer', ['signed' => false, 'null' => $nullable])
            ->addForeignKey($columnName, $referencedTable, 'id');

        return $table;
    }
}
