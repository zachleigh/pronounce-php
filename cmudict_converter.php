<?php 

use App\Ipa;
use App\Spelling;
use App\database\Database;

$file = 'library/cmudict';

$ipa = new Ipa();
$spelling = new Spelling();
$database = new Database();

// Database settings
$host = $database->host;
$user = $database->user;
$password = $database->password;
$db = $database->database;
$table = $database->table;

/* 
* Set to true to fill mysql database, false to echo string for each line in $file
* Required db columns: word, arpabet, ipa, spelling
*/
$fill_database = false;

//	Connect to database
if ($fill_database)
{
    $mysqli = new mysqli($host, $user, $password, $db);

    if ($mysqli->connect_error) 
    {
    	die('Failed to connect to database' . $mysqli->connect_error);
    }
}

//	Open file
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
    $mysqli->close();
}