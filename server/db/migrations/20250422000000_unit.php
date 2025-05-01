<?php

use App\Helpers\PhinxHelper;
use Phinx\Migration\AbstractMigration;

class Unit extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('units')
            ->addColumn('name', 'string', ['null' => false])
            ->addColumn('square_meters', 'float')
            ->addColumn('bedroom_count', 'integer');

        PhinxHelper::setForeignColumn($table, 'condominium_id', 'condominiums');
        PhinxHelper::setDatetimeColumns($table);

        $table->create();
    }
}
