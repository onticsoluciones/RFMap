<?php

namespace Ontic\RFMap\Webservices\Controller;

use Ontic\RFMap\Webservices\Service\ConnectionFactory;
use Symfony\Component\HttpFoundation\Response;

class UpdateScheduledTaskController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        // Handle preflight request
        if($this->request->getMethod() === 'OPTIONS')
        {
            return new Response('', 200, [
                'Access-Control-Allow-Methods' => 'PUT',
                'Access-Control-Allow-Headers' => 'Content-Type'
            ]);
        }

        // Read plugin name
        $pluginName = $this->parameters['name'];

        // Read new interval
        $interval = $this->request->getContent();
        if(strlen($interval) > 0)
        {
            $this->updateSchedule($pluginName, $interval);
        }
        else
        {
            $this->removeSchedule($pluginName);
        }

        return new Response('', 200);
    }
    
    private function updateSchedule($pluginName, $interval)
    {
        // Change the update interval
        $connection = (new ConnectionFactory())->open($this->configuration->databasePath);
        $sql = 'UPDATE tasks 
          SET reschedule_after = :reschedule_after 
          WHERE plugin = :plugin AND reschedule_after IS NOT NULL;';
        $statement = $connection->prepare($sql);
        $statement->execute([
            'reschedule_after' => $interval,
            'plugin' => $pluginName
        ]);
        
        if($statement->rowCount() == 0)
        {
            // Make it an insert instead
            $sql = '
              INSERT INTO tasks(plugin, run_at, reschedule_after)
              VALUES(:plugin, :run_at, :reschedule_after)';
            
            $statement = $connection->prepare($sql);
            $statement->execute([
                'plugin' => $pluginName,
                'run_at' => time(),
                'reschedule_after' => $interval
            ]);
        }
    }
    
    private function removeSchedule($pluginName)
    {
        // Delete the scheduled task from the database
        $connection = (new ConnectionFactory())->open($this->configuration->databasePath);
        $sql = 'DELETE FROM tasks WHERE plugin = :plugin AND reschedule_after IS NOT NULL;';
        $statement = $connection->prepare($sql);
        $statement->execute([
            'plugin' => $pluginName
        ]);
    }
}