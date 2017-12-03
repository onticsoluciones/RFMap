<?php

namespace Ontic\RFMap\Webservices\Controller;

use Ontic\RFMap\Webservices\Service\ConnectionFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DataPointController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        // Open DB connection
        $connection = (new ConnectionFactory())
            ->open($this->configuration->databasePath);
        
        // Fetch all datapoints
        $sql = 'SELECT * FROM datapoints';
        $parameters = [];
        
        if($plugin = $this->request->get('plugin'))
        {
            $sql .= ' WHERE plugin = :plugin';
            $parameters['plugin'] = $plugin;
        }
        
        $statement = $connection->prepare($sql);
        $statement->execute($parameters);
        
        // Convert them to the appropiate format
        $datapoints = array_map(function($row)
        {
            return static::parseRow($row);
        }, $statement->fetchAll(\PDO::FETCH_ASSOC));
        
        // Return the response
        return new JsonResponse($datapoints);
    }

    /**
     * @param array $row
     * @return array
     */
    private static function parseRow($row)
    {
        return [
            'plugin' => $row['plugin'],
            'entityid' => $row['entityid'],
            'datetime' => $row['datetime'],
            'name' => $row['name'],
            'frequency' => (float) $row['frequency'],
            'bandwidth' => (float) $row['bandwidth'],
            'rssi' => (float) $row['rssi'],
            'extra' => json_decode($row['extra'])
        ];
    }
}