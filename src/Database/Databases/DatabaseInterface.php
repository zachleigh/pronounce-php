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
     * @param OutputInterface $output
     * @return handle
    */
    public function getHandle(OutputInterface $output);

    /**
     * Insert data into database
     *
     * @param database handle $handle, array $answers, OutputInterface $output
     * @return null
    */
    public function insertDataArray($handle, $answers, OutputInterface $output);

    /**
     * Get database prepared statement
     *
     * @param database handle $handle, array $answers, OutputInterface $output
     * @return PDOStatement
    */
    public function getStatement($handle, $answers, OutputInterface $output);

    /**
     * Execute prepared statement
     *
     * @param array $answer, OutputInterface $output
     * @return null
    */
    public function executeStatement($statement, $answer, OutputInterface $output);
}