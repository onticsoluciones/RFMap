<?php

use Phinx\Migration\AbstractMigration;

class CreateInitialSchema extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('datapoint', [ 'id' => false, 'primary_key' => [ 'plugin', 'entityid', 'datetime' ]])
            ->addColumn('plugin', 'string')
            ->addColumn('entityid', 'string')
            ->addColumn('datetime', 'timestamp', [ 'default' => 'CURRENT_TIMESTAMP' ])
            ->addColumn('name', 'string')
            ->addColumn('frequency', 'float')
            ->addColumn('bandwidth', 'float')
            ->addColumn('rssi', 'float')
            ->addColumn('extra', 'text')
            ->create();
        
        $this->table('queue')
            ->addColumn('plugin', 'string')
            ->addColumn('run_at', 'timestamp')
            ->addColumn('reschedule_after', 'integer', [ 'null' => true ])
            ->create();
        
        $this->table('plugins')
            ->addColumn('name', 'string')
            ->addColumn('enabled', 'boolean')
            ->addIndex([ 'name' ], [ 'unique' => true ])
            ->create();
    }
}
