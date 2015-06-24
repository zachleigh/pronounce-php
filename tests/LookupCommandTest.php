<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use PronouncePHP\Transcribe\Transcriber;
use PronouncePHP\Hyphenate\Hyphenator;
use PronouncePHP\Build\Builder;
use PronouncePHP\LookupCommand;

class LookupCommandTest extends TestCase
{
    private $transcriber;

    private $hyphenator;

    private $builder;

    private $expected;

    private $app;

    private $command;

    private $command_tester;

    public function setUp()
    {
        $this->transcriber = new Transcriber();
        $this->hyphenator = new Hyphenator();
        $this->builder = new Builder();
        $this->expected = new ExpectedResults();
        $this->app = new Application();
        $this->app->add(new LookupCommand($this->transcriber, $this->hyphenator, $this->builder));
        $this->command = $this->app->find('lookup');
        $this->command_tester = new CommandTester($this->command);
    }

    public function test_single_word_lookup_returns_table() 
    {
        $GLOBALS['status'] = 0;

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'word' => 'word'
        ));

        $result = $this->expected->results_single_word_lookup_returns_table();

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }

    public function test_double_word_lookup_returns_table() 
    {
        $GLOBALS['status'] = 0;

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'word' => 'hello,bye'
        ));

        $result = $this->expected->results_double_word_lookup_returns_table();

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }

    public function test_multiple_word_lookup_returns_table() 
    {
        $GLOBALS['status'] = 0;

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'word' => 'orange,red,grey,blue,pink,purple'
        ));

        $result = $this->expected->results_multiple_word_lookup_returns_table();

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }

    public function test_misspelled_word_lookup_returns_error() 
    {
        $GLOBALS['status'] = 0;

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'word' => 'lskdfh'
        ));

        $result = $this->expected->results_misspelled_word_lookup_returns_error();

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }

    public function test_single_word_lookup_with_fields_option_returns_table() 
    {
        $GLOBALS['status'] = 0;

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'word' => 'radio',
            '--fields' => 'word,ipa'
        ));

        $result = $this->expected->results_single_word_lookup_with_fields_option_returns_table();

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }

    public function test_multiple_word_lookup_with_fields_option_returns_table() 
    {
        $GLOBALS['status'] = 0;

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'word' => 'radio,headphones',
            '--fields' => 'word,ipa,spelling'
        ));

        $result = $this->expected->results_multiple_word_lookup_with_fields_option_returns_table();

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }

    public function test_single_word_lookup_with_destination_string_returns_string() 
    {
        $GLOBALS['status'] = 0;

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'word' => 'insect',
            '--destination' => 'string'
        ));

        $result = $this->expected->results_single_word_lookup_with_destination_string_returns_string();

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }

    public function test_incorrect_fields_option_returns_error() 
    {
        $GLOBALS['status'] = 0;

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'word' => 'insect',
            '--fields' => 'word,ipa,jibberish'
        ));

        $result = $this->expected->results_incorrect_fields_option_returns_error();

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }

    public function test_incorrect_destination_option_returns_error() 
    {
        $GLOBALS['status'] = 0;

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'word' => 'insect',
            '--destination' => 'pocket'
        ));

        $result = $this->expected->results_incorrect_destination_option_returns_error();

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }
}