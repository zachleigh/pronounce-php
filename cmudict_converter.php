<?php 


use Database\MysqlDatabase as Database;
use Database\Connection;

$file = 'library/cmudict';

$ipa = new Ipa();
$spelling = new Spelling();
$connection = new Connection();
$database = new Database($connection);

/* 
* Set to true to fill mysql database, false to echo string for each line in $file
* Required db columns: word, arpabet, ipa, spelling
*/
$fill_database = false;

if ($fill_database)
{
    $db_connection = $database->makeConnection();
}

$handle = fopen($file, 'r') or die('Failed to open dictionary file');

if ($handle) 
{
    while (($line = fgets($handle)) !== false) 
    {
        if (isComment($line)) 
        {
            continue;
        }

        $exploded_line = explode(' ', $line);

        $word = $exploded_line[0];

        array_shift($exploded_line);

        $arpabet_array = array_filter($exploded_line);

        $arpabet_string = trim(implode(' ', $exploded_line));

        $ipa_string = $ipa->buildIpaString($arpabet_array);

        $spelling_string = $spelling->buildSpellingString($arpabet_array);

        if ($fill_database)
        {
          $result = $database->fillDatabase($db_connection, $word, $arpabet_string, $ipa_string, $spelling_string);
        }
        else
        {
          echo 'Word: ' . $word . "\n" . 'Arpabet: ' . $arpabet_string . ' IPA: ' . $ipa_string . ' Spelling: ' . $spelling_string . "\n\n";
        }
    }
} 
else 
{
    die('Something terrible happened');
} 

//	Close up everything
fclose($handle);

if ($fill_database)
{
    $database->closeConnection($db_connection);
}