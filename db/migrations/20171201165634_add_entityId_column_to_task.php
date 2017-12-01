<?php


use Phinx\Migration\AbstractMigration;

class AddEntityIdColumnToTask extends AbstractMigration
{
    public function change()
    {
        $this->table('tasks')
            ->addColumn('entity_id', 'string', [ 'null' => true ])
            ->update();
    }
}
