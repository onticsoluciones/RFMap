<?php

namespace Ontic\RFMap\Webservices\Service;

class ConnectionFactory
{
    /**
     * @param $databaseFile
     * @return \PDO
     */
    public function open($databaseFile)
    {
        return new \PDO('sqlite:' . $databaseFile);
    }
}