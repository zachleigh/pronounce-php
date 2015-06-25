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
             ->addOption('file', 'o', InputOption::VALUE_REQUIRED, 'Set file path for output', 'output.txt')
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
        $output->writeln('<info>Hyphenating...</info>');

        $words = explodeByComma($input->getArgument('word'));

        $destination = $input->getOption('destination');

        $file_name = $input->getOption('file');

        $symbol = $input->getOption('symbol');

        $answers = [];

        foreach ($words as $word)
        {
            $hyphenated_word = $this->hyphenator->hyphenateWord($word);

            $hyphenated_word = $this->setHyphenationSymbol($hyphenated_word, $symbol);

            $answers[$word] = $this->makeHyphenateOutputArray($word, $hyphenated_word);
        }

        $destination_method = $this->builder->buildDestinationMethod($destination);

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

        $answer['hyphenated_word'] = $hyphenated_word;

        return $answer;
    }

}