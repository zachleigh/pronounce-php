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

class Command extends SymfonyCommand
{
    /**
     * Make default options array.
     */
    protected function makeOptions()
    {
        $this->options = [
            'destination' => '',
            'fields' => '',
            'file_name' => '',
            'hyphenate' => '',
            'multiple' => '',
            'symbol' => '',
        ];
    }

    /**
     * Set options field.
     *
     * @param string $options_field, string $value
     *
     * @return string
     */
    protected function setOptionsField($options_field, $value)
    {
        $this->options[$options_field] = $value;
    }

    /**
     * Build word string.
     *
     * @param string $word, array $exploded_line, array $arpabet_array
     *
     * @return string
     */
    protected function makeWordString($word, array $exploded_line, array $arpabet_array)
    {
        return strtolower($word);
    }

    /**
     * Build arpabet string.
     *
     * @param string $word, array $exploded_line, array $arpabet_array
     *
     * @return string
     */
    protected function makeArpabetString($word, array $exploded_line, array $arpabet_array)
    {
        return trim(implode(' ', $exploded_line));
    }

    /**
     * Build ipa string.
     *
     * @param string $word, array $exploded_line, array $arpabet_array
     *
     * @return string
     */
    protected function makeIpaString($word, array $exploded_line, array $arpabet_array)
    {
        return $this->transcriber->buildIpaString($arpabet_array);
    }

    /**
     * Build spelling string.
     *
     * @param string $word, array $exploded_line, array $arpabet_array
     *
     * @return string
     */
    protected function makeSpellingString($word, array $exploded_line, array $arpabet_array)
    {
        return $this->transcriber->buildSpellingString($arpabet_array);
    }

    /**
     * Build hyphenated word string.
     *
     * @param string $word, array $exploded_line, array $arpabet_array
     *
     * @return string
     */
    protected function makeHyphenatedWordString($word, array $exploded_line, array $arpabet_array)
    {
        if (ctype_alpha($word)) {
            $hyphenated_word = $this->hyphenateOutputWord($this->hyphenator, $word, true);

            $hyphenated_word = $this->setHyphenationSymbol($hyphenated_word);

            return $hyphenated_word;
        }

        return strtolower($word);
    }

    /**
     * Display error message for unanswered words.
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, array $unanswered
     */
    protected function displayErrorForUnanswered($output, array $unanswered)
    {
        foreach ($unanswered as $word) {
            $word = strtolower($word);

            $output->writeln("<error>Word $word could not be found</error>");
        }
    }

    /**
     * Output data to table.
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, array $answers
     */
    protected function outputToTable($output, array $answers)
    {
        $table = new Table($output);

        $this->builder->buildTable($table, $answers);

        $table->render();
    }

    /**
     * Output data to string.
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, array $answers
     */
    protected function outputToString($output, array $answers)
    {
        $string = $this->builder->buildString($answers);

        $output->writeln($string);
    }

    /**
     * Output data to file.
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, array $answers, string $file_name
     */
    protected function outputToFile($output, array $answers)
    {
        $this->setOptionsField('file_name', $this->makeFileName($this->options['file_name']));

        $handle = $this->getFileHandle($output);

        $file = $this->openFile($output, $handle);

        foreach ($answers as $answer) {
            $this->writeFileLine($file, $answer);
        }

        $output->writeln("<info>Successfully wrote to $file_name</info>");

        $this->closeFile($handle);
    }

    /**
     * Make unique filename.
     *
     * @param string $file_name
     *
     * @return string
     */
    protected function makeFileName($file_name)
    {
        $filesystem = new Filesystem();

        $new_file_name = $file_name;

        $count = 1;

        while ($filesystem->exists($new_file_name)) {
            if (strpos($file_name, '.') !== false) {
                $index = strpos($file_name, '.');

                $new_file_name = substr($file_name, 0, $index).$count.substr($file_name, $index);
            } else {
                $new_file_name = $file_name.$count;
            }

            $count += 1;
        }

        return $new_file_name;
    }

    /**
     * Get file handle.
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, Symfony\Component\Filesystem\Filesystem $filesystem
     *
     * @return resource
     */
    protected function getFileHandle(OutputInterface $output)
    {
        $filesystem = new Filesystem();

        $filesystem->touch($this->options['file_name']);

        $handle = fopen($this->options['file_name'], 'w') or die('<error>Failed to open destination file</error>');

        return $handle;
    }

    /**
     * Open file for output.
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, resource $handle
     *
     * @return ymfony\Component\Console\Output\StreamOutput
     */
    protected function openFile(OutputInterface $output, $handle)
    {
        $file = new StreamOutput($handle);

        if (!$file) {
            $output->writeln('<error>Error with destination file</error>');

            $GLOBALS['status'] = 1;

            fclose($handle);

            return;
        }

        return $file;
    }

    /**
     * Write line to file.
     *
     * @param Symfony\Component\Console\Output\StreamOutput $file, array $answer
     */
    protected function writeFileLine($file, array $answer)
    {
        $line = $this->builder->buildFileLine($answer);

        $file->writeln($line);
    }

    /**
     * Close file for output.
     *
     * @param resource $handle
     */
    protected function closeFile($handle)
    {
        fclose($handle);
    }

    /**
     * Output data to database.
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, array $answers
     */
    protected function outputToDatabase($output, array $answers)
    {
        $connect = $this->getDatabaseConnection($output);

        $handle = $connect->handle();

        $connect->insertDataArray($handle, $answers);

        $output->writeln('<info>Successfully wrote to database</info>');

        $handle = null;
    }

    /**
     * Get database connection.
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return PronouncePHP\Database\Connect $connect
     */
    protected function getDatabaseConnection(OutputInterface $output)
    {
        $database_class = $this->builder->buildDatabaseClass($output);

        $connect = new Connect($database_class, $output);

        return $connect;
    }

    /**
     * Hyphenate word for output.
     *
     * @param PronouncePHP\Hyphenate\Hyphenator $hyphenator, string $word
     *
     * @return string
     */
    protected function hyphenateOutputWord(Hyphenator $hyphenator, $word)
    {
        if ($this->options['hyphenate'] === false) {
            return $word;
        }

        return $hyphenator->hyphenateWord($word);
    }

    /**
     * Set hyphenation symbol.
     *
     * @param string $word
     *
     * @return string
     */
    protected function setHyphenationSymbol($word)
    {
        return str_replace(' ', $this->options['symbol'], $word);
    }

    /**
     * Deal with multiple entries in CMUdict file.
     *
     * @param string $word
     *
     * @return string
     */
    protected function parseDuplicateEntries($word)
    {
        if ($this->options['multiple'] === 'none') {
            return $word;
        } elseif ($this->options['multiple'] === 'repeat') {
            return preg_replace("/\([^)]+\)/", '', $word);
        }

        return $word;
    }

    /**
     * Make unique key for answers array.
     *
     * @param string $word, array $answers
     *
     * @return string
     */
    protected function makeAnswersKey($word, array $answers)
    {
        $key = $word;

        $count = 1;

        while (array_key_exists($key, $answers)) {
            $key = $key.$count;

            $count += 1;
        }

        return $key;
    }
}
