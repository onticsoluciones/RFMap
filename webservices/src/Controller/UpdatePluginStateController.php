<?php

namespace Ontic\RFMap\Webservices\Controller;

use Ontic\RFMap\Webservices\Service\ConnectionFactory;
use Symfony\Component\HttpFoundation\Response;

class UpdatePluginStateController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        // Handle preflight
        if($this->request->getMethod() === 'OPTIONS')
        {
            return new Response('', 200, [
                'Access-Control-Allow-Methods' => 'PUT',
                'Access-Control-Allow-Headers' => 'Content-Type'
            ]);
        }
        
        // Read plugin name
        $pluginName = $this->parameters['name'];
        
        // Read new state (true or false)
        $state = $this->request->getContent();
        
        // Update the plugin state in the database
        $connection = (new ConnectionFactory())->open($this->configuration->databasePath);
        $sql = 'UPDATE plugins SET enabled = :enabled WHERE name = :name;';
        $statement = $connection->prepare($sql);
        $statement->execute([
            'enabled' => $state,
            'name' => $pluginName
        ]);
        
        return new Response('', 200);
    }
}