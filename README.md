# Pronounce-PHP

-Converts words to pronunciation strings using the Carnegie Mellon University Pronouncing Dictionary (CMUdict) file.  Currently converts to the International Phonetic Alphabet (IPA) and to an easier to read spelling approximation.  
-Hyphenates English words.  Hyphenation for IPA and spelling approximation hopefully coming soon.
-Outputs either to the console or to a file. Database integration coming in next release.

## Installation

Download and run 
```
composer install
```

## Usage

#####General syntax overview
```
pronounce-php command argument [options]
```

## Commands
[hyphenate](#hyphenate)   
[lookup](#lookup)
### hyphenate

Hyphenate a word or words.  Note that this function is mostly accurate, but there may be some errors.  If you find an error, please report it so I can add the word to the exception list.

##### Syntax overview
```
pronounce-php hyphenate words_to_hyphenate
```

##### Options
###### --destination [-d]  
Set the output destination. Default is to output a table to the console.  If file is selected, fields will be seperated by a forward slash (/) surrounded by spaces.  
Available desitinations: [table, string, file]
```
pronounce-php hyphenate words_to_hyphenate --destination=file
```

###### --file [-o]   
If 'file' is selected for the output destination, the 'file' option can be used to set a file name to write to.  The default file name is 'output.txt' and is written to the pronounce-php directory.
```
pronounce-php hyphenate words_to_hyphenate --destination=file --file=my_file.txt
```

###### --symbol [-s]   
Set the character to be used for hyphenation.  The default value is a hyphen (-).
```
pronounce-php hyphenate words_to_hyphenate --symbol=/
```

##### Examples

Basic usage
```
./pronounce-php hyphenate hello


+-------+-----------------+
| word  | hyphenated word |
+-------+-----------------+
| hello | hel-lo          |
+-------+-----------------+
```

A comma seperated list of words may also be given.
```
./pronounce-php hyphenate basket,curtain,hyphenate


+-----------+-----------------+
| word      | hyphenated word |
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
| word    | hyphenated word |
+---------+-----------------+
| machine | ma.chine        |
+---------+-----------------+
```

Set the ouput destination with the 'destination' option. Only one destination may be choosen.
Setting 'destination' to 'string' produces a string instead of a table.
```
./pronounce-php hyphenate flower,mountain --destination=string


word: flower hyphenated word: flower 
word: mountain hyphenated word: moun-tain 
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
Available desitinations: [table, string, file]
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
Set the character to be used for hyphenation.  The default value is a hyphen (-).
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