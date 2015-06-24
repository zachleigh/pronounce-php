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
     * @return void
    */
    public function __construct(DatabaseInterface $database, OutputInterface $output)
    {
        $this->database = $database;

        $this->output = $output;
    }

}