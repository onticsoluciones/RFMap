<?php

namespace Ontic\RFMap\Webservices\Controller;

use Symfony\Component\HttpFoundation\Request;

abstract class Controller
{
    /** @var Request */
    protected $request;

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }
}