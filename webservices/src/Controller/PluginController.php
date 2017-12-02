<?php

namespace Ontic\RFMap\Webservices\Controller;

use Ontic\RFMap\Webservices\Service\ConnectionFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PluginController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        // Open DB connection
        $connection = (new ConnectionFactory())->open($this->configuration->databasePath);
        
        // Fetch all plugins
        $sql = 'SELECT * FROM plugins;';
        $statement = $connection->prepare($sql);
        $statement->execute();
        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
        
        // Convert the format
        $plugins = array_map(function($row)
        {
            return static::parseRow($row);
        }, $rows);
        
        // Return the response
        return new JsonResponse($plugins);
    }

    /**
     * @param array $row
     * @return array
     */
    private static function parseRow($row)
    {
        return [
            'name' => $row['name'],
            'enabled' => $row['enabled'] == 1
        ];
    }
}