<?php

use Phinx\Migration\AbstractMigration;

class CreateInitialSchema extends AbstractMigration
{
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
