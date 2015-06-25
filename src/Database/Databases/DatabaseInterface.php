<?php

namespace PronouncePHP\Database\Databases;
use Symfony\Component\Console\Output\OutputInterface;

interface DatabaseInterface
{
    /**
     * Get database information array
     *
     * @return array
    */
    public function getInformationArray();

    /**
     * Get database handle
     *
     * @return handle
    */
    public function getHandle(OutputInterface $output);

    /**
     * Insert data into database
     *
     * @return null
    */
    public function insertData($handle, $answers, OutputInterface $output);
}