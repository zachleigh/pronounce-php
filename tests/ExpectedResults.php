<?php

class ExpectedResults
{
    public function results_single_word_lookup_returns_table()
    {
        return "Searching...
+------+---------+------+----------+
| word | arpabet | ipa  | spelling |
+------+---------+------+----------+
| word | W ER1 D | wɝ'd | wur'd    |
+------+---------+------+----------+
";
    }

    public function results_double_word_lookup_returns_table()
    {
        return "Searching...
+-------+--------------+--------+----------+
| word  | arpabet      | ipa    | spelling |
+-------+--------------+--------+----------+
| bye   | B AY1        | baɪ'   | bahy'    |
| hello | HH AH0 L OW1 | hʌɫoʊ' | huhloh'  |
+-------+--------------+--------+----------+
";
    }

    public function results_multiple_word_lookup_returns_table()
    {
        return "Searching...
+--------+----------------+---------+----------+
| word   | arpabet        | ipa     | spelling |
+--------+----------------+---------+----------+
| blue   | B L UW1        | bɫu'    | bloo'    |
| grey   | G R EY1        | greɪ'   | grey'    |
| orange | AO1 R AH0 N JH | ɔ'rʌndʒ | aw'ruhnj |
| pink   | P IH1 NG K     | pɪ'ŋk   | pi'ngk   |
| purple | P ER1 P AH0 L  | pɝ'pʌɫ  | pur'puhl |
| red    | R EH1 D        | rɛ'd    | re'd     |
+--------+----------------+---------+----------+
";
    }
}