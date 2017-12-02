<?php

namespace Ontic\RFMap\Webservices\Controller;

use Ontic\RFMap\Webservices\Model\Configuration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    /** @var Request */
    protected $request;
    /** @var Configuration */
    protected $configuration;

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Configuration $configuration
     */
    public function setConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return Response
     */
    public abstract function indexAction();
}