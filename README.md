# Pronounce-PHP

Converts words to pronunciation strings using the Carnegie Mellon University Pronouncing Dictionary (CMUdict) file.  Currently converts to the International Phonetic Alphabet (IPA) and to an easier to read spelling approximation.

Currently, this program just outputs strings to the console with no syllable breaks. Syllables and database filling capabilities are coming soon.

## Installation

Download and run 
```
composer install
```

## Usage

#### lookup

Look up a word and print the IPA and Spelling approximation pronunciation strings in the console. 

```
pronouncephp lookup word
```

A comma seperated list of words may also be given. Note that words will be returned in alphabetical order.

```
pronouncephp word1,word2,word3
```

###### Examples:

```
./pronouncephp word pronounce
```
Will produce:
```
Word: PRONOUNCE
Arpabet: P R AH0 N AW1 N S IPA: prʌnaʊ'ns Spelling: pruhnou'ns
```

```
./pronouncephp word book,flavor,elephant,worm
```
Will produce:
```
Word: BOOK
Arpabet: B UH1 K IPA: bʊ'k Spelling: buu'k
Word: ELEPHANT
Arpabet: EH1 L AH0 F AH0 N T IPA: ɛ'ɫʌfʌnt Spelling: e'luhfuhnt
Word: FLAVOR
Arpabet: F L EY1 V ER0 IPA: fɫeɪ'vɝ Spelling: fley'vur
Word: WORM
Arpabet: W ER1 M IPA: wɝ'm Spelling: wur'm
```