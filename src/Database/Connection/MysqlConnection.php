<?php

namespace PronouncePHP\Database\Connection;

use PronouncePHP\Database\Connection\ConnectionInterface;

class MysqlConnection implements ConnectionInterface
{
    /**
     * Get mysql connection information string
     *
     * @return string
    */
    public function getConnection()
    {
        return 7;
    }
}