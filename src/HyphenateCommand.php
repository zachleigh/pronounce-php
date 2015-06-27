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

    protected $options;

    /**
     * Construct
     *
     * @param PronouncePHP\Hyphenate\Hyphenator $hyphenator, PronouncePHP\Build\Builder $builder
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
     * @param Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output
     * @return void
    */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Hyphenating...</info>');

        $words = explodeByComma($input->getArgument('word'));

        $this->makeOptions();

        $this->setOptionsField('destination', $input->getOption('destination'));

        $this->setOptionsField('file_name', $input->getOption('file'));

        $this->setOptionsField('symbol', $input->getOption('symbol'));

        $answers = [];

        foreach ($words as $word)
        {
            $hyphenated_word = $this->hyphenator->hyphenateWord($word);

            $hyphenated_word = $this->setHyphenationSymbol($hyphenated_word);

            $answers[$word] = $this->makeHyphenateOutputArray($word, $hyphenated_word);
        }

        $destination_method = $this->builder->buildDestinationMethod($this->options['destination']);

        if (!method_exists($this, $destination_method))
        {
            $output->writeln("<error>Incorret destination input</error>");
            $output->writeln("<info>Destination options: </info><comment>table,string,file,database</comment>");

            $GLOBALS['status'] = 1;

            return null;
        }

        $this->$destination_method($output, $answers);
    }

    /**
     * Make hyphenate output array for given fields
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, string $word, string $hyphenated_word
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