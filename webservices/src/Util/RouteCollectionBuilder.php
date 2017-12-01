<?php

namespace Ontic\RFMap\Webservices\Util;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteCollectionBuilder
{
    /** @var RouteCollection */
    private $routes;
    
    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    /**
     * @param string $name
     * @param string $path
     * @param string $controllerName
     */
    public function addRoute($name, $path, $controllerName)
    {
        $controllerClass = sprintf(
            'Ontic\\RFMap\\Webservices\\Controller\\%sController.php',
            $controllerName);
        
        $route = new Route($path, ['_controller' => $controllerClass ]);
        $this->routes->add($name, $route);
    }

    /**
     * @return RouteCollection
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}