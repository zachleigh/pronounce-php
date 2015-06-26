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
             ->addOption('fields', 'f', InputOption::VALUE_REQUIRED, 'Select the output fields you wish to display', 'word,hyphenated_word,arpabet,ipa,spelling')
             ->addOption('destination', 'd', InputOption::VALUE_REQUIRED, 'Select the destination for output', 'file')
             ->addOption('file', 'o', InputOption::VALUE_REQUIRED, 'Set file path for output', 'output.txt')
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

        $fields = explodeByComma($input->getOption('fields'));

        $destination = $input->getOption('destination');

        $file_name = $input->getOption('file');

        $symbol = $input->getOption('symbol');

        $method_names = $this->builder->buildFieldMethods($fields);

        if (!$handle) 
        {
            $GLOBALS['status'] = 1;
            
            die('<error>File did not open properly</error>');
        }

        $destination_method = $this->builder->buildAllDestinationMethod($destination);

        if (!method_exists($this, $destination_method))
        {
            $output->writeln("<error>Incorret destination input</error>");
            $output->writeln("<info>Destination options: </info><comment>table,string,file,database</comment>");

            $GLOBALS['status'] = 1;

            return null;
        }

        $this->$destination_method($output, $handle, $method_names, $file_name, $symbol);
    }

    /**
     * Write all to file
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, resource $handle, array $method_names string $file_name, string $symbol
     * @return void
    */
    protected function writeToFile(OutputInterface $output, $handle, array $method_names, $file_name, $symbol)
    {
        $file_name = $this->makeFileName($file_name);

        $output_handle = $this->getFileHandle($output, $file_name);

        $file = $this->openFile($output, $output_handle);

        while (($line = fgets($handle)) !== false) 
        {
            if (isComment($line)) 
            {
                continue;
            }

            $exploded_line = explode(' ', $line);

            $word = trim($exploded_line[0]);

            $answer = $this->makeAllOutputArray($output, $word, $exploded_line, $method_names, $symbol);

            $this->writeFileLine($file, $answer);
        }

        $output->writeln("<info>Successfully wrote to $file_name</info>");

        $this->closeFile($handle);
    }


    /**
     * Write all to database
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, resource $handle, array $method_names, string $file_name, string $symbol
     * @return void
    */
    protected function writeToDatabase(OutputInterface $output, $handle, array $method_names, $file_name, $symbol)
    {
        $connect = $this->getDatabaseConnection($output);

        $output_handle = $connect->database->getHandle($output);

        $statement = null;

        while (($line = fgets($handle)) !== false) 
        {
            if (isComment($line)) 
            {
                continue;
            }

            $exploded_line = explode(' ', $line);

            $word = trim($exploded_line[0]);

            $answer = $this->makeAllOutputArray($output, $word, $exploded_line, $method_names, $symbol);

            if (is_null($statement))
            {
                $fields = [];

                foreach (array_keys($answer) as $field)
                {
                    array_push($fields, $field);
                }

                $statement = $connect->database->getStatement($output_handle, $fields, $output);
            }

            $connect->database->executeStatement($statement, $answer, $output);
        }

        $output->writeln("<info>Successfully wrote to database</info>");

        $handle = null;
    }

    /**
     * Make all command output array for given fields
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, string $word, array $exploded_line, array $method_names, string $symbol
     * @return array
    */
    protected function makeAllOutputArray(OutputInterface $output, $word, array $exploded_line, array $method_names, $symbol)
    {
        $answer = [];

        $answer['word'] = strtolower($word);

        array_shift($exploded_line);

        $arpabet_array = array_filter($exploded_line);

        foreach ($method_names as $field => $method)
        {
            if (!method_exists($this, $method))
            {
                $output->writeln("<error>Incorret field input</error>");
                $output->writeln("<info>Field options: </info><comment>word,arpabet,ipa,spelling</comment>");

                $GLOBALS['status'] = 1;

                exit();
            }
            $field = camelCaseToUnderscore($field);

            $answer[$field] = $this->$method($word, $exploded_line, $arpabet_array, $symbol);
        }

        return $answer;
    }
}