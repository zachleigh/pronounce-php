<?php

namespace PronouncePHP;

use PronouncePHP\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use PronouncePHP\Transcribe\Transcriber;


class LookupCommand extends Command
{
    protected $transcriber;

    /**
     * Find comments in CMU file
     *
     * @param string $line
     * @return bool
    */
    public function __construct(Transcriber $transcriber)
    {
        $this->transcriber = $transcriber;

        parent::__construct();
    }

    public function configure()
    {
        $this->setName('lookup')
             ->setDescription('Convert a word or comma seperated list of words to different pronounciation strings')
             ->addArgument('string', InputArgument::REQUIRED, 'The word or words to convert');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $handle = $this->transcriber->loadCmuFile();

        $strings = $this->inputToArray($input->getArgument('string'));

        if (!$handle) 
        {
            die('Something terrible happened');
        }

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
                $output_string = $this->buildOutput($word, $exploded_line);

                $output->writeln($output_string);
            }
        }
        
        fclose($handle);
    }
}