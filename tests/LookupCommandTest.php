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
}