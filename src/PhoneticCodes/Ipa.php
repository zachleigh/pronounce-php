<?php

namespace PronouncePHP\PhoneticCodes;

class Ipa extends PhoneticCode
{
    /**
     * Build the word pronunciation string
     *
     * @param array $arpabet_array
     * @return string
     */
    public function buildIpaString($arpabet_array) 
    {
        $ipa_string = '';

        foreach ($arpabet_array as $arpabet_character)
        {
            $library = getArpabetToIpa();

            $ipa_character = $this->getCharacter($arpabet_character, $library);

            $ipa_string .= $ipa_character;
        }

        return $ipa_string;
    }
}