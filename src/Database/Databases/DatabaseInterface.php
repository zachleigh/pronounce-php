<?php

namespace PronouncePHP\Database\Databases;

interface DatabaseInterface
{
    /**
     * Get connection information string
     *
     * @return string
    */
    public function getInformationString();
}