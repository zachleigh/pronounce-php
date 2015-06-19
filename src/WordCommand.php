<?php

namespace PronouncePHP;

use PronouncePHP\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use PronouncePHP\PhoneticCodes\Arpabet;
use PronouncePHP\PhoneticCodes\Ipa;
use PronouncePHP\PhoneticCodes\Spelling;

class WordCommand extends Command
{
    protected $arpabet;

    protected $ipa;

    protected $spelling;

    /**
     * Find comments in CMU file
     *
     * @param string $line
     * @return bool
    */
    public function __construct(Arpabet $arpabet, Ipa $ipa, Spelling $spelling)
    {
        $this->arpabet = $arpabet;
        $this->ipa = $ipa;
        $this->spelling = $spelling;

        parent::__construct();
    }

    public function configure()
    {
        $this->setName('word')
             ->setDescription('Convert a word or comma seperated list of words to different pronounciation strings')
             ->addArgument('string', InputArgument::REQUIRED, 'The word or words to convert');
             // ->addArgument('destination', InputArgument::REQUIRED, 'Desired output for word conversion (console, file or database)');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $handle = $this->arpabet->loadCmuFile();

        $strings = $this->inputToArray($input->getArgument('string'));

        if (!$handle) 
        {
            die('Something terrible happened');
        }

        while (($line = fgets($handle)) !== false) 
        {
            foreach ($strings as $string) 
            {
                if ($this->arpabet->isComment($line)) 
                {
                    continue;
                }

                $exploded_line = explode(' ', $line);

                $word = trim($exploded_line[0]);

                if ($word === $string)
                {
                    $output_string = $this->buildOutput($word, $exploded_line);

                    $output->writeln($output_string);
                }
            }
        } 

        fclose($handle);
    }
}