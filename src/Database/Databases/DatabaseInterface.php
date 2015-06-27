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
     * @param Symfony\Component\Console\Output\OutputInterface $output
     * @return PDO handle
    */
    public function getHandle(OutputInterface $output);

    /**
     * Insert data into database
     *
     * @param PDO handle $handle, array $answers, Symfony\Component\Console\Output\OutputInterface $output
     * @return null
    */
    public function insertDataArray($handle, $answers, OutputInterface $output);

    /**
     * Get database prepared statement
     *
     * @param PDO handle $handle, array $fields, Symfony\Component\Console\Output\OutputInterface $output
     * @return PDOStatement
    */
    public function getStatement($handle, $fields, OutputInterface $output);

    /**
     * Execute prepared statement
     *
     * @param PDOStatement $statement, array $answer, Symfony\Component\Console\Output\OutputInterface $output
     * @return null
    */
    public function executeStatement($statement, $answer, OutputInterface $output);
}