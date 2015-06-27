<?php

namespace PronouncePHP;

use PronouncePHP\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use PronouncePHP\Transcribe\Transcriber;
use PronouncePHP\Hyphenate\Hyphenator;
use PronouncePHP\Build\Builder;

class AllCommand extends Command
{
    protected $transcriber;

    protected $hyphenator;

    protected $builder;

    protected $options;

    /**
     * Construct
     *
     * @param PronouncePHP\Transcribe\Transcriber $transcribe, PronouncePHP\Hyphenate\Hyphenator $hyphenator, PronouncePHP\Build\Builder $builder
     * @return void
    */
    public function __construct(Transcriber $transcriber, Hyphenator $hyphenator, Builder $builder)
    {
        $this->transcriber = $transcriber;
        $this->hyphenator = $hyphenator;
        $this->builder = $builder;

        parent::__construct();
    }

    /**
     * Configure command
     *
     * @return void
    */
    public function configure()
    {
        $this->setName('all')
             ->setDescription('Output pronunciation and hyphenation for all words in CMUdict')
             ->addOption('destination', 'd', InputOption::VALUE_REQUIRED, 'Select the destination for output', 'file')
             ->addOption('fields', 'f', InputOption::VALUE_REQUIRED, 'Select the output fields you wish to display', 'word,hyphenated_word,arpabet,ipa,spelling')
             ->addOption('file', 'o', InputOption::VALUE_REQUIRED, 'Set file path for output', 'output.txt')
             ->addOption('multiple', 'm', InputOption::VALUE_REQUIRED, 'Set behavior for duplicate entries in CMUdict file', 'none')
             ->addOption('symbol', 's', InputOption::VALUE_REQUIRED, 'Set the symbol used to divide words', '-');
    }

    /**
     * Execute the command
     *
     * @param Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output
     * @return void
    */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Working...</info>');
        $output->writeln('<info>This may take a few minutes</info>');

        $handle = $this->transcriber->loadCmuFile();

        $this->makeOptions();

        $this->setOptionsField('destination', $input->getOption('destination'));

        $this->setOptionsField('fields', explodeByComma($input->getOption('fields')));

        $this->setOptionsField('file_name', $input->getOption('file'));

        $this->setOptionsField('multiple', $input->getOption('multiple'));

        $this->setOptionsField('symbol', $input->getOption('symbol'));

        $this->setOptionsField('method_names', $this->builder->buildFieldMethods($this->options['fields']));

        if (!$handle) 
        {
            $GLOBALS['status'] = 1;
            
            die('<error>File did not open properly</error>');
        }

        $destination_method = $this->builder->buildAllDestinationMethod($this->options['destination']);

        if (!method_exists($this, $destination_method))
        {
            $output->writeln("<error>Incorret destination input</error>");
            $output->writeln("<info>Destination options: </info><comment>file,database</comment>");

            $GLOBALS['status'] = 1;

            return null;
        }

        $this->$destination_method($output, $handle);
    }

    /**
     * Write all to file
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, resource $handle
     * @return void
    */
    protected function writeToFile(OutputInterface $output, $handle)
    {
        $this->setOptionsField('file_name', $this->makeFileName($this->options['file_name']));

        $output_handle = $this->getFileHandle($output, $this->options['file_name']);

        $file = $this->openFile($output, $output_handle);

        while (($line = fgets($handle)) !== false) 
        {
            if (isComment($line)) 
            {
                continue;
            }

            $exploded_line = explode(' ', $line);

            $word = trim($exploded_line[0]);

            $word = $this->parseDuplicateEntries($word, $this->options['multiple']);

            $answer = $this->makeAllOutputArray($output, $word, $exploded_line);

            $this->writeFileLine($file, $answer);
        }

        $output->writeln("<info>Successfully wrote to $file_name</info>");

        $this->closeFile($handle);
    }


    /**
     * Write all to database
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, resource $handle
     * @return void
    */
    protected function writeToDatabase(OutputInterface $output, $handle)
    {
        $connect = $this->getDatabaseConnection($output);

        $output_handle = $connect->handle();

        $statement = null;

        while (($line = fgets($handle)) !== false) 
        {
            if (isComment($line)) 
            {
                continue;
            }

            $exploded_line = explode(' ', $line);

            $word = trim($exploded_line[0]);

            $word = $this->parseDuplicateEntries($word);

            $answer = $this->makeAllOutputArray($output, $word, $exploded_line);

            if (is_null($statement))
            {
                $statement_fields = [];

                foreach (array_keys($answer) as $statement_field)
                {
                    array_push($statement_fields, $statement_field);
                }

                $statement = $connect->statement($output_handle, $statement_fields);
            }

            $connect->executeStatement($statement, $answer);
        }

        $output->writeln("<info>Successfully wrote to database</info>");

        $handle = null;
    }

    /**
     * Make all command output array for given fields
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, string $word, array $exploded_line
     * @return array
    */
    protected function makeAllOutputArray(OutputInterface $output, $word, array $exploded_line)
    {
        $answer = [];

        $answer['word'] = strtolower($word);

        array_shift($exploded_line);

        $arpabet_array = array_filter($exploded_line);

        foreach ($this->options['method_names'] as $answer_field => $method)
        {
            if (!method_exists($this, $method))
            {
                $output->writeln("<error>Incorret field input</error>");
                $output->writeln("<info>Field options: </info><comment>word,arpabet,ipa,spelling</comment>");

                $GLOBALS['status'] = 1;

                exit();
            }
            $answer_field = camelCaseToUnderscore($answer_field);

            $answer[$answer_field] = $this->$method($word, $exploded_line, $arpabet_array);
        }

        return $answer;
    }
}