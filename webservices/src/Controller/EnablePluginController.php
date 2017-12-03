<?php

namespace Ontic\RFMap\Webservices\Controller;

use Ontic\RFMap\Webservices\Service\ConnectionFactory;
use Symfony\Component\HttpFoundation\Response;

class EnablePluginController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        // Read plugin name
        $pluginName = $this->parameters['name'];

        // Update the plugin state in the database
        $connection = (new ConnectionFactory())->open($this->configuration->databasePath);
        
        $sql = 'UPDATE plugins SET enabled = 0;';
        $statement = $connection->prepare($sql);
        $statement->execute();
        
        $sql = 'UPDATE plugins SET enabled = 1 WHERE name = :name;';
        $statement = $connection->prepare($sql);
        $statement->execute([
            'name' => $pluginName
        ]);

        return new Response('', 200);
    }
}