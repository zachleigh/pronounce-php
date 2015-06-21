<?php

namespace PronouncePHP;

use PronouncePHP\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use PronouncePHP\Transcribe\Transcriber;
use PronouncePHP\Build\Builder;


class LookupCommand extends Command
{
    protected $transcriber;

    protected $builder;

    /**
     * Construct
     *
     * @param Transcriber $transcribe
     * @return void
    */
    public function __construct(Transcriber $transcriber, Builder $builder)
    {
        $this->transcriber = $transcriber;
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
        $this->setName('lookup')
             ->setDescription('Convert a word or comma seperated list of words to different pronounciation strings')
             ->addArgument('word', InputArgument::REQUIRED, 'The word or words to convert')
             ->addOption('fields', 'f', InputOption::VALUE_REQUIRED, 'Select the output fields you wish to display', 'word,arpabet,ipa,spelling')
             ->addOption('destination', 'd', InputOption::VALUE_REQUIRED, 'Select the destination for output', 'table')
             ->addOption('file', null, InputOption::VALUE_REQUIRED, 'Set file path for output', 'output.txt');
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input, OutputInterface $output
     * @return void
    */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Searching...</info>');

        $handle = $this->transcriber->loadCmuFile();

        $strings = $this->explodeByComma($input->getArgument('word'));

        $fields = $this->explodeByComma($input->getOption('fields'));

        $destination = $input->getOption('destination');

        $file_name = $input->getOption('file');

        $method_names = $this->makeStringMethodNames($fields);

        if (!$handle) 
        {
            die('<error>File did not open properly</error>');
        }

        $answers = [];

        $answered = [];

        while (($line = fgets($handle)) !== false) 
        {
            if ($this->isComment($line)) 
            {
                continue;
            }

            $exploded_line = explode(' ', $line);

            $word = trim($exploded_line[0]);

            if (in_array($word, $strings))
            {
                $answers[$word] = $this->makeOutputArray($output, $word, $exploded_line, $method_names);

                array_push($answered, $word);
            }
        }

        $unanswered = array_diff($strings, $answered);

        $destination_method = $this->makeDestinationMethodName($destination);

        if (!method_exists($this, $destination_method))
        {
            $output->writeln("<error>Incorret destination input</error>");
            $output->writeln("<info>Destination options: </info><comment>table,string,file,database</comment>");

            exit();
        }

        $this->$destination_method($output, $answers, $file_name);

        $this->displayErrorForUnanswered($output, $unanswered);

        fclose($handle);
    }
}