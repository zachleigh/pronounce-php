<?php

namespace PronouncePHP;

use PronouncePHP\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use PronouncePHP\Syllabize\Syllabizer;

class HyphenateCommand extends Command
{
    protected $syllabizer;

    /**
     * Construct
     *
     * @param Transcriber $transcribe
     * @return void
    */
    public function __construct(Syllabizer $syllabizer)
    {
        $this->syllabizer = $syllabizer;

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
             ->addArgument('word', InputArgument::REQUIRED, 'The word or words to hyphenate');
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

        foreach ($words as $word)
        {
            $string = $this->syllabizer->hyphenateWord($word);

            $output->writeln($string);
        }
    }

}