<?php

namespace PronouncePHP\Config;

use Symfony\Component\Filesystem\Filesystem;

class Startup
{
    private $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    public function configure() 
    {
        if (!$this->filesystem->exists('.env'))
        {
            $this->filesystem->copy('.env.example', '.env');
        }
    }
}