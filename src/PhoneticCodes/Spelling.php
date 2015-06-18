<?php

namespace PronouncePHP\PhoneticCodes;

class Spelling extends PhoneticCode
{
    /**
     * Build the word pronunciation string
     *
     * @param array $arpabet_array
     * @return string
     */
    public function buildSpellingString($arpabet_array) 
    {
        $spelling_string = '';

        foreach ($arpabet_array as $arpabet_character)
        {
            $library = getArpabetToSpelling();

            $spelling_character = $this->getCharacter($arpabet_character, $library);
            
            $spelling_string .= $spelling_character;
        }

        return $spelling_string;
    }
}