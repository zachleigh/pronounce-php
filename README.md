# Pronounce-PHP

Converts words to pronunciation strings using the Carnegie Mellon University Pronouncing Dictionary (CMUdict) file.  Currently converts to the International Phonetic Alphabet (IPA) and to an easier to read spelling approximation.

At the moment, this program simply outputs tables to the console with no syllable breaks. Syllables and database filling capabilities are coming soon.

## Installation

Download and run 
```
composer install
```

## Usage

Organized by commands

#### lookup

Syntax overview
```
pronouncephp lookup words_to_lookup [options]
```

##### Options
*--fields [-f] : Set the fields to be displayed in the table.  All fields are enabled by default. Choices: word, arpabet, ipa, spelling

Look up a word and print the Arpabet, IPA and Spelling approximation pronunciation strings in the console.

```
./pronouncephp lookup hello

// Output:
// +-------+--------------+--------+----------+
// | word  | arpabet      | ipa    | spelling |
// +-------+--------------+--------+----------+
// | hello | HH AH0 L OW1 | hʌɫoʊ' | huhloh'  |
// +-------+--------------+--------+----------+

```

A comma seperated list of words may also be given. Note that words will be returned in alphabetical order.

```
/.pronouncephp lookup elephant,zebra,giraffe

Output
+----------+---------------------+----------+------------+
| word     | arpabet             | ipa      | spelling   |
+----------+---------------------+----------+------------+
| elephant | EH1 L AH0 F AH0 N T | ɛ'ɫʌfʌnt | e'luhfuhnt |
| giraffe  | JH ER0 AE1 F        | dʒɝæ'f   | jura'f     |
| zebra    | Z IY1 B R AH0       | zi'brʌ   | zee'bruh   |
+----------+---------------------+----------+------------+
```

Set desired table fields with the --fields option

```
./pronouncephp lookup blue,red,green --fields=word,ipa

Output
+-------+-------+
| word  | ipa   |
+-------+-------+
| blue  | bɫu'  |
| green | gri'n |
| red   | rɛ'd  |
+-------+-------+
```