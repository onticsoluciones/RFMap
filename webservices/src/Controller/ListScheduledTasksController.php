<?php

namespace Ontic\RFMap\Webservices\Controller;

use Ontic\RFMap\Webservices\Service\ConnectionFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ListScheduledTasksController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        // Open DB connection
        $connection = (new ConnectionFactory())->open($this->configuration->databasePath);
        
        // Get all scheduled tasks (i.e., all where 'reschedule_after' is non-null)
        $sql = 'SELECT * FROM tasks WHERE reschedule_after IS NOT NULL;';
        $statement = $connection->prepare($sql);
        $statement->execute();
        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $tasks = array_map(function($row)
        {
            return static::rowToTask($row);
        }, $rows);
        
        return new JsonResponse($tasks);
    }
    
    private static function rowToTask($row)
    {
        return [
            'plugin' => $row['plugin'],
            'reschedule_after' => (int) $row['reschedule_after']
        ];
    }
}