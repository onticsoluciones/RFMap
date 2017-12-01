<?php

namespace Ontic\RFMap\Service;

use Ontic\RFMap\Entity\Task;
use Ontic\RFMap\Repository\TaskRepository;

class TaskProcessor
{
    /** @var \PDO */
    private $connection;
    /** @var TaskRepository */
    private $taskRepository;
    /** @var TaskExecutor */
    private $taskExecutor;

    /**
     * @param $pluginDir
     * @param \PDO $connection
     */
    public function __construct($pluginDir, \PDO $connection)
    {
        $this->connection = $connection;
        $this->taskRepository = new TaskRepository($connection);
        $this->taskExecutor = new TaskExecutor($pluginDir, $connection);
    }

    public function run()
    {
        while(true)
        {
            if($task = $this->taskRepository->findFirst())
            {
                $this->onTaskFound($task);
            }
            sleep(1);
        }
    }

    /**
     * @param Task $task
     */
    private function onTaskFound(Task $task)
    {
        $this->taskExecutor->execute($task);
    }
}