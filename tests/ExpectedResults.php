<?php

class ExpectedResults
{
    public function results_single_word_lookup_returns_table()
    {
        return "Searching..." . "\n" .
               "+------+---------+------+----------+" . "\n" .
               "| word | arpabet | ipa  | spelling |" . "\n" .
               "+------+---------+------+----------+" . "\n" .
               "| word | W ER1 D | wɝ'd | wur'd    |" . "\n" .
               "+------+---------+------+----------+" . "\n";
    }

    public function results_double_word_lookup_returns_table()
    {
        return "Searching..." . "\n" .
               "+-------+--------------+--------+----------+" . "\n" .
               "| word  | arpabet      | ipa    | spelling |" . "\n" .
               "+-------+--------------+--------+----------+" . "\n" .
               "| bye   | B AY1        | baɪ'   | bahy'    |" . "\n" .
               "| hello | HH AH0 L OW1 | hʌɫoʊ' | huhloh'  |" . "\n" .
               "+-------+--------------+--------+----------+" . "\n";
    }

    public function results_multiple_word_lookup_returns_table()
    {
        return "Searching..." . "\n" .
               "+--------+----------------+---------+----------+" . "\n" .
               "| word   | arpabet        | ipa     | spelling |" . "\n" .
               "+--------+----------------+---------+----------+" . "\n" .
               "| blue   | B L UW1        | bɫu'    | bloo'    |" . "\n" .
               "| grey   | G R EY1        | greɪ'   | grey'    |" . "\n" .
               "| orange | AO1 R AH0 N JH | ɔ'rʌndʒ | aw'ruhnj |" . "\n" .
               "| pink   | P IH1 NG K     | pɪ'ŋk   | pi'ngk   |" . "\n" .
               "| purple | P ER1 P AH0 L  | pɝ'pʌɫ  | pur'puhl |" . "\n" .
               "| red    | R EH1 D        | rɛ'd    | re'd     |" . "\n" .
               "+--------+----------------+---------+----------+" . "\n";
    }

    public function results_misspelled_word_lookup_returns_error()
    {
        return "Searching..." . "\n" .
               "Word lskdfh could not be found" . "\n";
    }

    public function results_single_word_lookup_with_fields_option_returns_table()
    {
        return "Searching..." . "\n" .
               "+-------+----------+" . "\n" .
               "| word  | ipa      |" . "\n" .
               "+-------+----------+" . "\n" .
               "| radio | reɪ'dioʊ |" . "\n" .
               "+-------+----------+" . "\n";
    }

    public function results_multiple_word_lookup_with_fields_option_returns_table()
    {
        return "Searching..." . "\n" .
               "+------------+-----------+-----------+" . "\n" .
               "| word       | ipa       | spelling  |" . "\n" .
               "+------------+-----------+-----------+" . "\n" .
               "| headphones | hɛ'dfoʊnz | he'dfohnz |" . "\n" .
               "| radio      | reɪ'dioʊ  | rey'deeoh |" . "\n" .
               "+------------+-----------+-----------+" . "\n";
    }

    public function results_single_word_lookup_with_destination_string_returns_string()
    {
        return "Searching..." . "\n" .
               "word: insect arpabet: IH1 N S EH2 K T ipa: ɪ'nsɛkt spelling: i'nsekt " . "\n" .
               "" . "\n" .
               "";
    }

    public function results_incorrect_fields_option_returns_error()
    {
        return "Searching...
Incorret field input
Field options: word,arpabet,ipa,spelling
";
    }

    public function results_incorrect_destination_option_returns_error()
    {
        return "Searching..." . "\n" .
               "Incorret destination input" . "\n" .
               "Destination options: table,string,file,database" . "\n" .
               "";
    }
}