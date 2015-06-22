<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use PronouncePHP\Transcribe\Transcriber;
use PronouncePHP\Build\Builder;
use PronouncePHP\LookupCommand;

class LookupCommandTest extends TestCase
{
    public function test_single_word_lookup_returns_table() 
    {
        $transcriber = new Transcriber();
        $builder = new Builder();
        $expected = new ExpectedResults();
        $app = new Application();
        $GLOBALS['status'] = 0;

        $app->add(new LookupCommand($transcriber, $builder));

        $command = $app->find('lookup');

        $command_tester = new CommandTester($command);

        $command_tester->execute(array(
            'command' => $command->getName(),
            'word' => 'word'
        ));

        $result = $expected->results_single_word_lookup_returns_table();

        $this->assertEquals($result, $command_tester->getDisplay());
    }

    public function test_double_word_lookup_returns_table() 
    {
        $transcriber = new Transcriber();
        $builder = new Builder();
        $expected = new ExpectedResults();
        $app = new Application();
        $GLOBALS['status'] = 0;

        $app->add(new LookupCommand($transcriber, $builder));

        $command = $app->find('lookup');

        $command_tester = new CommandTester($command);

        $command_tester->execute(array(
            'command' => $command->getName(),
            'word' => 'hello,bye'
        ));

        $result = $expected->results_double_word_lookup_returns_table();

        $this->assertEquals($result, $command_tester->getDisplay());
    }

    public function test_multiple_word_lookup_returns_table() 
    {
        $transcriber = new Transcriber();
        $builder = new Builder();
        $expected = new ExpectedResults();
        $app = new Application();
        $GLOBALS['status'] = 0;

        $app->add(new LookupCommand($transcriber, $builder));

        $command = $app->find('lookup');

        $command_tester = new CommandTester($command);

        $command_tester->execute(array(
            'command' => $command->getName(),
            'word' => 'orange,red,grey,blue,pink,purple'
        ));

        $result = $expected->results_multiple_word_lookup_returns_table();

        $this->assertEquals($result, $command_tester->getDisplay());
    }

    public function test_misspelled_word_lookup_returns_error() 
    {
        $transcriber = new Transcriber();
        $builder = new Builder();
        $expected = new ExpectedResults();
        $app = new Application();
        $GLOBALS['status'] = 0;

        $app->add(new LookupCommand($transcriber, $builder));

        $command = $app->find('lookup');

        $command_tester = new CommandTester($command);

        $command_tester->execute(array(
            'command' => $command->getName(),
            'word' => 'lskdfh'
        ));

        $result = $expected->results_misspelled_word_lookup_returns_error();

        $this->assertEquals($result, $command_tester->getDisplay());
    }

    public function test_single_word_lookup_with_fields_option_returns_table() 
    {
        $transcriber = new Transcriber();
        $builder = new Builder();
        $expected = new ExpectedResults();
        $app = new Application();
        $GLOBALS['status'] = 0;

        $app->add(new LookupCommand($transcriber, $builder));

        $command = $app->find('lookup');

        $command_tester = new CommandTester($command);

        $command_tester->execute(array(
            'command' => $command->getName(),
            'word' => 'radio',
            '--fields' => 'word,ipa'
        ));

        $result = $expected->results_single_word_lookup_with_fields_option_returns_table();

        $this->assertEquals($result, $command_tester->getDisplay());
    }

    public function test_multiple_word_lookup_with_fields_option_returns_table() 
    {
        $transcriber = new Transcriber();
        $builder = new Builder();
        $expected = new ExpectedResults();
        $app = new Application();
        $GLOBALS['status'] = 0;

        $app->add(new LookupCommand($transcriber, $builder));

        $command = $app->find('lookup');

        $command_tester = new CommandTester($command);

        $command_tester->execute(array(
            'command' => $command->getName(),
            'word' => 'radio,headphones',
            '--fields' => 'word,ipa,spelling'
        ));

        $result = $expected->results_multiple_word_lookup_with_fields_option_returns_table();

        $this->assertEquals($result, $command_tester->getDisplay());
    }

    public function test_single_word_lookup_with_destination_string_returns_string() 
    {
        $transcriber = new Transcriber();
        $builder = new Builder();
        $expected = new ExpectedResults();
        $app = new Application();
        $GLOBALS['status'] = 0;

        $app->add(new LookupCommand($transcriber, $builder));

        $command = $app->find('lookup');

        $command_tester = new CommandTester($command);

        $command_tester->execute(array(
            'command' => $command->getName(),
            'word' => 'insect',
            '--destination' => 'string'
        ));

        $result = $expected->results_single_word_lookup_with_destination_string_returns_string();

        $this->assertEquals($result, $command_tester->getDisplay());
    }

    public function test_incorrect_fields_option_returns_error() 
    {
        $transcriber = new Transcriber();
        $builder = new Builder();
        $expected = new ExpectedResults();
        $app = new Application();
        $GLOBALS['status'] = 0;

        $app->add(new LookupCommand($transcriber, $builder));

        $command = $app->find('lookup');

        $command_tester = new CommandTester($command);

        $command_tester->execute(array(
            'command' => $command->getName(),
            'word' => 'insect',
            '--fields' => 'word,ipa,jibberish'
        ));

        $result = $expected->results_incorrect_fields_option_returns_error();

        $this->assertEquals($result, $command_tester->getDisplay());
    }

    public function test_incorrect_destination_option_returns_error() 
    {
        $transcriber = new Transcriber();
        $builder = new Builder();
        $expected = new ExpectedResults();
        $app = new Application();
        $GLOBALS['status'] = 0;

        $app->add(new LookupCommand($transcriber, $builder));

        $command = $app->find('lookup');

        $command_tester = new CommandTester($command);

        $command_tester->execute(array(
            'command' => $command->getName(),
            'word' => 'insect',
            '--destination' => 'pocket'
        ));

        $result = $expected->results_incorrect_destination_option_returns_error();

        $this->assertEquals($result, $command_tester->getDisplay());
    }
}