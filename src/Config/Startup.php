<?php

namespace PronouncePHP\Config;

use Symfony\Component\Filesystem\Filesystem;

class Startup
{
    private $filesystem;

    /**
     * Construct
     *
     * @return void
    */
    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * Configure app on startup
     *
     * @return void
    */
    public function configure() 
    {
        $this->makeDotenvFile();
    }

    /**
     * Check for .env and make if not found
     *
     * @return void
    */
    private function makeDotenvFile() 
    {
        if (!$this->filesystem->exists('.env'))
        {
            $this->filesystem->copy('.env.example', '.env');
        }
    }
}