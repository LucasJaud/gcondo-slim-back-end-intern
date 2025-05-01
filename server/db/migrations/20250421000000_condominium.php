<?php

use App\Helpers\PhinxHelper;
use Phinx\Migration\AbstractMigration;

class Condominium extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('condominiums')
            ->addColumn('name', 'string', ['null' => false])
            ->addColumn('zip_code', 'string', ['limit' => 8, 'null' => false])
            ->addColumn('url', 'string', ['null' => false])
            ->addIndex('url', ['unique' => true]);

        PhinxHelper::setDatetimeColumns($table);

        $table->create();
    }
}
