<?php

namespace PronouncePHP;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

class Command extends SymfonyCommand
{
    /**
     * Explode by comma
     *
     * @param string $string
     * @return array
    */
    protected function explodeByComma($string)
    {
        $string = strtoupper($string);

        return explode(',', $string);
    }

    /**
     * Find comments in CMU file
     *
     * @param string $line
     * @return bool
    */
    protected function isComment($line)
    {
        if (substr($line, 0, 3) === ';;;')
        {
            return true;
        }

        return false;
    }

    /**
     * Make method names from array of strings
     *
     * @param array $strings
     * @return array
    */
    protected function makeMethodNames(array $strings)
    {
        $names = [];

        foreach ($strings as $string) {
            $string = strtolower($string);

            $name = 'make' . ucfirst($string) . 'String';

            $names[$string] = $name;
        }

        return $names;
    }

    /**
     * make output array for given fields
     *
     * @param OutputInterface $output, string $word, array $exploded_line, array $method_names
     * @return array
    */
    protected function makeOutputArray($output, $word, $exploded_line, array $method_names)
    {
        $answer = [];

        array_shift($exploded_line);

        $arpabet_array = array_filter($exploded_line);

        foreach ($method_names as $field => $method)
        {
            if (!method_exists($this, $method))
            {
                $output->writeln("<error>Incorret field input</error>");
                $output->writeln("<info>Field options: </info><comment>word,arpabet,ipa,spelling</comment>");

                exit();
            }

            $answer[$field] = $this->$method($word, $exploded_line, $arpabet_array);
        }

        return $answer;
    }

    /**
     * Build word string
     *
     * @param string $word, array $exploded_line, array $arpabet_array
     * @return string
    */
    protected function makeWordString($word, array $exploded_line, array $arpabet_array)
    {
        return strtolower($word);
    }

    /**
     * Build arpabet string
     *
     * @param string $word, array $exploded_line, array $arpabet_array
     * @return string
    */
    protected function makeArpabetString($word, array $exploded_line, array $arpabet_array)
    {
        return trim(implode(' ', $exploded_line));
    }

    /**
     * Build ipa string
     *
     * @param string $word, array $exploded_line, array $arpabet_array
     * @return string
    */
    protected function makeIpaString($word, array $exploded_line, array $arpabet_array)
    {
        return $this->transcriber->buildIpaString($arpabet_array);
    }

    /**
     * Build spelling string
     *
     * @param string $word, array $exploded_line, array $arpabet_array
     * @return string
    */
    protected function makeSpellingString($word, array $exploded_line, array $arpabet_array)
    {
        return $this->transcriber->buildSpellingString($arpabet_array);
    }

    /**
     * Display error message for unanswered words
     *
     * @param OutputInterface $output, array $unanswered
     * @return void
    */
    protected function displayErrorForUnanswered($output, array $unanswered)
    {
        foreach ($unanswered as $word)
        {
            $word = strtolower($word);
            
            $output->writeln("<error>Word $word could not be found</error>");
        }
    }
}