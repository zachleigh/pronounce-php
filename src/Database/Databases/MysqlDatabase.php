<?php

namespace PronouncePHP\Database\Databases;

use Symfony\Component\Console\Output\OutputInterface;
use PronouncePHP\Database\Connection\ConnectionInterface;
use PronouncePHP\Config\Config;

class MysqlDatabase implements DatabaseInterface
{
    /**
     * Get mysql database information array
     *
     * @return array
    */
    public function getInformationArray()
    {
        $array = Config::get('connections')['mysql'];

        return $array;
    }

    /**
     * Get mysql database handle
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output
     * @return PDO handle
    */
    public function getHandle(OutputInterface $output)
    {
        $mysql= $this->getInformationArray();

        $driver = $mysql['driver'];

        $charset = $mysql['charset'];

        $host = $mysql['host'];

        $dbname = $mysql['database_name'];

        $username = $mysql['username'];

        $password = $mysql['password'];

        try
        {
            $handle = new \PDO("$driver:host=$host;dbname=$dbname;charset=$charset", $username, $password);

            $handle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        }
        catch(\PDOException $e) 
        {
            $output->writeln("<error>Could not connect to database</error>");
            $output->writeln("<comment>Please ensure database credentials in .env are correct and that connection information is properly set in config.php</comment>");
            
            exit();
        }

        return $handle;
    }

    /**
     * Insert data array into mysql database
     *
     * @param PDO handle $handle, array $answers, Symfony\Component\Console\Output\OutputInterface $output
     * @return null
    */
    public function insertDataArray($handle, $answers, OutputInterface $output)
    {
        $fields = getKeys($answers);

        $statement = $this->getStatement($handle, $fields, $output);

        foreach ($answers as $answer)
        {
            $this->executeStatement($statement, $answer, $output);
        }
    }

    /**
     * Get mysql prepared statement
     *
     * @param PDO handle $handle, array $fields, Symfony\Component\Console\Output\OutputInterface $output
     * @return PDOStatement
    */
    public function getStatement($handle, $fields, OutputInterface $output)
    {
        $table = getenv('DB_TABLE');

        $names = implode(', ', $fields);

        $placeholders = ':' . implode(', :', $fields);

        $mysql = "INSERT INTO $table " . "(" . $names . ") value (" . $placeholders . ")";

        try
        {
            $statement = $handle->prepare($mysql);
        }
        catch(\PDOException $error)
        {
            $output->writeln("<error>Problem preparing mysql statement</error>");

            exit();
        }

        return $statement;
    }

    /**
     * Execute mysql statement
     *
     * @param PDOStatement $statement, array $answer, Symfony\Component\Console\Output\OutputInterface $output
     * @return null
    */
    public function executeStatement($statement, $answer, OutputInterface $output)
    {
        try
        {
            $statement->execute($answer);
        }
        catch(\PDOException $e)
        {
            $output->writeln("<error>Problem writing to database</error>");
            $output->writeln("<comment>Please make sure the database table exists and that the database columns match the expected fields</comment>");

            exit();
        }
    }
}