<?php

namespace PronouncePHP\Database;

use Symfony\Component\Console\Output\OutputInterface;
use PronouncePHP\Database\Connection\MysqlConnection;

class Connect
{
    private $config;

    /**
     * Construct
     *
     * @return void
    */
    public function __construct()
    {
        $this->config = include('config.php');
    }

    /**
     * Get database connection
     *
     * @return Connection
    */
    public function getDatabaseConnection($output)
    {
        $database_type = $this->getDatabaseType($output);

        $database_class = $this->makeConnectionClass($database_type);

        $connection_information = $this->getConnectionInformation($database_type);

        if (!class_exists($database_class))
        {
            $output->writeln("<error>Database type not found!</error>");
            $output->writeln("<comment>Please ensure that the database type is specified and that it is supported</comment>");

            $GLOBALS['status'] = 1;

            exit();
        }
        
        return new $database_class();
    }

    /**
     * Get database type specified in config file
     *
     * @return string
    */
    public function getDatabaseType($output)
    {
        $database_type = $this->config['database'];

        if (is_null($database_type))
        {
            $output->writeln("<error>No database type specified in config.php</error>");

            $GLOBALS['status'] = 1;

            return null;
        }

        return $database_type;
    }

    /**
     * Make class name for connection
     *
     * @return string $database_type
    */
    public function makeConnectionClass($database_type)
    {
        return 'PronouncePHP\Database\Connection\\' . ucfirst($database_type) . 'Connection';
    }

    /**
     * Get connection information for specified database type
     *
     * @return string $database_type
    */
    public function getConnectionInformation($database_type)
    {
        $information = $this->config['connections'][strtolower($database_type)];

        return $information;
    }
}