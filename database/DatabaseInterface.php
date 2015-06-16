<?php

namespace Database;

interface DatabaseInterface 
{
    /**
     * Construct
     *
     * @param Connection $connection
     * @return void
     */
    public function __construct(Connection $connection);

    /**
     * Make database connection
     *
     * @return connection
     */
    public function makeConnection();

    /**
     * Fill database
     *
     * @param connection $db_connection, string $word, string $arpabet_string, string $ipa_string, string $spelling_string
     * @return result
     */
    public function fillDatabase($db_connection, $word, $arpabet_string, $ipa_string, $spelling_string);

    /**
     * Close mysqli connection
     *
     * @param connection $db_connection
     * @return void
     */
    public function closeConnection($db_connection);
}