<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use PronouncePHP\Hyphenate\Hyphenator;
use PronouncePHP\Build\Builder;
use PronouncePHP\HyphenateCommand;

class HyphenateCommandTest extends TestCase
{
    private $hyphenator;

    private $builder;

    private $expected;

    private $app;

    private $command;

    private $command_tester;

    public function setUp()
    {
        $this->hyphenator = new Hyphenator();
        $this->builder = new Builder();
        $this->expected = new HyphenateExpectedResults();
        $this->app = new Application();
        $this->app->add(new HyphenateCommand($this->hyphenator, $this->builder));
        $this->command = $this->app->find('hyphenate');
        $this->command_tester = new CommandTester($this->command);
    }

    // No options
    public function test_single_word_hyphenate_returns_table()
    {
        $GLOBALS['status'] = 0;

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'word' => 'hotel'
        ));

        $result = $this->expected->results_single_word_hyphenate_returns_table();

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }

    public function test_multiple_word_hyphenate_returns_table()
    {
        $GLOBALS['status'] = 0;

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'word' => 'monkey,serious,bookshelf'
        ));

        $result = $this->expected->results_multiple_word_hyphenate_returns_table();

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }

    // Destination option
    public function test_single_word_hyphenate_with_destination_string_returns_string()
    {
        $GLOBALS['status'] = 0;

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'word' => 'bookshelf',
            '--destination' => 'string'
        ));

        $result = $this->expected->results_single_word_hyphenate_with_destination_string_returns_string();

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }

    public function test_multiple_word_hyphenate_with_destination_string_returns_string()
    {
        $GLOBALS['status'] = 0;

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'word' => 'monkey,serious,bookshelf',
            '--destination' => 'string'
        ));

        $result = $this->expected->results_multiple_word_hyphenate_with_destination_string_returns_string();

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }

    // Symbol Option
    public function test_symbol_option_returns_table()
    {
        $GLOBALS['status'] = 0;

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'word' => 'hotel',
            '--symbol' => '_'
        ));

        $result = $this->expected->results_symbol_option_returns_table();

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }
}