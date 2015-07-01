<?php

class LookupExpectedResults
{
    // No options
    public function results_single_word_lookup_returns_table()
    {
        return 'Searching...'."\n".
               '+------+---------+------+----------+'."\n".
               '| word | arpabet | ipa  | spelling |'."\n".
               '+------+---------+------+----------+'."\n".
               "| word | W ER1 D | wɝ'd | wur'd    |"."\n".
               '+------+---------+------+----------+'."\n";
    }

    public function results_double_word_lookup_returns_table()
    {
        return 'Searching...'."\n".
               '+-------+--------------+--------+----------+'."\n".
               '| word  | arpabet      | ipa    | spelling |'."\n".
               '+-------+--------------+--------+----------+'."\n".
               "| bye   | B AY1        | baɪ'   | bahy'    |"."\n".
               "| hello | HH AH0 L OW1 | hʌɫoʊ' | huhloh'  |"."\n".
               '+-------+--------------+--------+----------+'."\n";
    }

    public function results_multiple_word_lookup_returns_table()
    {
        return 'Searching...'."\n".
               '+--------+----------------+---------+----------+'."\n".
               '| word   | arpabet        | ipa     | spelling |'."\n".
               '+--------+----------------+---------+----------+'."\n".
               "| blue   | B L UW1        | bɫu'    | bloo'    |"."\n".
               "| grey   | G R EY1        | greɪ'   | grey'    |"."\n".
               "| orange | AO1 R AH0 N JH | ɔ'rʌndʒ | aw'ruhnj |"."\n".
               "| pink   | P IH1 NG K     | pɪ'ŋk   | pi'ngk   |"."\n".
               "| purple | P ER1 P AH0 L  | pɝ'pʌɫ  | pur'puhl |"."\n".
               "| red    | R EH1 D        | rɛ'd    | re'd     |"."\n".
               '+--------+----------------+---------+----------+'."\n";
    }

    public function results_misspelled_word_lookup_returns_error()
    {
        return 'Searching...'."\n".
               'Word lskdfh could not be found'."\n";
    }

    // Fields
    public function results_single_word_lookup_with_fields_option_returns_table()
    {
        return 'Searching...'."\n".
               '+-------+----------+'."\n".
               '| word  | ipa      |'."\n".
               '+-------+----------+'."\n".
               "| radio | reɪ'dioʊ |"."\n".
               '+-------+----------+'."\n";
    }

    public function results_multiple_word_lookup_with_fields_option_returns_table()
    {
        return 'Searching...'."\n".
               '+------------+-----------+-----------+'."\n".
               '| word       | ipa       | spelling  |'."\n".
               '+------------+-----------+-----------+'."\n".
               "| headphones | hɛ'dfoʊnz | he'dfohnz |"."\n".
               "| radio      | reɪ'dioʊ  | rey'deeoh |"."\n".
               '+------------+-----------+-----------+'."\n";
    }

    public function results_incorrect_fields_option_returns_error()
    {
        return 'Searching...'."\n".
               'Incorret field input'."\n".
               'Field options: word,arpabet,ipa,spelling'."\n";
    }

    // Destination option
    public function results_single_word_lookup_with_destination_string_returns_string()
    {
        return 'Searching...'."\n".
               "word: insect arpabet: IH1 N S EH2 K T ipa: ɪ'nsɛkt spelling: i'nsekt "."\n".
               ''."\n".
               '';
    }

    public function results_multiple_word_lookup_with_destination_string_returns_string()
    {
        return 'Searching...'."\n".
               "word: insect arpabet: IH1 N S EH2 K T ipa: ɪ'nsɛkt spelling: i'nsekt "."\n".
               "word: television arpabet: T EH1 L AH0 V IH2 ZH AH0 N ipa: tɛ'ɫʌvɪʒʌn spelling: te'luhvizhuhn "."\n".
               ''."\n".
               '';
    }

    public function results_incorrect_destination_option_returns_error()
    {
        return 'Searching...'."\n".
               'Incorret destination input'."\n".
               'Destination options: table,string,file,database'."\n".
               '';
    }

    // Hyphenation option
    public function results_word_hyphenation_returns_table()
    {
        return 'Searching...'."\n".
             '+--------+-------------+-------+----------+'."\n".
             '| word   | arpabet     | ipa   | spelling |'."\n".
             '+--------+-------------+-------+----------+'."\n".
             "| mon-ey | M AH1 N IY0 | mʌ'ni | muh'nee  |"."\n".
             '+--------+-------------+-------+----------+'."\n";
    }

    public function results_word_hyphenation_with_symbol_returns_table()
    {
        return 'Searching...'."\n".
             '+------------+-----------------------+-----------+--------------+'."\n".
             '| word       | arpabet               | ipa       | spelling     |'."\n".
             '+------------+-----------------------+-----------+--------------+'."\n".
             "| com.put.er | K AH0 M P Y UW1 T ER0 | kʌmpju'tɝ | kuhmpyoo'tur |"."\n".
             '+------------+-----------------------+-----------+--------------+'."\n";
    }

    // Multiple option
    public function results_multiple_repeat_returns_table()
    {
        return 'Searching...'."\n".
             '+-----------+-------------------------+-----------+-------------+'."\n".
             '| word      | arpabet                 | ipa       | spelling    |'."\n".
             '+-----------+-------------------------+-----------+-------------+'."\n".
             "| abkhazian | AE0 B K AA1 Z IY0 AH0 N | æbkɑ'ziʌn | abko'zeeuhn |"."\n".
             "| abkhazian | AE0 B K AE1 Z IY0 AH0 N | æbkæ'ziʌn | abka'zeeuhn |"."\n".
             "| abkhazian | AE0 B K AA1 Z Y AH0 N   | æbkɑ'zjʌn | abko'zyuhn  |"."\n".
             "| abkhazian | AE0 B K AE1 Z Y AH0 N   | æbkæ'zjʌn | abka'zyuhn  |"."\n".
             '+-----------+-------------------------+-----------+-------------+'."\n";
    }
}
