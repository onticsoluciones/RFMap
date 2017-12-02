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
     * @param string $method
     */
    public function addRoute($name, $path, $controllerName, $method = 'GET')
    {
        $controllerClass = sprintf(
            'Ontic\\RFMap\\Webservices\\Controller\\%sController',
            $controllerName);
        
        $route = new Route($path, ['_controller' => $controllerClass ]);
        $route->setMethods([$method]);
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