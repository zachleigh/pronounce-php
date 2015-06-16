# cmudict-converter
Converts the Carnegie Mellon University Pronouncing Dictionary (CMUdict) file to the International Phonetic Alphabet (IPA) and to an easier to read spelling approximation. Fills a MYSQL database with the output if desired.

Currently, this program just outputs strings with no syllable breaks. I've tried putting them in, but the number of rules and exceptions to these rules is difficult to deal with. If anybody cares to help out with this, Id be greatful.

** USAGE **
1. Copy src/database/ExampleDatabase.php to Database.php.  Be sure the change the class name in the file to Database.

2. Enter your MYSQL information in Database.php if you wish to fill a database.

3. By default, it will not fill a database.  To change this behavior, change $fill_database on line 24 of cmudict_converter.php to true.

4. Run with 'php index.php'
