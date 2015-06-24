<?php

namespace PronouncePHP\Build;

use Symfony\Component\Console\Output\OutputInterface;
use PronouncePHP\Config\Config;

class Builder
{
    /**
     * Build display table
     *
     * @param Table $table, array $answers
     * @return Table
    */
    public function buildTable($table, array $answers)
    {
        $headers = [];

        $rows = [];

        foreach ($answers as $answer)
        {
            $row = [];

            foreach ($answer as $header => $row_item) 
            {                
                if (!in_array($header, $headers))
                {
                    array_push($headers, $header);
                }

                array_push($row, $row_item);
            }

            array_push($rows, $row);
        }

        $table->setHeaders($headers)
              ->setRows($rows);
        
        return $table;
    }

    /**
     * Build display string
     *
     * @param array $answers
     * @return string
    */
    public function buildString(array $answers)
    {
        $string = '';

        foreach ($answers as $answer)
        {
            foreach ($answer as $key => $value) 
            {                
                $string .= '<info>' . $key . ': </info>' . $value . ' ';
            }

            $string .= "\n";
        }

        return $string;
    }

    /**
     * Build file ouput line
     *
     * @param array $answer
     * @return string
    */
    public function buildFileOutput(array $answer)
    {
        $string = '';

        foreach ($answer as $key => $value) 
        {                
            $string .= $value . ' / ';
        }

        return $string;
    }

    /**
     * Get database connection
     *
     * @param OutputInterface $output
     * @return Connection
    */
    public function buildDatabaseClass(OutputInterface $output)
    {
        $database_type = Config::get('database');

        $database_class = $this->makeConnectionClass($database_type);

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
     * Make class name for connection
     *
     * @return string $database_type
    */
    private function makeConnectionClass($database_type)
    {
        return 'PronouncePHP\Database\Databases\\' . ucfirst($database_type) . 'Database';
    }
}