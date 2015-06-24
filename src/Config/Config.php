<?php

namespace PronouncePHP\Config;

class Config
{
    public static function get($key)
    {
        $config = include('config.php');

        return $config[$key];
    }
}