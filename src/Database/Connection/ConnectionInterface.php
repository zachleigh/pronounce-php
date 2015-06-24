<?php

namespace PronouncePHP\Database\Connection;

interface ConnectionInterface
{
    /**
     * Get connection information string
     *
     * @return string
    */
    public function getConnection();
}