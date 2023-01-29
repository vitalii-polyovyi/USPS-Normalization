<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAddressesTable extends AbstractMigration
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
        $table = $this->table('addresses');
        $table->addColumn('line1', 'string', ['limit' => 46])
            ->addColumn('line2', 'string', ['limit' => 46])
            ->addColumn('city', 'string', ['limit' => 50])
            ->addColumn('state', 'string', ['limit' => 50])
            ->addColumn('zipcode', 'string', ['limit' => 10])
            ->addTimestamps(null, false)
            ->addIndex(['line1', 'line2', 'city', 'state', 'zipcode'], ['unique' => true])
            ->create();
    }
}
