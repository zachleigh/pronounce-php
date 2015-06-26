# Pronounce-PHP

-Converts words to pronunciation strings using the Carnegie Mellon University Pronouncing Dictionary (CMUdict) file.  Currently converts to the International Phonetic Alphabet (IPA) and to an easier to read spelling approximation.  
-Hyphenates English words.  Hyphenation for IPA and spelling approximation hopefully coming soon.
-Outputs to the console, to a file, or to a database

#### Contents
###### [Installation](#installation)   
###### [Usage](#usage)   
* [Commands](#commands)   
  * [all](#all)   
  * [hyphenate](#hyphenate)   
  * [lookup](#lookup)   
* [Database Usage](#database-usage)   

## Installation

##### Requirements 
###### PHP 5.3.9 or higher
Linux users can find PHP releases in their distribution repositories.
For other operating systems, please visit the [php installation guide](http://php.net/manual/en/install.php) for instructions.

###### composer
Check the [composer documentation](https://getcomposer.org/doc/00-intro.md) for installation instructions.

##### Install
If requirements are met, you can install the package in two ways.

###### Download
Download [here](https://github.com/zachleigh/pronounce-php/releases) and run 
```
composer install
```   

###### Through composer
```
composer require zachleigh/pronounce-php
```
Once everything is installed, the program will be in vendor/zachleigh/pronounce-php

## Usage

#####General syntax overview
```
pronounce-php command argument [options]
```

## Commands
* [all](#all)      
* [hyphenate](#hyphenate)      
* [lookup](#lookup)    
### all

Output the entire CMUdict file with arpabet, hyphenation, IPA, and spelling approximation strings to either a file or a database.  Default is to write to a file called 'output.txt'.

##### Syntax overview
```
pronounce-php all [options]
```

##### Options 
###### --destination [-d]
Set the output destination. Default is to output a table to the console.  If file is selected, fields will be seperated by a forward slash (/) surrounded by spaces.  
Available desitinations: [file, database]
```
pronounce-php all --destination=database
```

###### --file [-o]
If 'file' is selected for the output destination, the 'file' option can be used to set a file name to write to.  The default file name is 'output.txt' and is written to the pronounce-php directory.
```
pronounce-php all --destination=file --file=my_file.txt
```

###### --symbol [-s]   
Set the character to be used for hyphenation.  The default value is a hyphen (-).  Note: if writing to a file, keep in mind that items in the file will be divided by forward slashes(/) so setting the hyphenation symbol to forward slash will complicate reading of the file.
```
pronounce-php all --symbol=.
```

##### Examples

Basic usage
```
./pronounce-php all


Lines in file will look like this:
accepting / ac-cept-ing / AE0 K S EH1 P T IH0 NG / æksɛ'ptɪŋ / akse'pting /
```

Change the symbol used to divide word with the 'symbol' option.
```
./pronounce-php all --symbol=.


Lines in file will look like this:
accepting / ac.cept.ing / AE0 K S EH1 P T IH0 NG / æksɛ'ptɪŋ / akse'pting /
```

Set the ouput destination with the 'destination' option. Only one destination may be choosen.
If 'destination' is set to 'file' (the default value), use the 'file' option to specify a file to write to.

```
./pronounce-php all --file=all.txt


Successfully wrote to all.txt
```

If 'destination' is set to 'database', database credentials will be read from .env and configuration will be read from config.php. 
```
./pronouncephp all --destination=database


Successfully wrote to database
```

### hyphenate

Hyphenate a word or words.  Note that this function is mostly accurate, but there may be some errors.  If you find an error, please report it so I can add the word to the exception list.

##### Syntax overview
```
pronounce-php hyphenate words_to_hyphenate [options]
```

##### Options
###### --destination [-d]  
Set the output destination. Default is to output a table to the console.  If file is selected, fields will be seperated by a forward slash (/) surrounded by spaces.  
Available desitinations: [table, string, file, database]
```
pronounce-php hyphenate words_to_hyphenate --destination=file
```

###### --file [-o]   
If 'file' is selected for the output destination, the 'file' option can be used to set a file name to write to.  The default file name is 'output.txt' and is written to the pronounce-php directory.
```
pronounce-php hyphenate words_to_hyphenate --destination=file --file=my_file.txt
```

###### --symbol [-s]   
Set the character to be used for hyphenation.  The default value is a hyphen (-). Note: if writing to a file, keep in mind that items in the file will be divided by forward slashes(/) so setting the hyphenation symbol to forward slash will complicate reading of the file.
```
pronounce-php hyphenate words_to_hyphenate --symbol=/
```

##### Examples

Basic usage
```
./pronounce-php hyphenate hello


+-------+-----------------+
| word  | hyphenated_word |
+-------+-----------------+
| hello | hel-lo          |
+-------+-----------------+
```

A comma seperated list of words may also be given.
```
./pronounce-php hyphenate basket,curtain,hyphenate


+-----------+-----------------+
| word      | hyphenated_word |
+-----------+-----------------+
| basket    | bas-ket         |
| curtain   | cur-tain        |
| hyphenate | hy-phen-ate     |
+-----------+-----------------+
``` 

Change the symbol used to divide word with the 'symbol' option.
```
./pronounce-php hyphenate machine --symbol=.


+---------+-----------------+
| word    | hyphenated_word |
+---------+-----------------+
| machine | ma.chine        |
+---------+-----------------+
```

Set the ouput destination with the 'destination' option. Only one destination may be choosen.
Setting 'destination' to 'string' produces a string instead of a table.
```
./pronounce-php hyphenate flower,mountain --destination=string


word: flower hyphenated word: flower 
word: mountain hyphenated_word: moun-tain 
```

Setting 'destination' to 'file' writes the output to a file.  The default file is 'output.txt'.

```
./pronounce-php hyphenate cupcakes,headphones --destination=file


Successfully wrote to output.txt
```
ouput.txt
```
cupcakes / cup-cakes / 
headphones / head-phones / 
```

If 'destination' is set to 'file', use the 'file' option to specify a file to write to.

```
./pronounce-php hyphenate reading,eating,shopping --destination=file --file=hyphen.txt


Successfully wrote to hyphen.txt
```
hyphen.txt
```
reading / read-ing / 
eating / eat-ing / 
shopping / shop-ping / 
```

If 'destination' is set to 'database', database credentials will be read from .env and configuration will be read from config.php. 
```
./pronouncephp hyphenate goodbye --destination=database


Successfully wrote to database
```

### lookup

Look up a word and output the Arpabet, IPA and Spelling approximation pronunciation strings.
The lookup command takes one argument: the word or words to be looked up.

##### Syntax overview
```
pronounce-php lookup words_to_lookup [options]
```

##### Options
###### --destination [-d]  
Set the output destination. Default is to output a table to the console.  If 'file' is selected, fields will be seperated by a forward slash (/) surrounded by spaces.  
Available desitinations: [table, string, file, database]
```
pronounce-php lookup words_to_lookup --destination=string
```

###### --fields [-f]  
Set the output fields to be displayed.  Fields must be in a comma seperated list.  All fields are enabled by default.  
Available fields: [word, arpabet, ipa, spelling]
```
pronounce-php lookup words_to_lookup --fields=word,arpabet,ipa,spelling
```

###### --file [-o]
If 'file' is selected for the output destination, the 'file' option can be used to set a file name to write to.  The default file name is 'output.txt' and is written to the pronounce-php directory.
```
pronounce-php lookup words_to_lookup --destination=file --file=my_file.txt
```

###### --hyphenate [-y]  
If the 'hyphenate' flag is given, applicable fields will be hyphenated.  Currently, only the 'word' field may be hyphenated.
```
pronounce-php lookup words_to_lookup --hyphenate
```

###### --symbol [-s]   
Set the character to be used for hyphenation.  The default value is a hyphen (-). Note: if writing to a file, keep in mind that items in the file will be divided by forward slashes(/) so setting the hyphenation symbol to forward slash will complicate reading of the file.
```
pronounce-php lookup words_to_lookup --symbol=_
```

##### Examples

Basic usage
```
./pronounce-php lookup hello


+-------+--------------+--------+----------+
| word  | arpabet      | ipa    | spelling |
+-------+--------------+--------+----------+
| hello | HH AH0 L OW1 | hʌɫoʊ' | huhloh'  |
+-------+--------------+--------+----------+

```

A comma seperated list of words may also be given. Note that words will be returned in alphabetical order.
```
./pronounce-php lookup elephant,zebra,giraffe


+----------+---------------------+----------+------------+
| word     | arpabet             | ipa      | spelling   |
+----------+---------------------+----------+------------+
| elephant | EH1 L AH0 F AH0 N T | ɛ'ɫʌfʌnt | e'luhfuhnt |
| giraffe  | JH ER0 AE1 F        | dʒɝæ'f   | jura'f     |
| zebra    | Z IY1 B R AH0       | zi'brʌ   | zee'bruh   |
+----------+---------------------+----------+------------+
```

Using the 'hyphenate' flag hyphenates the 'word' field.
```
./pronounce-php lookup money,coffee,schedule --hyphenate


+-----------+------------------+----------+----------+
| word      | arpabet          | ipa      | spelling |
+-----------+------------------+----------+----------+
| cof-fee   | K AA1 F IY0      | kɑ'fi    | ko'fee   |
| mon-ey    | M AH1 N IY0      | mʌ'ni    | muh'nee  |
| sched-ule | S K EH1 JH UH0 L | skɛ'dʒʊɫ | ske'juul |
+-----------+------------------+----------+----------+
```

Use the 'symbol' option to set the character used for hyphenation.
```
./pronounce-php lookup monkey,furry --hyphenate --symbol=~


+---------+----------------+--------+-----------+
| word    | arpabet        | ipa    | spelling  |
+---------+----------------+--------+-----------+
| fur~ry  | F ER1 IY0      | fɝ'i   | fur'ee    |
| mon~key | M AH1 NG K IY0 | mʌ'ŋki | muh'ngkee |
+---------+----------------+--------+-----------+
```

Set desired output fields with the 'fields' option.  Fields will be displayed in the order given.
```
./pronounce-php lookup blue,red,green --fields=word,ipa


+-------+-------+
| word  | ipa   |
+-------+-------+
| blue  | bɫu'  |
| green | gri'n |
| red   | rɛ'd  |
+-------+-------+
```

Set the ouput destination with the 'destination' option. Only one destination may be choosen.   
Setting 'destination' to 'string' produces a string instead of a table.
```
./pronounce-php lookup desk,chair,pencil --destination=string


word: chair arpabet: CH EH1 R ipa: tʃɛ'r spelling: che'r 
word: desk arpabet: D EH1 S K ipa: dɛ'sk spelling: de'sk 
word: pencil arpabet: P EH1 N S AH0 L ipa: pɛ'nsʌɫ spelling: pe'nsuhl 
```

Setting 'destination' to 'file' writes the output to a file.  The default file is 'output.txt'.
```
./pronounce-php lookup guitar --destination=file


Successfully wrote to output.txt
```
ouput.txt
```
guitar / G IH0 T AA1 R / gɪtɑ'r / gito'r /
```

If 'destination' is set to 'file', use the 'file' option to specify a file to write to.
```
./pronounce-php lookup night,day,noon --destination=file --file=words.txt


Successfully wrote to words.txt
```
words.txt
```
day / D EY1 / deɪ' / dey' / 
night / N AY1 T / naɪ't / nahy't / 
noon / N UW1 N / nu'n / noo'n /
```

If 'destination' is set to 'database', database credentials will be read from .env and configuration will be read from config.php. 
```
./pronouncephp lookup goodbye --destination=database


Successfully wrote to database
```

## Database Usage

##### Requirements

If you wish to fill a database with the information gained from using this program, you must be sure that your database meets the following requirements:
* Tables must have an auto-incrementing 'id' column
* Column names must exactly match the expected field names.
  * Hyphenate field names: 'word', 'hyphenated_word'   
  * Lookup field names: 'word', 'arpabet', 'ipa', 'spelling'   

Use the 'field' option to set which fields you wish to insert into your database.

##### Setup

First, copy the .env.example file (found in the pronounce-php root folder) to a new file called .env.  Open the .env file in a text editor and enter applicable database information.

Next, open config.php in a text-editor.  In the 'database' field, enter in the database type you are using. Currently, only Mysql is supported (see below for information about other database types).  If you wish, you can change the charset in the 'connections' field, but the default 'utf8' should satisfy most people. That should be all you have to do.  The other information in the file is pulled in from the .env file you setup in the previous step.

##### Other database types

The database connection uses php PDO drivers that can be changed out fairly easily.  Currently, PDO supports 12 database types.  Check the [driver list](http://php.net/manual/en/pdo.drivers.php) for more information.  If you wish to make an adapter for one of these database types, adapter name rules must be followed.
* Cuprid: **CupridDatabase**
* FreeTDS / Microsoft SQL Server / Sybase: **DblibDatabase**
* Firebird: **FirebirdDatabase**
* IBM DB2: **IbmDatabase**
* IBM Informix Dynamic Server: **InformixDatabase**
* MySQL: **MysqlDatabase**
* Oracle Call Interface: **OciDatabase**
* ODBC v3 (IBM DB2, unixODBC and win32 ODBC): **OdbcDatabase**
* PostgreSQL: **PgsqlDatabase**
* SQLite 3 and SQLite 2: **SqliteDatabase**
* Microsoft SQL Server / SQL Azure: **SqlsrvDatabase**
* 4d: **FourD** (A class naming rule exception exists for this, but it is untested)

The adapter class should be in its own file in src/Database/Databases/ and must implement DatabaseInterface. If you make a new adapter, please let me know so I can include it in the main program.

Besides making an adapter, you will also have to make a new array for the database in 'connections' in config.php.