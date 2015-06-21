# Pronounce-PHP

Converts words to pronunciation strings using the Carnegie Mellon University Pronouncing Dictionary (CMUdict) file.  Currently converts to the International Phonetic Alphabet (IPA) and to an easier to read spelling approximation.

At the moment, this program simply outputs tables to the console with no syllable breaks. Syllables and database filling capabilities are coming soon.

## Installation

Download and run 
```
composer install
```

## Usage

#####General syntax overview
```
pronouncephp command argument [options]
```

### Commands
[lookup](https://github.com/zachleigh/pronounce-php#lookup)
#### lookup

Look up a word and print the Arpabet, IPA and Spelling approximation pronunciation strings in the console.
The lookup command takes one argument: the word or words to be looked up.

#####Syntax overview
```
pronouncephp lookup words_to_lookup [options]
```

##### Options
######--fields [-f]
[word, arpabet, ipa, spelling]
Set the fields to be displayed.  Fields must be in a comma seperated list.  All fields are enabled by default. 

######--destination [-d]
[table, string, file]
Set the output destination. Default is to output a table to the console.

######--file
[file_name]
If 'file' is selected for output destination, the 'file' option can be used to set a file name to write to.  The default file name is 'output.txt' and is written to the pronounce-php directory.

##### Examples

Basic usage
```
./pronouncephp lookup hello


+-------+--------------+--------+----------+
| word  | arpabet      | ipa    | spelling |
+-------+--------------+--------+----------+
| hello | HH AH0 L OW1 | hʌɫoʊ' | huhloh'  |
+-------+--------------+--------+----------+

```

A comma seperated list of words may also be given. Note that words will be returned in alphabetical order.

```
/.pronouncephp lookup elephant,zebra,giraffe


+----------+---------------------+----------+------------+
| word     | arpabet             | ipa      | spelling   |
+----------+---------------------+----------+------------+
| elephant | EH1 L AH0 F AH0 N T | ɛ'ɫʌfʌnt | e'luhfuhnt |
| giraffe  | JH ER0 AE1 F        | dʒɝæ'f   | jura'f     |
| zebra    | Z IY1 B R AH0       | zi'brʌ   | zee'bruh   |
+----------+---------------------+----------+------------+
```

Set desired output fields with the --fields option.  Fields will be displayed in the order given.

```
./pronouncephp lookup blue,red,green --fields=word,ipa


+-------+-------+
| word  | ipa   |
+-------+-------+
| blue  | bɫu'  |
| green | gri'n |
| red   | rɛ'd  |
+-------+-------+
```

Set the ouput destination with the --destination option. Only one destination may be choosen.
Setting the destination to 'string' produces a string instead of a table.

```
./pronouncephp lookup desk,chair,pencil --destination=string


word: chair arpabet: CH EH1 R ipa: tʃɛ'r spelling: che'r 
word: desk arpabet: D EH1 S K ipa: dɛ'sk spelling: de'sk 
word: pencil arpabet: P EH1 N S AH0 L ipa: pɛ'nsʌɫ spelling: pe'nsuhl 
```

Setting destination to 'file' writes the output to a file.  The default file is 'output.txt'.

```
./pronouncephp lookup guitar --destination=file


Successfully wrote to output.txt
```

If the destination is set to 'file', use the 'file' option to specify a file to write to.

```
./pronouncephp lookup night,day,noon --destination=file --file=words.txt


Successfully wrote to words.txt
```