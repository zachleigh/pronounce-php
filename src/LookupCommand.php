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

class LookupCommand extends Command
{
    protected $transcriber;

    protected $hyphenator;

    protected $builder;

    /**
     * Construct
     *
     * @param Transcriber $transcribe, Hyphenator $hyphenator, Builder $builder
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
        $this->setName('lookup')
             ->setDescription('Convert a word or comma seperated list of words to different pronounciation strings')
             ->addArgument('word', InputArgument::REQUIRED, 'The word or words to convert')
             ->addOption('fields', 'f', InputOption::VALUE_REQUIRED, 'Select the output fields you wish to display', 'word,arpabet,ipa,spelling')
             ->addOption('destination', 'd', InputOption::VALUE_REQUIRED, 'Select the destination for output', 'table')
             ->addOption('file', 'o', InputOption::VALUE_REQUIRED, 'Set file path for output', 'output.txt')
             ->addOption('hyphenate', 'y', InputOption::VALUE_NONE, 'Hyphenate word in output')
             ->addOption('symbol', 's', InputOption::VALUE_REQUIRED, 'Set the symbol used to divide words', '-');
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

        $strings = explodeByComma($input->getArgument('word'));

        $fields = explodeByComma($input->getOption('fields'));

        $destination = $input->getOption('destination');

        $file_name = $input->getOption('file');

        $hyphenation = $input->getOption('hyphenate');

        $symbol = $input->getOption('symbol');

        $method_names = $this->builder->buildStringMethod($fields);

        if (!$handle) 
        {
            $GLOBALS['status'] = 1;
            
            die('<error>File did not open properly</error>');
        }

        $answers = [];

        $answered = [];

        while (($line = fgets($handle)) !== false) 
        {
            if (isComment($line)) 
            {
                continue;
            }

            $exploded_line = explode(' ', $line);

            $word = trim($exploded_line[0]);

            if (in_array($word, $strings))
            {
                $output_word = $this->hyphenateOutputWord($this->hyphenator, $word, $hyphenation);

                $output_word = $this->setHyphenationSymbol($output_word, $symbol);

                $answers[$word] = $this->makeLookupOutputArray($output, $output_word, $exploded_line, $method_names);

                array_push($answered, $word);
            }
        }

        if ($GLOBALS['status'] !== 0) {
            return null;
        }

        $unanswered = array_diff($strings, $answered);

        $destination_method = $this->builder->buildDestinationMethod($destination);

        if (!method_exists($this, $destination_method))
        {
            $output->writeln("<error>Incorret destination input</error>");
            $output->writeln("<info>Destination options: </info><comment>table,string,file,database</comment>");

            $GLOBALS['status'] = 1;

            return null;
        }

        $this->$destination_method($output, $answers, $file_name);

        $this->displayErrorForUnanswered($output, $unanswered);

        fclose($handle);
    }

    /**
     * Make lookup output array for given fields
     *
     * @param OutputInterface $output, string $word, array $exploded_line, array $method_names
     * @return array
    */
    protected function makeLookupOutputArray($output, $word, $exploded_line, array $method_names)
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

                $GLOBALS['status'] = 1;

                break;
            }

            $answer[$field] = $this->$method($word, $exploded_line, $arpabet_array);
        }

        return $answer;
    }
}