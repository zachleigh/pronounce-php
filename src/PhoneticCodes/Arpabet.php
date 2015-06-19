<?php

namespace PronouncePHP\PhoneticCodes;
use PronouncePHP\PhoneticCodes\Ipa;
use PronouncePHP\PhoneticCodes\Spelling;

class Arpabet
{
    private $file = 'src/Library/cmudict';

    /**
     * Load CMU file
     *
     * @return handle
    */
    public function loadCmuFile()
    {
        $handle = fopen($this->file, 'r') or die('Failed to open dictionary file');

        return $handle;
    }

    /**
     * Find comments in CMU file
     *
     * @param string $line
     * @return bool
    */
    public function isComment($line)
    {
        if (substr($line, 0, 3) === ';;;')
        {
            return true;
        }

        return false;
    }
}