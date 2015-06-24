<?php

namespace PronouncePHP\Database\Databases;

use PronouncePHP\Database\Connection\ConnectionInterface;

class MysqlDatabase implements DatabaseInterface
{
    /**
     * Construct
     *
     * @return void
    */
    public function __construct()
    {

    }

    /**
     * Get mysql database information string
     *
     * @return string
    */
    public function getInformationArray()
    {
        $information = $this->config['connections']['mysql'];

        return $information;
    }

    /**
     * Get mysql database information string
     *
     * @return string
    */
    public function getInformationString()
    {
        $information = $this->config['connections']['mysql'];

        return $information;
    }
}