<?php

namespace Ontic\RFMap\Service;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Ontic\RFMap\Entity\DataPoint;
use Ontic\RFMap\Entity\Task;
use Ontic\RFMap\Repository\DataPointRepository;
use Ontic\RFMap\Repository\PluginRepository;
use Ontic\RFMap\Repository\TaskRepository;

class TaskExecutor
{  
    /** @var Logger */
    private $logger;
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
        $this->logger = new Logger(TaskExecutor::class);
        $this->logger->pushHandler(new StreamHandler("php://stdout"));
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
            $this->completeTask($task);
            return;
        }
        
        if(!file_exists($executablePath) || !is_executable($executablePath))
        {
            // Plugin directory has been tampered after startup, do nothing
            $this->completeTask($task);
            return;
        }
        
        // Compose the command string
        $command = $executablePath;
        if($task->entityId)
        {
            $command = sprintf('%s %s', $command, escapeshellarg($task->entityId));
        }
        
        // Run it and wait for its response...
        $this->logger->debug(sprintf('Executing command "%s"', $command));
        exec($command, $output, $retval);
        $output = implode(PHP_EOL, $output);
        $this->logger->debug($output);
        
        if($retval !== 0)
        {
            // Command execution failed
            $this->logger->err(sprintf('Command execution failed with code %d', $retval));
            $this->completeTask($task);
            return;
        }
        
        // Try to parse the output as a JSON object
        if(($data = json_decode($output, true)) === null)
        {
            // Output not in the expected format, leave
            $this->logger->err('Command output is not a valid JSON object');
            $this->completeTask($task);
            return;
        }
        
        // Convert the json output to a datapoint array
        $dataPointArray = static::jsonToDataPoints($task->plugin, $data);
        $this->completeTask($task, $dataPointArray);
    }
    
    private function completeTask(Task $task, $dataPointArray = [])
    {
        $this->connection->beginTransaction();
        
        if($dataPointArray)
        {
            foreach($dataPointArray as $dataPoint)
            {
                $this->dataPointRepository->add($dataPoint);
            }
        }
        
        // Remove the task from the queue
        $this->taskRepository->remove($task);

        // If the task is a recurring one, reinsert it into the
        // queue to be executed after the specified interval
        if($task->rescheduleAfter)
        {
            $task = $task->getRescheduledTask();
            $this->taskRepository->add($task);
        }

        $this->connection->commit();
    }

    /**
     * @param string $pluginName
     * @param array $jsonArray
     * @return DataPoint[]
     */
    private static function jsonToDataPoints($pluginName, $jsonArray)
    {
        $dataPoints = [];
        
        foreach($jsonArray as $json)
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

            foreach ($requiredFields as $field)
            {
                if (!array_key_exists($field, $json))
                {
                    continue;
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

            // Add datapoint to the array
            $dataPoints[] = $dataPoint;
        }
        
        return $dataPoints;
    }
}