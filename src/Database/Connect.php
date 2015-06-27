<?php

namespace PronouncePHP\Database;

use Symfony\Component\Console\Output\OutputInterface;
use PronouncePHP\Database\Databases\DatabaseInterface;

class Connect
{
    public $database;

    private $output;

    /**
     * Construct
     *
     * @param PronouncePHP\Database\Databases\DatabaseInterface $database, Symfony\Component\Console\Output\OutputInterface $output
     * @return void
    */
    public function __construct(DatabaseInterface $database, OutputInterface $output)
    {
        $this->database = $database;

        $this->output = $output;
    }

    /**
     * Get database information array
     *
     * @return array
    */
    public function connectionInformationArray()
    {
        return $this->database->getInformationArray();
    }

    /**
     * Get database handle
     *
     * @return PDO handle
    */
    public function handle()
    {
        return $this->database->getHandle($this->output);
    }

    /**
     * Insert data into database
     *
     * @param PDO handle $handle, array $answers
     * @return null
    */
    public function insertDataArray($handle, $answers)
    {
        $this->database->insertDataArray($handle, $answers, $this->output);
    }

    /**
     * Get database prepared statement
     *
     * @param PDO handle $handle, array $fields
     * @return PDOStatement
    */
    public function statement($handle, $fields)
    {
        return $this->database->getStatement($handle, $fields, $this->output);
    }

    /**
     * Execute prepared statement
     *
     * @param PDOStatement $statement, array $answer
     * @return null
    */
    public function executeStatement($statement, $answer)
    {
        $this->database->executeStatement($statement, $answer, $this->output);
    }
}