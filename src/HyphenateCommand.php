<?php

namespace PronouncePHP;

use PronouncePHP\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use PronouncePHP\Hyphenate\Hyphenator;
use PronouncePHP\Build\Builder;

class HyphenateCommand extends Command
{
    protected $hyphenator;

    protected $builder;

    /**
     * Construct
     *
     * @param Hyphenator $hyphenator, Builder $builder
     * @return void
    */
    public function __construct(Hyphenator $hyphenator, Builder $builder)
    {
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
        $this->setName('hyphenate')
             ->setDescription('Convert a word or comma seperated list of words to hypheanted strings')
             ->addArgument('word', InputArgument::REQUIRED, 'The word or words to hyphenate')
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
        $words = $this->explodeByComma($input->getArgument('word'));

        $destination = $input->getOption('destination');

        $file_name = $input->getOption('file');

        $answers = [];

        foreach ($words as $word)
        {
            $hyphenated_word = $this->hyphenator->hyphenateWord($word);

            $answers[$word] = $this->makeHyphenateOutputArray($word, $hyphenated_word);
        }

        $destination_method = $this->makeDestinationMethodName($destination);

        if (!method_exists($this, $destination_method))
        {
            $output->writeln("<error>Incorret destination input</error>");
            $output->writeln("<info>Destination options: </info><comment>table,string,file,database</comment>");

            $GLOBALS['status'] = 1;

            return null;
        }

        $this->$destination_method($output, $answers, $file_name);
    }

    /**
     * Make hyphenate output array for given fields
     *
     * @param OutputInterface $output, string $word, array $exploded_line, array $method_names
     * @return array
    */
    protected function makeHyphenateOutputArray($word, $hyphenated_word)
    {
        $answer = [];

        $answer['word'] = strtolower($word);

        $answer['hyphenated word'] = $hyphenated_word;

        return $answer;
    }

}