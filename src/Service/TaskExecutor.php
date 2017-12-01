<?php

namespace Ontic\RFMap\Service;

use Ontic\RFMap\Entity\DataPoint;
use Ontic\RFMap\Entity\Task;
use Ontic\RFMap\Repository\DataPointRepository;
use Ontic\RFMap\Repository\PluginRepository;
use Ontic\RFMap\Repository\TaskRepository;

class TaskExecutor
{
    /** @var string */
    private $pluginDir;
    /** @var \PDO */
    private $connection;
    /** @var PluginRepository */
    private $pluginRepository;
    /** @var TaskRepository */
    private $taskRepository;
    /** @var DataPointRepository */
    private $dataPointRepository;

    /**
     * @param string $pluginDir
     * @param \PDO $connection
     */
    public function __construct($pluginDir, \PDO $connection)
    {
        $this->pluginDir = $pluginDir;
        $this->connection = $connection;
        $this->pluginRepository = new PluginRepository($connection);
        $this->taskRepository = new TaskRepository($connection);
        $this->dataPointRepository = new DataPointRepository($connection);
    }

    /**
     * @param Task $task
     */
    public function execute(Task $task)
    {
        $executablePath = sprintf('%s/%s/execute', $this->pluginDir, $task->plugin);
        
        if(!$this->pluginRepository->isEnabled($task->plugin))
        {
            // Plugin is no longer enabled, bail out
            return;
        }
        
        if(!file_exists($executablePath) || !is_executable($executablePath))
        {
            // Plugin directory has been tampered after startup, do nothing
            return;
        }
        
        // Compose the command string
        $command = $executablePath;
        if($task->entityId)
        {
            $command = sprintf('%s %s', $command, escapeshellarg($task->entityId));
        }
        
        // Run it and wait for its response...
        exec($command, $output, $retval);
        
        if($retval !== 0)
        {
            // Command execution failed
            return;
        }
        
        // Try to parse the output as a JSON object
        $output = implode(PHP_EOL, $output);
        if(($data = json_decode($output)) === null)
        {
            // Output not in the expected format, leave
            return;
        }
        
        $this->connection->beginTransaction();
        
        // Convert the json output to a datapoint
        $dataPoint = static::jsonToDataPoint($task->plugin, $data);
        if($dataPoint)
        {
            $this->dataPointRepository->add($dataPoint);
        }
        
        // Remove the task from the queue
        $this->taskRepository->remove($task);
        
        // If the task is a recurring one, reinsert it into the
        // queue to be execute after the specified interval
        if($task->rescheduleAfter)
        {
            $task = $task->getRescheduledTask();
            $this->taskRepository->add($task);
        }
        
        $this->connection->commit();
    }

    /**
     * @param string $pluginName
     * @param array $json
     * @return null|DataPoint
     */
    private static function jsonToDataPoint($pluginName, $json)
    {
        // Verify that all required fields are present
        $requiredFields = [
            'entity_id',
            'name',
            'frequency',
            'bandwidth',
            'rssi',
            'extra'
        ];
        
        foreach($requiredFields as $field)
        {
            if(!array_key_exists($field, $json))
            {
                return null;
            }
        }
        
        // Compose the datapoint object
        $dataPoint = new DataPoint();
        $dataPoint->plugin = $pluginName;
        $dataPoint->entityid = $json['entity_id'];
        $dataPoint->name = $json['name'];
        $dataPoint->frequency = $json['frequency'];
        $dataPoint->bandwidth = $json['bandwidth'];
        $dataPoint->rssi = $json['rssi'];
        $dataPoint->extra = $json['extra'];
        
        // And return it
        return $dataPoint;
    }
}