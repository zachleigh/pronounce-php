<?php

namespace PronouncePHP;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Output\StreamOutput;

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
    protected function makeStringMethodNames(array $strings)
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

    /**
     * Make method name for destination
     *
     * @param string $destination
     * @return string
    */
    protected function makeDestinationMethodName($destination)
    {
        $destination = strtolower($destination);

        return 'outputTo' . ucfirst($destination);
    }

    /**
     * Output data to table
     *
     * @param OutputInterface $output, array $answers
     * @return void
    */
    protected function outputToTable($output, array $answers)
    {
        $table = new Table($output);

        $this->builder->buildTable($table, $answers);

        $table->render();
    }

    /**
     * Output data to string
     *
     * @param OutputInterface $output, array $answers
     * @return void
    */
    protected function outputToString($output, array $answers)
    {
        $string = $this->builder->buildString($answers);

        $output->writeln($string);
    }

    /**
     * Output data to file
     *
     * @param OutputInterface $output, array $answers, string $file_name
     * @return void
    */
    protected function outputToFile($output, array $answers, $file_name)
    {
        $filesystem = new Filesystem();

        $stream = $file_name;

        $count = '';

        while ($filesystem->exists($stream))
        {
            $count += 1;

            $stream = 'output' . $count . '.txt';
        }

        $filesystem->touch($stream);

        $handle = fopen($stream, 'w') or die('<error>Failed to open destination file</error>');

        $file = new StreamOutput($handle);

        if (!$file)
        {
            $output->writeln('<error>Error with destination file</error>');

            $GLOBALS['status'] = 1;

            fclose($handle);

            return null;
        }

        foreach ($answers as $answer)
        {
            $line = $this->builder->buildFileOutput($answer);

            $file->writeln($line);
        }

        $output->writeln("<info>Successfully wrote to $stream</info>");

        fclose($handle);
    }
}