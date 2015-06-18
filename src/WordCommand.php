<?php

namespace PronouncePHP;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use PronouncePHP\PhoneticCodes\Ipa;
use PronouncePHP\PhoneticCodes\Spelling;

class WordCommand extends Command
{
    private $file = 'src/Library/cmudict';

    public function configure()
    {
        $this->setName('word')
             ->setDescription('Convert a word to pronunciation string')
             ->addArgument('string', InputArgument::REQUIRED, 'The word you wish to convert');
             // ->addArgument('destination', InputArgument::REQUIRED, 'Desired output for word conversion (console, file or database)');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $ipa = new Ipa();
        $spelling = new Spelling();

        $handle = fopen($this->file, 'r') or die('Failed to open dictionary file');

        if ($handle) 
        {
            while (($line = fgets($handle)) !== false) 
            {
                if (isComment($line)) 
                {
                    continue;
                }

                $exploded_line = explode(' ', $line);

                $word = trim($exploded_line[0]);

                $string =strtoupper($input->getArgument('string'));

                if ($word === $string)
                {
                    array_shift($exploded_line);

                    $arpabet_array = array_filter($exploded_line);

                    $arpabet_string = trim(implode(' ', $exploded_line));

                    $ipa_string = $ipa->buildIpaString($arpabet_array);

                    $spelling_string = $spelling->buildSpellingString($arpabet_array);

                    echo 'Word: ' . $word . "\n" . 'Arpabet: ' . $arpabet_string . ' IPA: ' . $ipa_string . ' Spelling: ' . $spelling_string . "\n\n";
                }
            }
        } 
        else 
        {
            die('Something terrible happened');
        } 

        fclose($handle);
    }
}