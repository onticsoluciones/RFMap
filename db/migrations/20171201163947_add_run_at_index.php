<?php


use Phinx\Migration\AbstractMigration;

class AddRunAtIndex extends AbstractMigration
{
    public function change()
    {
        $this->table('tasks')
            ->addIndex([ 'run_at' ])
            ->save();
    }
}
