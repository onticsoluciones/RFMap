<?php

namespace Ontic\RFMap\Repository;

use Ontic\RFMap\Entity\Task;

class TaskRepository extends Repository
{
    /**
     * @return null|Task
     */
    public function findFirst()
    {
        $sql = '
          SELECT tasks.* 
          FROM tasks 
          INNER JOIN plugins ON tasks.plugin = plugins.name
          WHERE strftime(\'%s\', \'now\') >= run_at AND plugins.enabled = 1
          ORDER BY run_at DESC 
          LIMIT 1;';
        $command = $this->connection->prepare($sql);
        $command->execute();
        
        if($row = $command->fetch(\PDO::FETCH_ASSOC))
        {
            return static::rowToTask($row);
        }
        else
        {
            return null;
        }
    }
    
    /**
     * Add a new task to the queue
     * @param Task $task
     */
    public function add(Task $task)
    {
        $sql = '
          INSERT INTO tasks(plugin, run_at, reschedule_after)
          VALUES(:plugin, :run_at, :reschedule_after);';
        
        $command = $this->connection->prepare($sql);
        $command->execute([
            'plugin' => $task->plugin,
            'run_at' => $task->runAt->format('U'),
            'reschedule_after' => $task->rescheduleAfter
        ]);
    }

    /**
     * Removes a task from the queue
     * @param Task $task
     */
    public function remove(Task $task)
    {
        $sql = 'DELETE FROM tasks WHERE id = :id;';
        $command = $this->connection->prepare($sql);
        $command->execute([ 'id' => $task->id ]);
    }

    /**
     * @param array $row
     * @return Task
     */
    private static function rowToTask($row)
    {
        $task = new Task();
        $task->id = $row['id'];
        $task->plugin = $row['plugin'];
        $task->rescheduleAfter = $row['reschedule_after'];
        $task->entityId = $row['entity_id'];
        
        $task->runAt = \DateTime::createFromFormat('U', $row['run_at']);
        $task->runAt->setTimezone(new \DateTimeZone('UTC'));
        
        return $task;
    }
}