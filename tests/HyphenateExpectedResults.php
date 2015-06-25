<?php

class HyphenateExpectedResults
{
    public function results_single_word_hyphenate_returns_table()
    {
        return "Hyphenating..." . "\n" .
               "+-------+-----------------+" . "\n" .
               "| word  | hyphenated_word |" . "\n" .
               "+-------+-----------------+" . "\n" .
               "| hotel | ho-tel          |" . "\n" .
               "+-------+-----------------+" . "\n";
    }

    public function results_multiple_word_hyphenate_returns_table()
    {
        return "Hyphenating..." . "\n" .
               "+-----------+-----------------+" . "\n" .
               "| word      | hyphenated_word |" . "\n" .
               "+-----------+-----------------+" . "\n" .
               "| monkey    | mon-key         |" . "\n" .
               "| serious   | se-ri-ous       |" . "\n" .
               "| bookshelf | book-shelf      |" . "\n" .
               "+-----------+-----------------+" . "\n";
    }

    public function results_word_hyphenate_with_destination_string_returns_string()
    {
        return "Hyphenating..." . "\n" .
               "word: monkey hyphenated_word: mon-key " . "\n" .
               "word: serious hyphenated_word: se-ri-ous " . "\n" .
               "word: bookshelf hyphenated_word: book-shelf " . "\n" .
               "" . "\n" .
               "";
    }
}