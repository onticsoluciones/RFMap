<?php

require_once __DIR__ . '/vendor/autoload.php';

use Ontic\RFMap\Webservices\Util\RouteCollectionBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

// Build request object
$request = Request::createFromGlobals();

// Define routes
$builder = new RouteCollectionBuilder();
$builder->addRoute('datapoints', '/datapoint', 'DataPoint');
$routes = $builder->getRoutes();

// Match current request
$context = new RequestContext('/');
$matcher = new UrlMatcher($routes, $context);
$parameters = $matcher->match($request->getUri());


