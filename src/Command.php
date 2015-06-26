<?php

namespace PronouncePHP;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Output\OutputInterface;
use PronouncePHP\Hyphenate\Hyphenator;
use PronouncePHP\Build\Builder;
use PronouncePHP\Database\Connect;
use PronouncePHP\Config\Config;

class Command extends SymfonyCommand
{
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
     * Build hyphenated word string
     *
     * @param string $word, array $exploded_line, array $arpabet_array, string $symbol
     * @return string
    */
    protected function makeHyphenatedWordString($word, array $exploded_line, array $arpabet_array, $symbol)
    {
        if (ctype_alpha($word))
        {
            $hyphenated_word = $this->hyphenateOutputWord($this->hyphenator, $word, true);

            $hyphenated_word = $this->setHyphenationSymbol($hyphenated_word, $symbol);

            return $hyphenated_word;
        }

        return strtolower($word);
    }

    /**
     * Display error message for unanswered words
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, array $unanswered
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
     * Output data to table
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, array $answers
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
     * @param Symfony\Component\Console\Output\OutputInterface $output, array $answers
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
     * @param Symfony\Component\Console\Output\OutputInterface $output, array $answers, string $file_name
     * @return void
    */
    protected function outputToFile($output, array $answers, $file_name)
    {
        $file_name = $this->makeFileName($file_name);

        $handle = $this->getFileHandle($output, $file_name);

        $file = $this->openFile($output, $handle);

        foreach ($answers as $answer)
        {
            $this->writeFileLine($file, $answer);
        }

        $output->writeln("<info>Successfully wrote to $file_name</info>");

        $this->closeFile($handle);
    }

    /**
     * Make unique filename
     *
     * @param string $file_name
     * @return string
    */
    protected function makeFileName($file_name)
    {
        $filesystem = new Filesystem();

        $count = '';

        while ($filesystem->exists($file_name))
        {
            $count += 1;

            $file_name = 'output' . $count . '.txt';
        }

        return $file_name;
    }

    /**
     * Get file handle
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, Symfony\Component\Filesystem\Filesystem $filesystem, string $file_name
     * @return resource
    */
    protected function getFileHandle(OutputInterface $output, $file_name)
    {
        $filesystem = new Filesystem();

        $filesystem->touch($file_name);

        $handle = fopen($file_name, 'w') or die('<error>Failed to open destination file</error>');

        return $handle;
    }

    /**
     * Open file for output
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, resource $handle
     * @return ymfony\Component\Console\Output\StreamOutput
    */
    protected function openFile(OutputInterface $output, $handle)
    {
        $file = new StreamOutput($handle);

        if (!$file)
        {
            $output->writeln('<error>Error with destination file</error>');

            $GLOBALS['status'] = 1;

            fclose($handle);

            return null;
        }

        return $file;
    }

    /**
     * Write line to file
     *
     * @param ymfony\Component\Console\Output\StreamOutput $file, array $answer
     * @return void
    */
    protected function writeFileLine($file, array $answer)
    {
        $line = $this->builder->buildFileLine($answer);

        $file->writeln($line);
    }

    /**
     * Close file for output
     *
     * @param resource $handle
     * @return void
    */
    protected function closeFile($handle)
    {
        fclose($handle);
    }

    /**
     * Output data to database
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, array $answers
     * @return void
    */
    protected function outputToDatabase($output, array $answers)
    {
        $connect = $this->getDatabaseConnection($output);

        $handle = $connect->database->getHandle($output);

        $connect->database->insertDataArray($handle, $answers, $output);

        $output->writeln("<info>Successfully wrote to database</info>");

        $handle = null;
    }

    /**
     * Get database connection
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output
     * @return PronouncePHP\Database\Connect $connect
    */
    protected function getDatabaseConnection(OutputInterface $output)
    {
        $database_class = $this->builder->buildDatabaseClass($output);

        $connect = new Connect($database_class, $output);

        return $connect;
    }

    /**
     * Hyphenate word for output
     *
     * @param PronouncePHP\Hyphenate\Hyphenator $hyphenator, string $word, bool $hyphenation
     * @return string
    */
    protected function hyphenateOutputWord(Hyphenator $hyphenator, $word, $hyphenation)
    {
        if ($hyphenation === false)
        {
            return $word;
        }

        return $hyphenator->hyphenateWord($word);
    }

    /**
     * Set hyphenation symbol
     *
     * @param string $word, string $symbol
     * @return string
    */
    protected function setHyphenationSymbol($word, $symbol)
    {
        return str_replace(' ', $symbol, $word);
    }
}