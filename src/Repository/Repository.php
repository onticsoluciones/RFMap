<?php

namespace Ontic\RFMap\Repository;

abstract class Repository
{
    /** @var \PDO */
    protected $connection;

    /**
     * @param \PDO $connection
     */
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }
}