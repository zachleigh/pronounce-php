<?php

namespace PronouncePHP\PhoneticCodes;

class PhoneticCode
{
    /**
     * Get character from given library
     *
     * @param string $arpabet_character, array $library
     * @return string $new_character
     */
    protected function getCharacter($arpabet_character, $library)
    {
        $stress = $this->hasStress($arpabet_character);

        $arpabet_character = preg_replace('/[0-9]+/', '', $arpabet_character);

        $arpabet_character = trim($arpabet_character);

        $new_character = $library[$arpabet_character] . $stress;

        return $new_character;
    }

    /**
     * Check for stress on the character
     *
     * @param string $character
     * @return string $stress
     */
    protected function hasStress($character)
    {
        $stress = '';

        $is_vowel = preg_match('/[0-9]/', $character, $number);

        if ($is_vowel === 1) 
        {
            if ($number[0] == 1) {
                $stress = '\'';
            }
        }

        return $stress;
    }
}