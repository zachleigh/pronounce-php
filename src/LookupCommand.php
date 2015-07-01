<?php

namespace PronouncePHP;

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

    protected $options;

    /**
     * Construct.
     *
     * @param PronouncePHP\Transcribe\Transcriber $transcribe, PronouncePHP\Hyphenate\Hyphenator $hyphenator, PronouncePHP\Build\Builder $builder
     */
    public function __construct(Transcriber $transcriber, Hyphenator $hyphenator, Builder $builder)
    {
        $this->transcriber = $transcriber;
        $this->hyphenator = $hyphenator;
        $this->builder = $builder;

        parent::__construct();
    }

    /**
     * Configure command.
     */
    public function configure()
    {
        $this->setName('lookup')
             ->setDescription('Convert a word or comma seperated list of words to different pronounciation strings')
             ->addArgument('word', InputArgument::REQUIRED, 'The word or words to convert')
             ->addOption('destination', 'd', InputOption::VALUE_REQUIRED, 'Select the destination for output', 'table')
             ->addOption('fields', 'f', InputOption::VALUE_REQUIRED, 'Select the output fields you wish to display', 'word,arpabet,ipa,spelling')
             ->addOption('file', 'o', InputOption::VALUE_REQUIRED, 'Set file path for output', 'output.txt')
             ->addOption('hyphenate', 'y', InputOption::VALUE_NONE, 'Hyphenate word in output')
             ->addOption('multiple', 'm', InputOption::VALUE_REQUIRED, 'Set behavior for duplicate entries in CMUdict file', 'none')
             ->addOption('symbol', 's', InputOption::VALUE_REQUIRED, 'Set the symbol used to divide words', '-');
    }

    /**
     * Execute the command.
     *
     * @param Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Searching...</info>');

        $handle = $this->transcriber->loadCmuFile();

        $strings = explodeByComma($input->getArgument('word'));

        $this->makeOptions();

        $this->setOptionsField('destination', $input->getOption('destination'));

        $this->setOptionsField('fields', explodeByComma($input->getOption('fields')));

        $this->setOptionsField('file_name', $input->getOption('file'));

        $this->setOptionsField('hyphenate', $input->getOption('hyphenate'));

        $this->setOptionsField('multiple', $input->getOption('multiple'));

        $this->setOptionsField('symbol', $input->getOption('symbol'));

        $this->setOptionsField('method_names', $this->builder->buildFieldMethods($this->options['fields']));

        if (!$handle) {
            $GLOBALS['status'] = 1;

            die('<error>File did not open properly</error>');
        }

        $answers = [];

        $answered = [];

        while (($line = fgets($handle)) !== false) {
            if (isComment($line)) {
                continue;
            }

            $exploded_line = explode(' ', $line);

            $word = trim($exploded_line[0]);

            $word = $this->parseDuplicateEntries($word);

            if (in_array($word, $strings)) {
                $output_word = $this->hyphenateOutputWord($this->hyphenator, $word);

                $output_word = $this->setHyphenationSymbol($output_word);

                $key = $this->makeAnswersKey($word, $answers);

                $answers[$key] = $this->makeLookupOutputArray($output, $output_word, $exploded_line);

                array_push($answered, $word);
            }
        }

        if ($GLOBALS['status'] !== 0) {
            return;
        }

        $unanswered = array_diff($strings, $answered);

        $destination_method = $this->builder->buildDestinationMethod($this->options['destination']);

        if (!method_exists($this, $destination_method)) {
            $output->writeln('<error>Incorret destination input</error>');
            $output->writeln('<info>Destination options: </info><comment>table,string,file,database</comment>');

            $GLOBALS['status'] = 1;

            return;
        }

        $this->$destination_method($output, $answers);

        $this->displayErrorForUnanswered($output, $unanswered);

        fclose($handle);
    }

    /**
     * Make lookup output array for given fields.
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output, string $word, array $exploded_line
     *
     * @return array
     */
    protected function makeLookupOutputArray(OutputInterface $output, $word, array $exploded_line)
    {
        $answer = [];

        array_shift($exploded_line);

        $arpabet_array = array_filter($exploded_line);

        foreach ($this->options['method_names'] as $answer_field => $method) {
            if (!method_exists($this, $method)) {
                $output->writeln('<error>Incorret field input</error>');
                $output->writeln('<info>Field options: </info><comment>word,arpabet,ipa,spelling</comment>');

                $GLOBALS['status'] = 1;

                break;
            }

            $answer[$answer_field] = $this->$method($word, $exploded_line, $arpabet_array);
        }

        return $answer;
    }
}
