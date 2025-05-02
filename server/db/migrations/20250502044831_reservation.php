<?php

use App\Helpers\PhinxHelper;
use Phinx\Migration\AbstractMigration;

class Reservation extends AbstractMigration
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
        $table = $this->table('reservations')
        ->addColumn('name', 'string', ['null' => false])
        ->addColumn('guest_count', 'integer', ['null' => true])
        ->addColumn('date', 'datetime', ['null' => false]);

        PhinxHelper::setForeignColumn($table, 'venue_id', 'venues');
        PhinxHelper::setDatetimeColumns($table);

        $table->create();
    }
}
