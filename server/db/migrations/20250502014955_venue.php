<?php

use App\Helpers\PhinxHelper;
use Phinx\Migration\AbstractMigration;

class Venue extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */

    public function change(): void
    {
        $table = $this->table('venues')
        ->addColumn('name','string',['null' => false])
        ->addColumn('square_meters', 'float')
        ->addColumn('maximum_occupancy', 'integer');

        PhinxHelper::setForeignColumn($table, 'condominium_id', 'condominiums');
        PhinxHelper::setDatetimeColumns($table);

        $table->create();
    }
}
