<?php

require_once __DIR__ . '/vendor/autoload.php';

use Ontic\RFMap\Webservices\Controller\Controller;
use Ontic\RFMap\Webservices\Service\ConfigurationLoader;
use Ontic\RFMap\Webservices\Util\RouteCollectionBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

// Load DB configuration
$configurationFile = __DIR__ . '/../phinx.yml';
$configuration = (new ConfigurationLoader($configurationFile))->load();

// Build request object
$request = Request::createFromGlobals();

// Define routes
$builder = new RouteCollectionBuilder();
$builder->addRoute('datapoints', '/datapoint', 'DataPoint');
$builder->addRoute('plugins', '/plugin', 'Plugin');
$builder->addRoute('tasks', '/task', 'ListScheduledTasks');
$builder->addRoute('updatePluginState', '/plugin/{name}/state', 'UpdatePluginState', ['PUT', 'OPTIONS']);
$builder->addRoute('updateScheduledTask', '/task/{name}/interval', 'UpdateScheduledTask', ['PUT', 'OPTIONS']);
$routes = $builder->getRoutes();

// Match current request
$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try
{
    $parameters = $matcher->match($request->getPathInfo());
    
    // Instantiate matching controller
    /** @var Controller $controller */
    $controller = new $parameters['_controller'];
    $controller->setRequest($request);
    $controller->setConfiguration($configuration);
    $controller->setParameters($parameters);
    
    // Execute it and fetch the response
    $response = $controller->indexAction();
    
    // Add CORS header
    $response->headers->add(['Access-Control-Allow-Origin' => '*']);
    
    // Send the response and exit
    $response->send();
}
catch(ResourceNotFoundException $ex)
{
    // Route not found
    header(':', true, 404);
    echo '404 Not Found';
}
