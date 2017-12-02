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
        $connection = new \PDO('sqlite:' . $databaseFile);
        $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $connection;
    }
}