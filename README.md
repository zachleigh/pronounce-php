# cmudict-converter
Converts the Carnegie Mellon University Pronouncing Dictionary (CMUdict) file to the International Phonetic Alphabet (IPA) and to an easier to read spelling approximation. Fills a MYSQL database with the output if desired.

Currently, this program just outputs strings with no syllable breaks. Syllables are coming soon.

# Usage
1. Copy /database/ExampleConnection.php to Connection.php.  Be sure the change the class name in the file to Connection.

2. Enter your MYSQL information in Connection.php if you wish to fill a database.  For other database types, write your own Database file and have it implement DatabaseInterface.php.

3. By default, it will not fill a database.  To change this behavior, change $fill_database on line 19 of cmudict_converter.php to true.

4. Install composer, if you havent already, and run 'composer install'.  Might have to run 'php composer dumpautoload' too.

5. Run with 'php run.php'
