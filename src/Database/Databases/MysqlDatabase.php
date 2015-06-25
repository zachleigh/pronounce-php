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
     * @param OutputInterface $output
     * @return handle
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
     * Insert data into mysql database
     *
     * @param database handle $handle, array $answers
     * @return null
    */
    public function insertData($handle, $answers, OutputInterface $output)
    {
        $first_item_key = array_keys($answers)[0];

        $keys = array_keys($answers[$first_item_key]);

        $table = getenv('DB_TABLE');

        $names = implode(', ', $keys);

        $placeholders = ':' . implode(', :', $keys);

        $mysql = "INSERT INTO $table " . "(" . $names . ") value (" . $placeholders . ")";
        
        try
        {
            $statement = $handle->prepare($mysql);

            foreach ($answers as $answer)
            {
                $statement->execute($answer);
            }
        }
        catch(\PDOException $e)
        {
            $output->writeln("<error>Problem writing to database</error>");
            $output->writeln("<comment>Please make sure the database table exists and that the database columns match the expected fields</comment>");

            exit();
        }
    }
}