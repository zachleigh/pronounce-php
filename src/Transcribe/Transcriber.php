<?php

namespace PronouncePHP\Transcribe;

class Transcriber
{
    /**
     * Load CMU file
     *
     * @return file handle
    */
    public function loadCmuFile()
    {
        $file = 'src/Transcribe/cmudict';

        $handle = fopen($file, 'r') or die('<error>Failed to open dictionary file</error>');

        return $handle;
    }

    /**
     * Build word IPA pronunciation string
     *
     * @param array $arpabet_array
     * @return string
     */
    public function buildIpaString($arpabet_array) 
    {
        $library = getArpabetToIpa();

        return $this->buildPronunciationString($arpabet_array, $library);
    }

    /**
     * Build word spelling pronunciation string
     *
     * @param array $arpabet_array
     * @return string
     */
    public function buildSpellingString($arpabet_array) 
    {
        $library = getArpabetToSpelling();

        return $this->buildPronunciationString($arpabet_array, $library);
    }

    /**
     * Build word pronunciation string
     *
     * @param array $arpabet_array, array $library
     * @return string
     */
    public function buildPronunciationString($arpabet_array, $library)
    {
        $string = '';

        foreach ($arpabet_array as $arpabet_character)
        {
            $character = $this->getCharacter($arpabet_character, $library);

            $string .= $character;
        }

        return $string;
    }

    /**
     * Get character from given library
     *
     * @param string $arpabet_character, array $library
     * @return string $new_character
     */
    protected function getCharacter($arpabet_character, $library)
    {
        $stress = $this->getStress($arpabet_character);

        $arpabet_character = preg_replace('/[0-9]+/', '', $arpabet_character);

        $arpabet_character = trim($arpabet_character);

        $new_character = $library[$arpabet_character] . $stress;

        return $new_character;
    }

    /**
     * Check for and get stress on the character
     *
     * @param string $character
     * @return string $stress
     */
    protected function getStress($character)
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