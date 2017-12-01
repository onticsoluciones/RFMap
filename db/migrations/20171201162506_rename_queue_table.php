<?php

use Phinx\Migration\AbstractMigration;

class RenameQueueTable extends AbstractMigration
{
    public function change()
    {
        $this->table('queue')
            ->rename('tasks');
        
        $this->table('datapoint')
            ->rename('datapoints');
    }
}
