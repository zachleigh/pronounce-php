<?php

namespace Database;

use Database\DatabaseInterface;
use Database\Connection;

class MysqlDatabase implements DatabaseInterface
{
    private $connection;

    /**
     * Construct
     *
     * @param Connection $connection
     * @return void
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Make mysqli connection
     *
     * @return mysqli
     */
    public function makeConnection()
    {
        $mysqli = new \mysqli($this->connection->host, $this->connection->user, $this->connection->password, $this->connection->database);

        if ($mysqli->connect_error) 
        {
            die('Failed to connect to database' . $mysqli->connect_error);
        }

        return $mysqli;
    }

    /**
     * Fill database
     *
     * @param mysqli $db_connection, string $word, string $arpabet_string, string $ipa_string, string $spelling_string
     * @return result
     */
    public function fillDatabase($db_connection, $word, $arpabet_string, $ipa_string, $spelling_string)
    {
        $mysqli = $db_connection;

        $mysqli->set_charset("utf8");

        $result = $mysqli->query("INSERT INTO $table (word, arpabet, ipa, spelling) VALUES (
            '" . $mysqli->escape_string($word) . "',
            '" . $mysqli->escape_string($arpabet_string) . "',
            '" . $mysqli->escape_string($ipa_string) . "',
            '" . $mysqli->escape_string($spelling_string) . "')"
        );

        if (!$result) {
            echo 'Invalid query: ' . $mysqli->error . "\n" . "\n";
        }

        return $result;
    }

    /**
     * Close mysqli connection
     *
     * @param mysqli $mysqli
     * @return void
     */
    public function closeConnection($db_connection)
    {
        $db_connection->close();
    }
}